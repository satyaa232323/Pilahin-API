<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8',
            ]);

            $validated['password'] = bcrypt($validated['password']);
            $validated['qr_code'] = strtoupper(Str::random(10));

            // Generate QR code image using png format (doesn't require imagick)
            $qrImage = QrCode::format(format: 'png')->size(300)->generate($validated['qr_code']);
            $path = 'qr_codes/' . $validated['qr_code'] . '.png';
            Storage::disk('public')->put(path: $path, contents: $qrImage);

            $validated['qr_image_url'] = Storage::url($path);

            $user = User::create($validated);
            $token = $user->createToken('auth_token')->plainTextToken;

            return ResponseHelper::success('User registered successfully', [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'qr_code' => $user->qr_code,
                    'qr_image_url' => $user->qr_image_url,
                    'role' => $user->role,
                    'points' => $user->points,
                    'income' => $user->income
                ],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ResponseHelper::validationError('Validation failed', $e->errors());
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Registration failed: ' . $e->getMessage());
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($validated)) {
                $user = Auth::user();
                $token = $user->createToken('auth_token')->plainTextToken;

                return ResponseHelper::success('Login successful', [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'qr_code' => $user->qr_code,
                        'qr_image_url' => $user->qr_image_url,
                        'role' => $user->role,
                        'points' => $user->points,
                        'income' => $user->income
                    ],
                ]);
            }

            return ResponseHelper::unauthorized('Invalid credentials');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ResponseHelper::validationError('Validation failed', $e->errors());
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Login failed: ' . $e->getMessage());
        }
    }

    /**
     * Admin login
     */
    public function adminLogin(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($validated)) {
                $user = Auth::user();

                // Check if user has admin role
                if ($user->role !== 'admin') {
                    Auth::logout();
                    return ResponseHelper::unauthorized('Access denied. Admin privileges required.');
                }

                $token = $user->createToken('auth_token')->plainTextToken;

                return ResponseHelper::success('Admin login successful', [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'role' => $user->role,
                        'points' => $user->points
                    ],
                ]);
            }

            return ResponseHelper::unauthorized('Invalid credentials');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ResponseHelper::validationError('Validation failed', $e->errors());
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Admin login failed: ' . $e->getMessage());
        }
    }

    /**
     * Super admin login
     */
    public function superAdminLogin(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($validated)) {
                $user = Auth::user();

                // Check if user has superadmin role
                if ($user->role !== 'superadmin') {
                    Auth::logout();
                    return ResponseHelper::unauthorized('Access denied. Super admin privileges required.');
                }

                $token = $user->createToken('auth_token')->plainTextToken;

                return ResponseHelper::success('Super admin login successful', [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'role' => $user->role,
                        'points' => $user->points
                    ],
                ]);
            }

            return ResponseHelper::unauthorized('Invalid credentials');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ResponseHelper::validationError('Validation failed', $e->errors());
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Super admin login failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return ResponseHelper::success('Successfully logged out');
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Logout failed: ' . $e->getMessage());
        }
    }

    /**
     * Get the current authenticated user profile
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            return ResponseHelper::success('User profile retrieved successfully', [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'qr_code' => $user->qr_code,
                    'qr_image_url' => $user->qr_image_url,
                    'role' => $user->role,
                    'points' => $user->points
                ]
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::serverError('Failed to retrieve profile: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
