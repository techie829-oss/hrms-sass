@extends('layouts.marketing')

@section('title', 'Frequently Asked Questions | SolidrixHR')
@section('description', 'Find answers to common questions about SolidrixHR features, pricing, setup, and more.')

@section('content')
<div class="bg-vibrant-hero py-20 lg:py-32 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-6">
                Frequently Asked <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Questions</span>
            </h1>
            <p class="text-xl text-slate-600 mb-10">
                Everything you need to know about the product and billing. Can't find the answer you're looking for? Please chat to our friendly team.
            </p>
        </div>
    </div>
</div>

<div class="py-20 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-8">
            <!-- FAQ Item 1 -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 mb-2">Is there a free trial available?</h3>
                <p class="text-slate-600">Yes, you can try SolidrixHR for free for 14 days. If you want, we’ll provide you with a free, personalized 30-minute onboarding call to get you up and running as soon as possible.</p>
            </div>
            
            <!-- FAQ Item 2 -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 mb-2">Can I change my plan later?</h3>
                <p class="text-slate-600">Of course. Our pricing scales with your company. Chat to our friendly team to find a solution that works for you.</p>
            </div>
            
            <!-- FAQ Item 3 -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 mb-2">What is your cancellation policy?</h3>
                <p class="text-slate-600">We understand that things change. You can cancel your plan at any time and we’ll refund you the difference already paid.</p>
            </div>
            
            <!-- FAQ Item 4 -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 mb-2">Can other info be added to an invoice?</h3>
                <p class="text-slate-600">At the moment, the only way to add additional information to invoices is to add the information to the workspace's name manually.</p>
            </div>
            
            <!-- FAQ Item 5 -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 mb-2">How does billing work?</h3>
                <p class="text-slate-600">Plans are per workspace, not per account. You can upgrade one workspace, and still have any number of free workspaces.</p>
            </div>
        </div>
    </div>
</div>
@endsection
