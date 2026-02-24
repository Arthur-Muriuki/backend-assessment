<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        // We use a database transaction to ensure both operations succeed or fail together
        return DB::transaction(function () use ($validated) {
            
            // 2. Find the wallet
            $wallet = Wallet::findOrFail($validated['wallet_id']);

            // 3. Create the transaction record
            $transaction = Transaction::create($validated);

            // 4. Update the wallet balance
            if ($validated['type'] === 'income') {
                $wallet->balance += $validated['amount'];
            } else {
                $wallet->balance -= $validated['amount'];
            }
            $wallet->save();

            // 5. Return the response
            return response()->json([
                'message' => 'Transaction recorded successfully!',
                'transaction' => $transaction,
                'new_wallet_balance' => $wallet->balance
            ], 201);
        });
    }
}