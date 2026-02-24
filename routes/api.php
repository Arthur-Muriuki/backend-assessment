<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

// User Routes
Route::post('/users', [UserController::class, 'store']); // Create User
Route::get('/users/{user}', [UserController::class, 'show']); // View Profile (Wallets & Balances)

// route for the profile endpoint
Route::get('/users/{user}', [UserController::class, 'show']);

// Wallet Routes
Route::post('/wallets', [WalletController::class, 'store']); // Create Wallet
Route::get('/wallets/{wallet}', [WalletController::class, 'show']); // View Single Wallet & Transactions

// Transaction Routes
Route::post('/wallets/{wallet}/transactions', [TransactionController::class, 'store']); // Add Transaction
Route::post('/transactions', [TransactionController::class, 'store']);