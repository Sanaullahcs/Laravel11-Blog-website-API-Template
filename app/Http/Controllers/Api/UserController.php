<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * List all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all(); // Retrieve all users
        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully.',
            'data' => $users // Return users data
        ]);
    }

    /**
     * Show a specific user by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id); // Find user by ID
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found' // Return 404 if user not found
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully.',
            'data' => $user // Return user data
        ]);
    }

    /**
     * Update a specific user by ID.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id); // Find user by ID
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found' // Return 404 if user not found
            ], 404);
        }

        // Validate request data
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        // Update user fields if present in the request
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password); // Hash the password before saving
        }

        $user->save(); // Save updated user

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user // Return updated user data
        ]);
    }

    /**
     * Delete a specific user by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id); // Find user by ID
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found' // Return 404 if user not found
            ], 404);
        }

        $user->delete(); // Delete the user

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully' // Return success message
        ], 200); // Use 200 OK since we're returning a success message
    }
}
