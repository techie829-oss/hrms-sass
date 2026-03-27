<?php

namespace App\Providers;

use App\SaaS\Modules\ModuleManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Singletons
        $this->app->singleton(ModuleManager::class, function ($app) {
            return new ModuleManager;
        });

        $this->registerModuleProviders();
    }

    /**
     * Auto-discover and register module providers.
     */
    protected function registerModuleProviders(): void
    {
        $modulePath = app_path('Modules');
        if (! File::isDirectory($modulePath)) {
            return;
        }

        $modules = File::directories($modulePath);
        foreach ($modules as $module) {
            $name = basename($module);
            $providerPath = $module.'/Providers';

            if (File::isDirectory($providerPath)) {
                $providers = File::files($providerPath);
                foreach ($providers as $provider) {
                    $className = 'App\\Modules\\'.$name.'\\Providers\\'.str_replace('.php', '', $provider->getFilename());
                    if (class_exists($className)) {
                        $this->app->register($className);
                    }
                }
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->bootModules();
    }

    /**
     * Auto-discover and boot modules.
     */
    protected function bootModules(): void
    {
        $modulePath = app_path('Modules');

        if (! File::isDirectory($modulePath)) {
            return;
        }

        $modules = File::directories($modulePath);

        foreach ($modules as $module) {
            $name = basename($module);
            $this->registerModuleViews($name, $module);
            $this->registerModuleMigrations($name, $module);
            $this->registerModuleRoutes($name, $module);
        }
    }

    /**
     * Register routes for the module.
     */
    protected function registerModuleRoutes(string $name, string $path): void
    {
        $routeFile = $path.'/routes.php';
        $centralHost = parse_url(config('app.url'), PHP_URL_HOST) ?? 'hrms.test';

        if (File::exists($routeFile)) {
            // Register module routes ONLY on tenant domains
            Route::domain('{tenant}.'.$centralHost)
                ->middleware(['web', 'tenant.active'])
                ->prefix(strtolower($name))
                ->group($routeFile);
        }
    }

    /**
     * Register views for the module.
     */
    protected function registerModuleViews(string $name, string $path): void
    {
        $viewPath = $path.'/resources/views';

        if (File::isDirectory($viewPath)) {
            $this->loadViewsFrom($viewPath, strtolower($name));
        }
    }

    /**
     * Get migration paths for all modules.
     */
    public static function getModuleMigrationPaths(): array
    {
        $modulePath = base_path('app/Modules');
        $paths = [];

        if (is_dir($modulePath)) {
            $modules = glob($modulePath . '/*', GLOB_ONLYDIR);
            foreach ($modules as $module) {
                $migrationPath = $module.'/database/migrations';
                if (is_dir($migrationPath)) {
                    $paths[] = $migrationPath;
                }
            }
        }

        return $paths;
    }

    protected function registerModuleMigrations(string $name, string $path): void
    {
        // NOOP: We only want to run module migrations within the tenant context.
        // If we call $this->loadMigrationsFrom($path.'/database/migrations'),
        // they get registered for the central 'migrate' command, which is WRONG for multi-tenancy.
    }
}
