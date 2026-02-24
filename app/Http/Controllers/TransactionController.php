<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Add transactions to a wallet (Income/Expense) and calculate balances (Requirement #3)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // 1. Validate incoming transaction data ensuring valid types and positive amounts
        $validated = $request->validate([
            'wallet_id'   => 'required|exists:wallets,id',
            'type'        => 'required|in:income,expense',
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        // 2. Use a database transaction to ensure data integrity
        // If either the transaction creation or wallet update fails, everything rolls back
        return DB::transaction(function () use ($validated) {
            
            // Retrieve the wallet we are adding the transaction to
            $wallet = Wallet::findOrFail($validated['wallet_id']);

            // Create the transaction record
            $transaction = Transaction::create($validated);

            // 3. Ensure balances are calculated correctly based on the transaction type
            if ($validated['type'] === 'income') {
                $wallet->balance += $validated['amount']; // Income adds to the balance
            } else {
                $wallet->balance -= $validated['amount']; // Expense subtracts from the balance
            }
            
            // Save the newly calculated balance to the database
            $wallet->save();

            // 4. Return a success response including the newly updated state
            return response()->json([
                'message'            => 'Transaction recorded successfully!',
                'transaction'        => $transaction,
                'new_wallet_balance' => $wallet->balance
            ], 201);
        });
    }
}