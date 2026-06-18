<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        // Define all static marketing routes to include in the sitemap
        $routes = [
            'central.home',
            'central.pricing',
            'central.privacy',
            'central.terms',
            'central.features',
            'central.about',
            'central.contact',
            'central.faqs',
            'central.cookie-policy',
            'features.hrms',
            'features.employee',
            'features.attendance',
            'features.leave',
            'features.payroll',
            'features.recruitment',
            'features.performance',
            'features.operations',
            'features.employee-self-service',
            'usecase.small-business',
            'usecase.startups',
            'usecase.manufacturing',
            'usecase.it-companies',
            'usecase.retail-stores'
        ];

        $urls = [];
        foreach ($routes as $route) {
            if (\Route::has($route)) {
                $urls[] = [
                    'loc' => route($route),
                    'lastmod' => now()->startOfDay()->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => $route === 'central.home' ? '1.0' : '0.8',
                ];
            }
        }

        return response()->view('sitemap', compact('urls'))->header('Content-Type', 'text/xml');
    }
}
