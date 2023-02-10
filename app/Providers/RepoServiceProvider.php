<?php

namespace App\Providers;

use App\Repository\CompanyRepository;
use Illuminate\Support\ServiceProvider;
use App\Repository\CompanyRepositryInterface;

class RepoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CompanyRepositryInterface::class,CompanyRepository::class);

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
