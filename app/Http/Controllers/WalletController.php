<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Create one or more wallets for a user (Requirement #2)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // 1. Validate the request ensures the user exists and the wallet has a name
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name'    => 'required|string|max:255',
            'balance' => 'nullable|numeric|min:0', // Optional starting balance, must not be negative
        ]);

        // 2. Create the wallet in the database
        $wallet = Wallet::create([
            'user_id' => $validated['user_id'],
            'name'    => $validated['name'],
            'balance' => $validated['balance'] ?? 0, // Default to 0 if not provided
        ]);

        // 3. Return the success response
        return response()->json([
            'message' => 'Wallet created successfully',
            'wallet'  => $wallet
        ], 201);
    }

    /**
     * Select a single wallet to view its balance and transactions (Requirement #5)
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Wallet $wallet)
    {
        // Eager load the transactions so they are automatically included in the response
        $wallet->load('transactions');

        return response()->json([
            // The 'balance' attribute is automatically included in the wallet object
            'wallet' => $wallet 
        ]);
    }
}