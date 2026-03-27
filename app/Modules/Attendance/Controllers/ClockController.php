<?php

namespace App\Modules\Attendance\Controllers;

use App\Core\BaseController;
use App\Modules\Attendance\Models\AttendanceLog;
use App\Modules\Attendance\Models\AttendanceShift;
use App\Modules\Attendance\Models\AttendancePolicy;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClockController extends BaseController
{
    /**
     * Show the Kiosk view.
     */
    public function kiosk()
    {
        $user = auth()->user();
        $employee = $user->employee;
        $today = Carbon::today();

        $todayLog = null;
        $shift = null;

        if ($employee) {
            $todayLog = AttendanceLog::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->first();

            $shift = $employee->attendanceShift ?: AttendanceShift::where('is_active', true)
                ->where('is_default', true)
                ->first();
        }

        // Recent 5 days attendance for mini-history
        $recentLogs = $employee
            ? AttendanceLog::where('employee_id', $employee->id)
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get()
            : collect();

        // Fetch effective policy
        $policy = $this->getEffectivePolicy();

        // Check if Kiosk is even enabled for this policy
        if ($policy && !$policy->is_kiosk_enabled) {
            return redirect()->route('attendance.index')->with('error', 'Kiosk attendance is currently disabled by admin.');
        }

        return view('attendance::kiosk', compact('user', 'employee', 'todayLog', 'shift', 'recentLogs', 'policy'));
    }

    /**
     * Handle Clock In.
     */
    public function clockIn(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;
        
        $policy = $this->getEffectivePolicy();

        $request->validate([
            'latitude' => ($policy && $policy->kiosk_require_location) ? 'required' : 'nullable',
            'longitude' => ($policy && $policy->kiosk_require_location) ? 'required' : 'nullable',
            'photo' => ($policy && $policy->kiosk_require_photo) ? 'required' : 'nullable',
        ], [
            'latitude.required' => 'Location access is mandatory.',
            'longitude.required' => 'Location access is mandatory.',
            'photo.required' => 'Photo capture is mandatory.',
        ]);

        if (!$employee) {
            return back()->with('error', 'Employee record not found for your user account.');
        }

        $today = Carbon::today();

        // Check if already clocked in today
        $existing = AttendanceLog::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already clocked in today.');
        }

        // Find Shift: Check employee assignment first, then default
        $shift = $employee->attendanceShift ?: AttendanceShift::where('is_active', true)
            ->where('is_default', true)
            ->first();

        $isLate = false;
        $lateMinutes = 0;
        $now = Carbon::now();

        if ($shift) {
            $startTime = Carbon::createFromFormat('H:i:s', Carbon::parse($shift->start_time)->format('H:i:s'))
                ->setDate($today->year, $today->month, $today->day);
            
            $graceTime = $startTime->copy()->addMinutes($shift->grace_minutes);

            if ($now->greaterThan($graceTime)) {
                $isLate = true;
                $lateMinutes = $now->diffInMinutes($startTime);
            }
        }

        // Save check-in photo
        $photoPath = $this->savePhoto($request->input('photo'), $employee->id, 'checkin');

        AttendanceLog::create([
            'tenant_id' => saas_tenant('id'),
            'employee_id' => $employee->id,
            'attendance_shift_id' => $shift?->id,
            'date' => $today,
            'check_in' => $now,
            'status' => $isLate ? 'late' : 'present',
            'is_late' => $isLate,
            'late_minutes' => $lateMinutes,
            'check_in_ip' => $request->ip(),
            'check_in_lat' => $request->input('latitude'),
            'check_in_lng' => $request->input('longitude'),
            'check_in_photo' => $photoPath,
            'remarks' => $request->input('device_info'),
        ]);

        $msg = 'Clocked in at ' . $now->format('h:i A');
        if ($isLate) {
            $msg .= ' — Late by ' . $lateMinutes . ' minutes';
        }

        return back()->with('success', $msg);
    }

    /**
     * Handle Clock Out.
     */
    public function clockOut(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        $policy = $this->getEffectivePolicy();

        $request->validate([
            'latitude' => ($policy && $policy->kiosk_require_location) ? 'required' : 'nullable',
            'longitude' => ($policy && $policy->kiosk_require_location) ? 'required' : 'nullable',
            'photo' => ($policy && $policy->kiosk_require_photo) ? 'required' : 'nullable',
        ], [
            'latitude.required' => 'Location access is mandatory.',
            'longitude.required' => 'Location access is mandatory.',
            'photo.required' => 'Photo capture is mandatory.',
        ]);

        if (!$employee) {
            return back()->with('error', 'Employee record not found.');
        }

        $today = Carbon::today();

        $log = AttendanceLog::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->whereNotNull('check_in')
            ->whereNull('check_out')
            ->first();

        if (!$log) {
            return back()->with('error', 'No active clock-in found for today.');
        }

        $checkOutTime = Carbon::now();
        $checkInTime = Carbon::parse($log->check_in);
        
        // Calculate worked hours
        $diffInMinutes = $checkInTime->diffInMinutes($checkOutTime);
        $workedHours = round($diffInMinutes / 60, 2);

        $overtimeMinutes = 0;
        $shift = $log->shift;

        if ($shift) {
            $endTime = Carbon::createFromFormat('H:i:s', Carbon::parse($shift->end_time)->format('H:i:s'))
                ->setDate($today->year, $today->month, $today->day);
            
            if ($checkOutTime->greaterThan($endTime)) {
                $overtimeMinutes = $checkOutTime->diffInMinutes($endTime);
            }
        }

        // Save check-out photo
        $photoPath = $this->savePhoto($request->input('photo'), $employee->id, 'checkout');

        // Append device info to remarks
        $existingRemarks = $log->remarks ?? '';
        $deviceInfo = $request->input('device_info');
        $updatedRemarks = $existingRemarks;
        if ($deviceInfo) {
            $updatedRemarks = trim($existingRemarks . ' | OUT: ' . $deviceInfo);
        }

        $log->update([
            'check_out' => $checkOutTime,
            'worked_hours' => $workedHours,
            'overtime_minutes' => $overtimeMinutes,
            'check_out_ip' => $request->ip(),
            'check_out_lat' => $request->input('latitude'),
            'check_out_lng' => $request->input('longitude'),
            'check_out_photo' => $photoPath,
            'remarks' => $updatedRemarks,
        ]);

        $msg = 'Clocked out at ' . $checkOutTime->format('h:i A') . '. Worked ' . $workedHours . ' hours.';
        if ($overtimeMinutes > 0) {
            $msg .= ' (Overtime: ' . $overtimeMinutes . ' mins)';
        }

        return back()->with('success', $msg);
    }

    /**
     * Save a base64-encoded photo to storage.
     */
    private function savePhoto(?string $base64, int $employeeId, string $type): ?string
    {
        if (!$base64 || !str_contains($base64, 'base64,')) {
            return null;
        }

        $imageData = base64_decode(explode(',', $base64)[1]);
        $filename = 'attendance-photos/' . $employeeId . '_' . $type . '_' . now()->format('Ymd_His') . '.jpg';
        Storage::disk('public')->put($filename, $imageData);

        return $filename;
    }

    /**
     * Get the effective attendance policy for the current context.
     */
    private function getEffectivePolicy(): ?AttendancePolicy
    {
        // For now, return the default policy for the tenant
        return AttendancePolicy::where('is_default', true)
            ->where('is_active', true)
            ->first();
    }
}
