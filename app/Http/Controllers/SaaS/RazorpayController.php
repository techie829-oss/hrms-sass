<?php

namespace App\Http\Controllers\SaaS;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\SaaS\Billing\Plan;
use App\SaaS\Billing\Subscription;
use App\SaaS\Billing\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RazorpayController extends Controller
{
    protected RazorpayService $razorpay;

    public function __construct(RazorpayService $razorpay)
    {
        $this->razorpay = $razorpay;
    }

    /**
     * Start the checkout process for a tenant.
     */
    public function checkout(Request $request, Tenant $tenant, Plan $plan)
    {
        if (!$plan->razorpay_plan_id) {
            return back()->with('error', 'This plan is not yet configured for online payments.');
        }

        $subscriptionData = $this->razorpay->createSubscription($plan->razorpay_plan_id, [
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'admin_id' => auth()->id(),
        ]);

        if (!$subscriptionData) {
            return back()->with('error', 'Could not initiate Razorpay subscription. Please try again.');
        }

        return view('saas.billing.checkout', [
            'tenant' => $tenant,
            'plan' => $plan,
            'razorpay_subscription_id' => $subscriptionData['id'],
            'razorpay_key_id' => $this->razorpay->getKeyId(),
        ]);
    }

    /**
     * Verify the payment from Razorpay callback.
     */
    public function verify(Request $request, Tenant $tenant, Plan $plan)
    {
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpaySubscriptionId = $request->input('razorpay_subscription_id');
        $razorpaySignature = $request->input('razorpay_signature');

        $isValid = $this->razorpay->verifySignature(
            $razorpaySignature,
            $razorpayPaymentId,
            null,
            $razorpaySubscriptionId
        );

        if (!$isValid) {
            Log::error('Razorpay Signature Verification Failed', $request->all());
            return redirect()->route('tenants.show', $tenant->id)
                ->with('error', 'Payment verification failed. Please contact support.');
        }

        // Activate the subscription
        Subscription::updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'plan_id' => $plan->id,
                'razorpay_id' => $razorpaySubscriptionId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(), // Initial activation
                'meta' => $request->all()
            ]
        );

        return redirect()->route('tenants.show', $tenant->id)
            ->with('success', 'Subscription activated successfully!');
    }
}
