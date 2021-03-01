<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $models = [
            'User',
            'Otp',
            'Skill',
            'Post',
            'Job',
            'News',
            'Province',
            'Like'
        ];

        foreach ($models as $model) {
            $this->app->bind("App\Interfaces\\{$model}RepositoryInterface", "App\Repositories\\{$model}Repository");
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Collection::macro('paginate', function ($total = null, $perPage = 15, $page = null, $pageName = 'page') {
            $page = $page ?: Paginator::resolveCurrentPage($pageName);
            $total = $total ?: $this->count();
            $items = $total ? $this->forPage($page, $perPage) : $this;

            return new LengthAwarePaginator(array_values($items->toArray()), $total, $perPage, $page, [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]);
        });
        User::observe(UserObserver::class);
    }
}
