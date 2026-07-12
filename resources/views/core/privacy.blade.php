@extends('layouts.marketing')

@section('title', 'Privacy Policy | SolidrixHR')

@section('content')
<section class="pt-24 pb-16 bg-white flex-grow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="w-full">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Privacy Policy</h1>
            <div class="prose prose-blue max-w-none text-gray-600">
                <p class="mb-4">Last updated: {{ date('F j, Y') }}</p>
        
        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">1. Introduction</h2>
        <p class="mb-4">Welcome to SolidrixHR. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you as to how we look after your personal data when you visit our website and tell you about your privacy rights and how the law protects you.</p>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">2. The data we collect about you</h2>
        <p class="mb-4">Personal data, or personal information, means any information about an individual from which that person can be identified. We may collect, use, store and transfer different kinds of personal data about you which we have grouped together as follows:</p>
        <ul class="list-disc pl-6 mb-4">
            <li><strong>Identity Data</strong> includes first name, last name, username or similar identifier.</li>
            <li><strong>Contact Data</strong> includes billing address, delivery address, email address and telephone numbers.</li>
            <li><strong>Financial Data</strong> includes bank account and payment card details.</li>
            <li><strong>Transaction Data</strong> includes details about payments to and from you and other details of products and services you have purchased from us.</li>
        </ul>

        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">3. Contact Details</h2>
        <p class="mb-4">If you have any questions about this privacy policy or our privacy practices, please contact us in the following ways:</p>
        <ul class="list-disc pl-6 mb-4">
            <li>Email address: support@sklops.com</li>
            <li>Phone number: +91 98116 55457</li>
        </ul>
    </div>
        </div>
    </div>
</section>
@endsection
