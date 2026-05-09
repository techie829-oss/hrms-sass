<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SaaS\Modules\ModuleManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ModuleController extends Controller
{
    public function __construct(protected ModuleManager $moduleManager) {}

    /**
     * Display all system modules.
     */
    public function index()
    {
        $availableModules = $this->moduleManager->getAvailableModules();
        $dbModules = DB::table('modules')->get()->keyBy('slug');

        return view('admin.modules.index', compact('availableModules', 'dbModules'));
    }

    /**
     * Sync discovered modules with the database.
     */
    public function sync()
    {
        $modules = $this->moduleManager->getAvailableModules();

        foreach ($modules as $slug => $module) {
            DB::table('modules')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name' => $module['name'],
                    'is_free' => $module['free'],
                    'updated_at' => now(),
                ]
            );
        }

        return back()->with('success', 'Modules synced with filesystem successfully.');
    }

    /**
     * Display a specific module's details.
     */
    public function show($slug)
    {
        $availableModules = $this->moduleManager->getAvailableModules();
        
        if (!isset($availableModules[$slug])) {
            abort(404, 'Module not found.');
        }
        
        $module = $availableModules[$slug];
        $dbModule = DB::table('modules')->where('slug', $slug)->first();
        
        $modulePath = $module['path'];
        
        // Scan Controllers
        $controllersPath = $modulePath . '/Controllers';
        $controllers = [];
        if (File::isDirectory($controllersPath)) {
            $controllers = collect(File::allFiles($controllersPath))
                ->map(fn($file) => $file->getFilename())
                ->toArray();
        }

        // Scan Models
        $modelsPath = $modulePath . '/Models';
        $models = [];
        if (File::isDirectory($modelsPath)) {
            $models = collect(File::allFiles($modelsPath))
                ->map(fn($file) => $file->getFilename())
                ->toArray();
        }

        // Scan Migrations
        $migrationsPath = $modulePath . '/database/migrations';
        $migrations = [];
        if (File::isDirectory($migrationsPath)) {
            $migrations = collect(File::allFiles($migrationsPath))
                ->map(fn($file) => $file->getFilename())
                ->toArray();
        }

        // Scan Policies
        $policiesPath = $modulePath . '/Policies';
        $policies = [];
        if (File::isDirectory($policiesPath)) {
            $policies = collect(File::allFiles($policiesPath))
                ->map(fn($file) => $file->getFilename())
                ->toArray();
        }

        // Scan Views
        $viewsPath = $modulePath . '/resources/views';
        $views = [];
        if (File::isDirectory($viewsPath)) {
            $views = collect(File::allFiles($viewsPath))
                ->map(fn($file) => $file->getRelativePathname())
                ->toArray();
        }

        // Find which tenants have this module enabled
        $activeTenants = DB::table('tenant_modules')
            ->join('tenants', 'tenant_modules.tenant_id', '=', 'tenants.id')
            ->where('tenant_modules.module_id', $dbModule?->id)
            ->where('tenant_modules.enabled', true)
            ->select('tenants.id', 'tenants.name', 'tenants.domain', 'tenant_modules.installed_at')
            ->get();

        return view('admin.modules.show', compact(
            'module', 'dbModule', 'activeTenants', 'slug',
            'controllers', 'models', 'migrations', 'policies', 'views'
        ));
    }
}
