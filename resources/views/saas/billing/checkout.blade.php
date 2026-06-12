<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-slate-50">
        <div class="card bg-white border border-slate-200 rounded-xl shadow-sm p-10 max-w-md w-full text-center space-y-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 mb-2">
                <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Redirecting to Secure Payment</h2>
                <p class="text-sm text-slate-500 mt-2">Please do not refresh the page or click back.</p>
            </div>
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
                "color": "#4f46e5"
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
