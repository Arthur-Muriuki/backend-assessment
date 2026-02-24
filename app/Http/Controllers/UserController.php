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
        // Basic validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = User::create($validated);

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
        // Load the user's wallets. 
        // Note: The 'balance' is automatically appended to each wallet 
        // because of the getBalanceAttribute() we added in the Wallet model!
        $wallets = $user->wallets;

        // Calculate the overall balance across all wallets
        $totalBalance = $wallets->sum('balance');

        return response()->json([
            'user' => $user,
            'total_balance' => $totalBalance,
            'wallets' => $wallets
        ]);
    }
}