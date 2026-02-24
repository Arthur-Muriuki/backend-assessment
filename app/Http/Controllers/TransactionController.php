<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Add a transaction (income or expense) to a wallet (Requirement #3)
     */
    public function store(Request $request, Wallet $wallet)
    {
        // Strict validation based on technical requirements
        $validated = $request->validate([
            'type' => 'required|in:income,expense', // Must be exactly one of these two
            'amount' => 'required|numeric|gt:0',    // 'gt:0' ensures it is strictly positive
        ]);

        // Create the transaction tied directly to the wallet from the URL
        $transaction = $wallet->transactions()->create([
            'type' => $validated['type'],
            'amount' => $validated['amount'],
        ]);

        return response()->json([
            'message' => 'Transaction added successfully',
            'transaction' => $transaction,
            // We can even return the updated wallet balance for convenience!
            'new_wallet_balance' => $wallet->balance 
        ], 201);
    }
}