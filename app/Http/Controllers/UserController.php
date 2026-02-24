<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new user account without authentication (Requirement #1)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request ensuring required fields and uniqueness
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // 2. Create the user and strictly hash the password for security
        $user = User::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // 3. Return a success response with the newly created user
        return response()->json([
            'message' => 'User created successfully',
            'user'    => $user
        ], 201);
    }

    /**
     * View a user profile including all wallets, balances, and total balance (Requirement #4)
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        // Eager load the wallets and their nested transactions to ensure efficient database queries
        $user->load('wallets.transactions');

        return response()->json([
            'user'          => $user,
            // Dynamically calculate the total balance across all the user's wallets
            'total_balance' => $user->wallets->sum('balance')
        ]);
    }
}