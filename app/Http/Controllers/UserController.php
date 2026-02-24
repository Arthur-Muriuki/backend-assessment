<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new user account (Requirement #1)
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // 2. Create the user (remember to hash the password!)
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // 3. Return a success response
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    /**
     * View user profile, wallets, and balances (Requirement #4)
     */
    public function show(User $user)
    {
        // Eager load the wallets and their transactions to include balances
        $user->load('wallets.transactions');

        return response()->json([
            'user' => $user,
            // We can also calculate total balance across all wallets if desired
            'total_balance' => $user->wallets->sum('balance')
        ]);
    }
}