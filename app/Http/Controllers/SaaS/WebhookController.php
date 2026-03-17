<?php

namespace App\Http\Controllers\SaaS;

use App\Http\Controllers\Controller;
use App\SaaS\Billing\Subscription;
use App\SaaS\Billing\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected RazorpayService $razorpay;

    public function __construct(RazorpayService $razorpay)
    {
        $this->razorpay = $razorpay;
    }

    public function handle(Request $request)
    {
        $signature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent();

        if (!$this->razorpay->verifyWebhookSignature($payload, $signature)) {
            Log::error('Razorpay Webhook Signature Invalid');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $data = $request->all();
        $event = $data['event'] ?? null;

        Log::info("Razorpay Webhook Received: {$event}", $data);

        switch ($event) {
            case 'subscription.charged':
                $this->handleSubscriptionCharged($data['payload']['subscription']['entity']);
                break;
                
            case 'subscription.cancelled':
                $this->handleSubscriptionCancelled($data['payload']['subscription']['entity']);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleSubscriptionCharged(array $razorpaySubscription)
    {
        $subscription = Subscription::where('razorpay_id', $razorpaySubscription['id'])->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'active',
                'ends_at' => now()->addMonth(), // Assuming monthly for now, or use end_at from entity if provided
                'meta' => array_merge($subscription->meta ?? [], ['last_webhook_payload' => $razorpaySubscription])
            ]);
            Log::info("Subscription extended for tenant: {$subscription->tenant_id}");
        }
    }

    protected function handleSubscriptionCancelled(array $razorpaySubscription)
    {
        $subscription = Subscription::where('razorpay_id', $razorpaySubscription['id'])->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);
            Log::info("Subscription cancelled for tenant: {$subscription->tenant_id}");
        }
    }
}
