<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return ResponseHelper::success('User list retrieved successfully', $user);
    }

    /**
     * Get authenticated user profile.
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            return ResponseHelper::success('Profile retrieved successfully', $user);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to retrieve profile: ' . $e->getMessage());
        }
    }

    /**
     * Update user profile.
     */
    public function updateProfile(UserRequest $request, $id)
    {
        try {
            $user = User::find($id);

            // chek if user authenticated
            if ($request->user()->id !== $user->id) {
                return ResponseHelper::forbidden('You are not authorized to update this profile');
            }

            $validated = $request->validated();

            // hash password if provided

            if (isset($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);

                // Remove password_confirmation from validated data
                unset($validated['password_confirmation']);
            }

            // Update user profile
            $user->update($validated);

            $user->refresh();


            return ResponseHelper::success('Profile updated successfully', [
                'user' => $user,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ResponseHelper::notFound('User not found');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ResponseHelper::validationError('Validation failed', $e->errors());
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Profile update failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function GetQrText(Request $request)
    {
        try {
            $validated = $request->validate([
                'qr_code' => 'required|string'
            ]);

            // Check if authenticated user is admin
            $admin = $request->user();
            if ($admin->role !== 'admin' && $admin->role !== 'superadmin') {
                return ResponseHelper::forbidden('Only admin can scan QR codes');
            }

            // Find user by QR code
            $user = User::where('qr_code', $validated['qr_code'])->first();

            if (!$user) {
                return ResponseHelper::notFound('User not found with this QR code');
            }

            // Get user profile with additional information
            $userProfile = [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'points' => $user->points,
                'role' => $user->role,
                'qr_code' => $user->qr_code,
                'created_at' => $user->created_at,
                'last_scan_by' => $admin->name,
                'scan_time' => now()
            ];

            return ResponseHelper::success('User profile retrieved successfully', $userProfile);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ResponseHelper::validationError('Validation failed', $e->errors());
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to retrieve user profile: ' . $e->getMessage());
        }
    }




    /**
     * Update the specified resource in storage.
     */

    public function getQrImage(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user->qr_image_url) {
                return ResponseHelper::notFound('QR code image not found');
            }

            return ResponseHelper::success('QR image retrieved successfully', [
                'qr_image_url' => $user->qr_image_url,
                'qr_code' => $user->qr_code,
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to retrieve QR image: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
}