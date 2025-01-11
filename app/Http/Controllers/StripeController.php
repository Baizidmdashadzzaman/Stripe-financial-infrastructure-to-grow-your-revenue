<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Token;
use Stripe\Customer;
use Stripe\PaymentIntent;

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
                    'account_number' => 'DK1566951012331920',
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

    //Attach Bank Account to a Customer or Account
    public function attachBankAccount(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|string',
            'bank_account_token' => 'required|string',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Attach the bank account to the customer
            $customer = \Stripe\Customer::retrieve($request->customer_id);
            $customer->sources->create(['source' => $request->bank_account_token]);

            return response()->json([
                'success' => true,
                'message' => 'Bank account attached to customer successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    // Verify Bank Account Using Micro-Deposits
    public function verifyBankAccount(Request $request)
    {
        try {
            // Assuming $customerId is the Stripe customer ID and $sourceId is the bank account source ID
            $verification = \Stripe\Source::verify($customerId, $sourceId, [
                'amounts' => [32, 45], // Replace with actual micro-deposit amounts in cents
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank account verified successfully',
                'verification' => $verification,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Attach bank account to a customer.
     */
    public function attachBankAccount_2nd(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Create a customer and attach the bank account token
            $customer = Customer::create([
                'email' => $request->email,
                'source' => $request->token,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank account attached to customer',
                'customer' => $customer,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Create a payment intent.
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1', // Amount in the smallest currency unit (e.g., cents)
            'currency' => 'required|string',     // Currency code (e.g., "usd")
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount,   // Amount in cents (e.g., $10 = 1000)
                'currency' => $request->currency,
                'payment_method_types' => ['card'], // Supported payment methods
            ]);

            return response()->json([
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }


    /**
     * Confirm a payment intent.
     */

    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);
            $paymentIntent->confirm();

            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'payment_intent' => $paymentIntent,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }


    /**
     * Create a PaymentIntent with a US bank account.
     */
    public function createAchPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1', // Amount in cents
            'currency' => 'required|string',     // Currency code (e.g., "usd")
            'account_number' => 'required|string',
            'routing_number' => 'required|string',
            'account_holder_name' => 'required|string',
            'account_holder_type' => 'required|in:individual,company',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Create a PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount,
                'currency' => $request->currency,
                'payment_method_types' => ['us_bank_account'],
                'payment_method_data' => [
                    'type' => 'us_bank_account',
                    'us_bank_account' => [
                        'account_number' => $request->account_number,
                        'routing_number' => $request->routing_number,
                        'account_holder_name' => $request->account_holder_name,
                        'account_holder_type' => $request->account_holder_type,
                    ],
                ],
                'confirmation_method' => 'automatic',
                'confirm' => true,
            ]);

            return response()->json([
                'success' => true,
                'payment_intent' => $paymentIntent,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }



}
