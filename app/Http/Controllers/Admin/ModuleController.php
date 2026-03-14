<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SaaS\Modules\ModuleManager;
use Illuminate\Support\Facades\DB;

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
}
