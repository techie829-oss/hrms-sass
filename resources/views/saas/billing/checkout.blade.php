<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-surface-container-lowest">
        <div class="text-center space-y-4">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-primary border-t-transparent"></div>
            <h2 class="text-xl font-bold text-on-surface">Redirecting to Secure Payment...</h2>
            <p class="text-sm text-on-surface-variant">Please do not refresh the page or click back.</p>
        </div>
    </div>

    <form id="razorpay-form" action="{{ route('admin.tenants.verify', [$tenant->id, $plan->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_subscription_id" id="razorpay_subscription_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "{{ $razorpay_key_id }}",
            "subscription_id": "{{ $razorpay_subscription_id }}",
            "name": "HRMS SaaS",
            "description": "Subscription for {{ $plan->name }}",
            "image": "https://hrms.test/logo.png",
            "handler": function (response){
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_subscription_id').value = response.razorpay_subscription_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('razorpay-form').submit();
            },
            "prefill": {
                "name": "{{ $tenant->name }}",
                "email": "{{ auth()->user()->email }}",
                "contact": "{{ $tenant->contact_no }}"
            },
            "theme": {
                "color": "#6750A4"
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.on('payment.failed', function (response){
            alert("Payment Failed: " + response.error.description);
            window.location.href = "{{ route('admin.tenants.show', $tenant->id) }}";
        });
        
        window.onload = function() {
            rzp1.open();
        };
    </script>
</x-app-layout>
