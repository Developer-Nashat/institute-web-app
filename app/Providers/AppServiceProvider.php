<?php

namespace App\Providers;

use Filament\Infolists\Infolist;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
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
        Model::unguard();

        Column::configureUsing(function (Column $column): void {
            $column
                ->searchable()
                ->sortable();
        });

        Table::$defaultNumberLocale = 'en';
        Infolist::$defaultNumberLocale = 'en';
    }
}
