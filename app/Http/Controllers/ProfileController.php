<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update linked employee record if it exists
        if ($user->employee) {
            $employeeData = [
                'phone' => $data['phone'] ?? $user->employee->phone,
                'gender' => $data['gender'] ?? $user->employee->gender,
                'date_of_birth' => $data['date_of_birth'] ?? $user->employee->date_of_birth,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? $user->employee->emergency_contact_name,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? $user->employee->emergency_contact_phone,
                'emergency_contact_relation' => $data['emergency_contact_relation'] ?? $user->employee->emergency_contact_relation,
                'current_address' => $data['current_address'] ?? $user->employee->current_address,
                'permanent_address' => $data['permanent_address'] ?? $user->employee->permanent_address,
            ];

            if ($request->hasFile('profile_photo')) {
                $employeeData['profile_photo'] = $request->file('profile_photo')->store('avatars', 'public');
            }

            if ($request->hasFile('cover_photo')) {
                $employeeData['cover_photo'] = $request->file('cover_photo')->store('covers', 'public');
            }

            $user->employee->update($employeeData);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();
        if ($user->employee) {
            $path = $request->file('profile_photo')->store('avatars', 'public');
            $user->employee->update(['profile_photo' => $path]);
        }

        return Redirect::back()->with('status', 'photo-updated');
    }

    /**
     * Update the user's cover photo.
     */
    public function updateCover(Request $request): RedirectResponse
    {
        $request->validate([
            'cover_photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $user = $request->user();
        if ($user->employee) {
            $path = $request->file('cover_photo')->store('covers', 'public');
            $user->employee->update(['cover_photo' => $path]);
        }

        return Redirect::back()->with('status', 'cover-updated');
    }

    /**
     * Update the employee's main official image (HR Only).
     */
    public function updateMainImage(Request $request): RedirectResponse
    {
        // Simple permission check (can be replaced with Spatie middleware)
        if (!$request->user()->hasRole('super_admin') && !$request->user()->can('manage employees')) {
            abort(403);
        }

        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'main_image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $employee = \App\Modules\HR\Models\Employee::findOrFail($request->employee_id);
        $path = $request->file('main_image')->store('official_photos', 'public');
        $employee->update(['main_image' => $path]);

        return Redirect::back()->with('status', 'main-image-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
