<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Token;


class StripeController extends Controller
{

    public function validateBankAccount(Request $request)
    {
        // $request->validate([
        //     'country' => 'required|string',
        //     'currency' => 'required|string',
        //     'account_number' => 'required|string',
        //     'routing_number' => 'required|string',
        //     'account_holder_name' => 'required|string',
        //     'account_holder_type' => 'required|in:individual,company',
        // ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Create a bank account token
            $token = Token::create([
                'bank_account' => [
                    'country' => 'DK',
                    'currency' => 'dkk',
                    'account_number' => '',
                    'routing_number' => 'LUNADK22',
                    'account_holder_name' => 'VR Byen Aps',
                    'account_holder_type' => 'individual',
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank account token created successfully',
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
