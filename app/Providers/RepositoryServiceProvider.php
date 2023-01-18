<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\IncomeRepositoryInterface;
use App\Repositories\IncomeRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IncomeRepositoryInterface::class, IncomeRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
