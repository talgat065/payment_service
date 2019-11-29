<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Boot
     */
    public function boot()
    {
        $modules = config('modules.modules');
        if ($modules) {
            foreach ($modules as $module) {
                if (file_exists(__DIR__.'/'.$module.'/Routes/routes.php')) {
//                    $this->loadRoutesFrom(__DIR__.'/'.$module.'/Routes/routes.php');
                }
                if (is_dir(__DIR__.'/'.$module.'/Migrations')) {
                    $this->loadMigrationsFrom(__DIR__.'ModuleServiceProvider.php/'.$module.'/Migrations');

                }
            }
        }
    }
}
