<?php

namespace App\Providers;

use App\Mail\Transport\BrevoApiTransport;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
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
        Gate::policy(User::class, UserPolicy::class);

        $this->configureRateLimiting();
        $this->configureMailTransports();
    }

    /**
     * Configure custom mail transports.
     */
    protected function configureMailTransports(): void
    {
        Mail::extend('brevo', function (array $config) {
            $apiKey = $config['api_key'] ?? config('services.brevo.api_key');

            if (empty($apiKey)) {
                throw new \RuntimeException(
                    'Brevo API key is not configured. Please set BREVO_API_KEY in your .env file.'
                );
            }

            return new BrevoApiTransport(apiKey: $apiKey);
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
