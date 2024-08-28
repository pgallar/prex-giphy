<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider {

    public function boot() {}

    public function register(): void
    {
        $models = array(
            'UserRepository',
        );

        foreach ($models as $model) {
            $this->app->bind("App\Domain\Contracts\\{$model}Contract", "App\Infrastructure\Adapter\Out\Repositories\\{$model}");
        }
    }
}
