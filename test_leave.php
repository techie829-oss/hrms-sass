<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);
// Simulate a request to the correct tenant domain
$request = Illuminate\Http\Request::create('http://rkservices.hrms.test/leave/requests/create', 'GET');
$response = $kernel->handle($request);

echo "Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() >= 400) {
    if (method_exists($response, 'exception') && $response->exception) {
        echo "Exception: " . $response->exception->getMessage() . "\n";
    } else {
        echo "Response: " . substr($response->getContent(), 0, 1000) . "\n";
    }
}
