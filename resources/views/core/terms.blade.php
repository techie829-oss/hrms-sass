@extends('layouts.marketing')

@section('title', 'Terms of Service | SolidrixHR')

@section('content')
<section class="pt-24 pb-16 bg-white flex-grow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="w-full">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Terms of Service</h1>
            <div class="prose prose-blue max-w-none text-gray-600">
                <p class="mb-4">Last updated: {{ date('F j, Y') }}</p>
        
        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">1. Agreement to Terms</h2>
        <p class="mb-4">By accessing or using our Services, you agree to be bound by these Terms. If you disagree with any part of the terms, then you may not access the Service.</p>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">2. Subscriptions</h2>
        <p class="mb-4">Some parts of the Service are billed on a subscription basis. You will be billed in advance on a recurring and periodic basis (such as daily, weekly, monthly or annually).</p>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">3. Content</h2>
        <p class="mb-4">Our Service allows you to post, link, store, share and otherwise make available certain information, text, graphics, videos, or other material. You are responsible for the Content that you post to the Service, including its legality, reliability, and appropriateness.</p>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">4. Contact Us</h2>
        <p class="mb-4">If you have any questions about these Terms, please contact us:</p>
        <ul class="list-disc pl-6 mb-4">
            <li>Email: support@sklops.com</li>
            <li>Phone: +91 98116 55457</li>
        </ul>
    </div>
        </div>
    </div>
</section>
@endsection
