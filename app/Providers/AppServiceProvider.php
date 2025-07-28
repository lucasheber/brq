<?php

declare(strict_types = 1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configModels();
    }

    private function configModels(): void
    {
        // Disable the fillable property for all models
        Model::unguard();

        // Make strict mode the default for all models
        Model::shouldBeStrict();
    }
}
