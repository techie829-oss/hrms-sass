@extends('layouts.marketing')

@section('title', 'Pricing | Transparent HRMS Software Costs | Solidrix HRMS')
@section('description', 'Simple, predictable per-employee pricing. Pay only for the HR modules you need with Solidrix HRMS. No hidden implementation fees.')
@section('content')
<section class="pt-24 pb-16 bg-white flex-grow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">Simple, predictable pricing</h1>
        <p class="text-xl text-gray-600 mb-16">
            Pay for the modules you need. Transparent per-employee pricing with no hidden implementation fees.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-left">
            @foreach($plans as $plan)
            <div class="bg-{{ $plan->slug === 'professional' ? 'gray-900' : 'white' }} rounded-2xl border border-{{ $plan->slug === 'professional' ? 'gray-800' : 'gray-200' }} p-8 shadow-{{ $plan->slug === 'professional' ? 'xl' : 'sm' }} flex flex-col relative {{ $plan->slug === 'professional' ? 'transform md:-translate-y-4' : '' }}">
                @if($plan->slug === 'professional')
                <div class="absolute top-0 right-8 transform -translate-y-1/2">
                    <span class="bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Most Popular</span>
                </div>
                @endif
                <h3 class="text-xl font-bold text-{{ $plan->slug === 'professional' ? 'white' : 'gray-900' }} mb-2">{{ $plan->name }}</h3>
                <p class="text-{{ $plan->slug === 'professional' ? 'gray-400' : 'gray-500' }} text-sm mb-6">{{ $plan->description ?? 'The essential tools for your business.' }}</p>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-{{ $plan->slug === 'professional' ? 'white' : 'gray-900' }}">{{ config('billing.currency_symbol') }}{{ number_format($plan->price_monthly, 0) }}</span>
                    <span class="text-{{ $plan->slug === 'professional' ? 'gray-400' : 'gray-500' }}">/month</span>
                </div>
                <ul class="space-y-4 mb-8 flex-1 text-sm text-{{ $plan->slug === 'professional' ? 'gray-300' : 'gray-600' }}">
                    @if(is_array($plan->features))
                        @foreach($plan->features as $feature)
                        <li class="flex items-center"><svg class="w-5 h-5 text-{{ $plan->slug === 'professional' ? 'indigo-400' : 'green-500' }} mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> {{ $feature }}</li>
                        @endforeach
                    @endif
                </ul>
                <a href="/contact" class="block w-full py-3 px-4 {{ $plan->slug === 'professional' ? 'bg-indigo-600 hover:bg-indigo-500 text-white border-transparent' : 'bg-gray-50 hover:bg-gray-100 text-gray-900 border-gray-200' }} border text-center font-medium rounded-lg transition">
                    {{ $plan->slug === 'professional' ? 'Book a Demo' : ($plan->slug === 'enterprise' ? 'Contact Sales' : 'Start Free Trial') }}
                </a>
            </div>
            @endforeach

        </div>
    </div>
</section>
@endsection
