<?php

declare(strict_types = 1);

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Transaction\TransactionRepositoryInterface::class,
            \App\Repositories\Transaction\TransactionRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configModels();
        $this->configObservers();
        $this->configureScramble();
    }

    private function configModels(): void
    {
        // Disable the fillable property for all models
        Model::unguard();

        // Make strict mode the default for all models
        Model::shouldBeStrict();
    }

    private function configObservers(): void
    {
        // Register the TransactionObserver
        \App\Models\Transaction::observe(\App\Observers\TransactionObserver::class);
    }

    private function configureScramble(): void
    {
       Scramble::configure()
        ->withDocumentTransformers(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });
    }
}
