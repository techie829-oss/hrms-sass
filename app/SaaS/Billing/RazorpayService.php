<?php

namespace App\SaaS\Billing;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RazorpayService
{
    protected string $keyId;
    protected string $keySecret;
    protected string $webhookSecret;
    protected string $apiBase = 'https://api.razorpay.com/v1';

    public function __construct()
    {
        $this->keyId = config('services.razorpay.key_id') ?? '';
        $this->keySecret = config('services.razorpay.key_secret') ?? '';
        $this->webhookSecret = config('services.razorpay.webhook_secret') ?? '';
    }

    /**
     * Create a Razorpay Subscription.
     */
    public function createSubscription(string $razorpayPlanId, array $notes = [])
    {
        $response = Http::withBasicAuth($this->keyId, $this->keySecret)
            ->post("{$this->apiBase}/subscriptions", [
                'plan_id' => $razorpayPlanId,
                'total_count' => 120, // 10 years for monthly
                'quantity' => 1,
                'customer_notify' => 1,
                'notes' => $notes
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Razorpay Create Subscription Failed', [
            'plan_id' => $razorpayPlanId,
            'response' => $response->body()
        ]);

        return null;
    }

    /**
     * Create a Razorpay Order (One-Time).
     */
    public function createOrder(float $amount, string $currency = 'INR', array $notes = [])
    {
        $response = Http::withBasicAuth($this->keyId, $this->keySecret)
            ->post("{$this->apiBase}/orders", [
                'amount' => $amount * 100, // to paise
                'currency' => $currency,
                'receipt' => 'rcpt_' . uniqid(),
                'notes' => $notes
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Razorpay Create Order Failed', [
            'amount' => $amount,
            'response' => $response->body()
        ]);

        return null;
    }

    /**
     * Verify the payment signature.
     */
    public function verifySignature(string $signature, string $paymentId, ?string $orderId = null, ?string $subscriptionId = null): bool
    {
        $payload = '';
        if ($subscriptionId) {
            $payload = $paymentId . '|' . $subscriptionId;
        } elseif ($orderId) {
            $payload = $orderId . '|' . $paymentId;
        }

        if (empty($payload)) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $payload, $this->keySecret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Verify Webhook Signature.
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, $this->webhookSecret);
        return hash_equals($expectedSignature, $signature);
    }

    public function getKeyId(): string
    {
        return $this->keyId;
    }
}
