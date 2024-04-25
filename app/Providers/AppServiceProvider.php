<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        VarDumper::setHandler(function ($var) {
            $cloner = new VarCloner();
            $cloner->setMaxItems(-1); // Specifying -1 removes the limit
            $dumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();

            $dumper->dump($cloner->cloneVar($var));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (!$this->app->environment('local')) {
        //            \URL::forceScheme('https');
        //        }
        Model::shouldBeStrict(!app()->isProduction());
    }
}
