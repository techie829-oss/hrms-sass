<?php

namespace App\Http\Middleware;

use Closure;
use App\Modules\HR\Models\Employee;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployeeProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && saas_tenant()) {
            $user = auth()->user();
            
            // 1. Ensure employee record exists and is mapped to this user
            // Check by user_id first, then fallback to email (unique per tenant)
            $employee = Employee::where('tenant_id', saas_tenant('id'))
                ->where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })
                ->first();

            if (!$employee) {
                // Create new if absolutely missing
                Employee::create([
                    'tenant_id' => saas_tenant('id'),
                    'user_id' => $user->id,
                    'employee_id' => 'EMP-' . strtoupper(substr(saas_tenant('id'), 0, 3)) . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                    'first_name' => explode(' ', $user->name)[0] ?? $user->name,
                    'last_name' => explode(' ', $user->name)[1] ?? 'Admin',
                    'email' => $user->email,
                    'date_of_joining' => $user->created_at ?? now(),
                    'employment_type' => 'full_time',
                    'status' => 'active',
                ]);
            } elseif ($employee->user_id !== $user->id) {
                // Link existing record by email to this user_id
                $employee->update(['user_id' => $user->id]);
            }
        }

        return $next($request);
    }
}
