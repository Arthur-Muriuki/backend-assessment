# Money Tracker API

A RESTful API built with Laravel that allows users to track their finances across multiple wallets. Users can log income and expenses, and the API automatically calculates and updates their wallet balances.

## Features (Functional Requirements)
* **User Management**: Create user accounts without needing authentication.
* **Wallet Management**: Create multiple wallets for a single user (e.g., "Main Checking", "Savings").
* **Transaction Tracking**: Log `income` and `expense` transactions for specific wallets.
* **Dynamic Balances**: Wallet balances update automatically based on transaction history using database transactions for data integrity.
* **Profile Viewing**: View a user's profile, including all of their wallets, individual wallet balances, and a dynamically calculated total balance.

## Prerequisites
To run this project, you will need:
* PHP 8.1+
* Composer
* MySQL or SQLite (configured in your `.env` file)
* Postman (or any API client for testing)

## Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone <your-repository-url-here>
   cd money-tracker-api

2. Install dependencies:
composer install

3. Set up the environment file:
cp .env.example .env


Note: Open the .env file and configure your database credentials (e.g., DB_CONNECTION, DB_DATABASE).

5. Generate the application key:
php artisan key:generate

6. Run the database migrations:
php artisan migrate

7. Start the local development server:
php artisan serve
The API will be available at http://127.0.0.1:8000.

## API Documentation


Note: Add Accept: application/json and Content-Type: 

application/json to your headers when testing these endpoints.

1. Create a User

   
Endpoint: POST /api/users

JSON


// Request Body


{
    "name": "Arthur Muriuki",
    "email": "arthurmuriuki@example.com",
    "password": "password123"
}

2. View User Profile & Total Balance

   
Endpoint: GET /api/users/{id}


Returns the user, all associated wallets, transaction history for those wallets, and the total calculated balance.


4. Create a Wallet

   
Endpoint: POST /api/wallets


JSON


// Request Body
{
    "user_id": 1,
    "name": "Main Checking Account",
    "balance": 500.00 
}

5. View a Single Wallet

   
Endpoint: GET /api/wallets/{id}


Returns the wallet details, current balance, and a list of all its transactions.


6. Add a Transaction

   
Endpoint: POST /api/transactions


Automatically updates the associated wallet's balance.


JSON


// Request Body


//Must be "income" or "expense" 


{
    "wallet_id": 1,
    "type": "expense", 
    "amount": 50.00,
    "description": "Groceries"
}

## Resetting the Database

If you want to clear all data and start fresh while testing the API, you can wipe the database and re-run the migrations using this Artisan command:

\`\`\`
php artisan migrate:fresh
\`\`\`

*Note: This will delete all users, wallets, and transactions.*
