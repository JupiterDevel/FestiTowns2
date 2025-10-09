<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Festivity;
use App\Models\Locality;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Policies\FestivityPolicy;
use App\Policies\LocalityPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Locality::class => LocalityPolicy::class,
        Festivity::class => FestivityPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

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
        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
