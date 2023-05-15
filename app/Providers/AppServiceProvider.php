<?php

namespace App\Providers;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Navigation\NavigationGroup;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Produk')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Penjualan')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Kepegawaian')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Users')
                    ->collapsed(),
            ]);
        });
    }
}
