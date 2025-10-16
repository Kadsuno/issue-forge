<?php

namespace App\Providers;

use App\Mail\Transport\BrevoApiTransport;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use App\Models\WorkflowState;
use App\Observers\TicketObserver;
use App\Policies\ProjectPolicy;
use App\Policies\TicketPolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkflowStatePolicy;
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
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Ticket::class, TicketPolicy::class);
        Gate::policy(WorkflowState::class, WorkflowStatePolicy::class);

        // Register observers
        Ticket::observe(TicketObserver::class);

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
