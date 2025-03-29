<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
    */
    public function register(): void
    {
        $this->bindRepositories();
    }

    private function bindRepositories(): void
    {
        $repositoriesPath   = app_path('Repositories');
        $interfacesPath     = app_path('Repositories/Interfaces');

        $repositoryFiles = File::files($repositoriesPath);

        foreach ($repositoryFiles as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);

            $interfaceName = $filename . 'Interface';

            $interfacePath = $interfacesPath . DIRECTORY_SEPARATOR . $interfaceName . '.php';

            if (File::exists($interfacePath)) {
                $interface  = 'App\Repositories\Interfaces\\' . $interfaceName;
                $repository = 'App\Repositories\\' . $filename;

                $this->app->bind($interface, $repository);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
