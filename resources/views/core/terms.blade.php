@extends('layouts.marketing')

@section('title', 'Terms of Service | Solidrix HRMS')

@section('content')
<section class="pt-24 pb-16 bg-white flex-grow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="w-full">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Terms of Service</h1>
            <div class="prose prose-blue max-w-none text-gray-600">
                <p class="mb-4">Last updated: {{ date('F j, Y') }}</p>
        
        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">1. Agreement to Terms</h2>
        <p class="mb-4">By accessing or using the Solidrix HRMS ("Service"), a product of Solidrix Technologies, you agree to be bound by these Terms. If you disagree with any part of the terms, then you may not access the Service. Our service is designed to help businesses manage employee records, attendance, payroll, and related HR operations.</p>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">2. Subscription and Billing</h2>
        <p class="mb-4">Solidrix HRMS operates on a subscription basis. You will be billed in advance on a recurring periodic basis (e.g., monthly or annually). Fees are non-refundable unless otherwise explicitly stated in writing. We reserve the right to change our subscription fees with a 30-day notice.</p>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">3. Data Privacy and Employee Records</h2>
        <p class="mb-4">As an HRMS platform, we process sensitive employee data (including payroll, attendance, and personal details) on your behalf. You are responsible for obtaining necessary consent from your employees to store and process their data using our Service. Solidrix Technologies will never sell or share your employee data with third parties.</p>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">4. Acceptable Use</h2>
        <p class="mb-4">You agree not to use the Service for any unlawful purpose or in a way that violates any applicable local, state, national, or international law. You must not attempt to interfere with the proper functioning of the Service or unauthorized access to other tenants' data.</p>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">5. Contact Us</h2>
        <p class="mb-4">If you have any questions about these Terms, please contact us:</p>
        <ul class="list-disc pl-6 mb-4">
            <li>Email: admin@solidrix.com</li>
            <li>Address: Lakhimpur Kheri, Uttar Pradesh - 262701</li>
        </ul>
    </div>
        </div>
    </div>
</section>
@endsection
