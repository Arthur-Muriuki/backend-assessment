<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Create one or more wallets for a user (Requirement #2)
     */
    public function store(Request $request)
    {
        // Validate the request ensures the user exists and the wallet has a name
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
        ]);

        $wallet = Wallet::create($validated);

        return response()->json([
            'message' => 'Wallet created successfully',
            'wallet' => $wallet
        ], 201);
    }

    /**
     * Select a single wallet to view its balance and transactions (Requirement #5)
     */
    public function show(Wallet $wallet)
    {
        // Eager load the transactions so they are included in the response
        $wallet->load('transactions');

        return response()->json([
            // The 'balance' attribute is automatically included here
            'wallet' => $wallet 
        ]);
    }
}