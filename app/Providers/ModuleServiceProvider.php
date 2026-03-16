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
        }
    }

    /**
     * Register routes for the module.
     */
    protected function registerModuleRoutes(string $name, string $path): void
    {
        $routeFile = $path.'/routes.php';

        if (File::exists($routeFile)) {
            Route::middleware('web')
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
}
