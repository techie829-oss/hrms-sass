@extends('layouts.marketing')

@section('title', 'Cookie Policy | SolidrixHR')
@section('description', 'Information about how we use cookies and similar technologies.')

@section('content')
<div class="bg-vibrant-hero py-20 lg:py-32 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-6">
                Cookie <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Policy</span>
            </h1>
            <p class="text-xl text-slate-600 mb-10">
                Last updated: {{ date('F j, Y') }}
            </p>
        </div>
    </div>
</div>

<div class="py-20 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-slate lg:prose-lg">
        <p>
            This Cookie Policy explains how SolidrixHR ("we", "us", or "our") uses cookies and similar technologies to recognize you when you visit our website. It explains what these technologies are and why we use them, as well as your rights to control our use of them.
        </p>

        <h3>What are cookies?</h3>
        <p>
            Cookies are small data files that are placed on your computer or mobile device when you visit a website. Cookies are widely used by website owners in order to make their websites work, or to work more efficiently, as well as to provide reporting information.
        </p>

        <h3>Why do we use cookies?</h3>
        <p>
            We use first-party and third-party cookies for several reasons. Some cookies are required for technical reasons in order for our website to operate, and we refer to these as "essential" or "strictly necessary" cookies. Other cookies also enable us to track and target the interests of our users to enhance the experience on our online properties. Third parties serve cookies through our website for advertising, analytics, and other purposes.
        </p>

        <h3>How can I control cookies?</h3>
        <p>
            You have the right to decide whether to accept or reject cookies. You can exercise your cookie rights by setting your preferences in the Cookie Consent Manager. The Cookie Consent Manager allows you to select which categories of cookies you accept or reject. Essential cookies cannot be rejected as they are strictly necessary to provide you with services.
        </p>

        <p>
            If you have any questions about our use of cookies or other technologies, please email us at support@sklops.com.
        </p>
    </div>
</div>
@endsection
