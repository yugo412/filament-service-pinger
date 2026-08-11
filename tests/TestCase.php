<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use Yugo\FilamentServicePinger\Support\JobResolver;
use Yugo\FilamentServicePinger\Support\ModelResolver;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected string $serviceModelResolver;

    protected string $serviceCheckModelResolver;

    protected string $pingJobResolver;

    protected function getPackageProviders($app)
    {
        // Order matters: all Filament providers must register BEFORE Livewire. Livewire's
        // mechanism registration resolves `Livewire\Mechanisms\DataStore` and binds it as a
        // singleton instance; if Filament\Support's `DataStoreOverride` binding runs after that,
        // it drops the instance and every resolution returns a new store (breaking error bags).
        return [
            \BladeUI\Heroicons\BladeHeroiconsServiceProvider::class,
            \BladeUI\Icons\BladeIconsServiceProvider::class,
            \RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider::class,
            \Filament\Support\SupportServiceProvider::class,
            \Filament\Schemas\SchemasServiceProvider::class,
            \Filament\Forms\FormsServiceProvider::class,
            \Filament\Infolists\InfolistsServiceProvider::class,
            \Filament\Notifications\NotificationsServiceProvider::class,
            \Filament\Actions\ActionsServiceProvider::class,
            \Filament\Tables\TablesServiceProvider::class,
            \Filament\Widgets\WidgetsServiceProvider::class,
            \Filament\QueryBuilder\QueryBuilderServiceProvider::class,
            \Filament\FilamentServiceProvider::class,
            \Livewire\LivewireServiceProvider::class,
            \Yugo\FilamentServicePinger\Provider::class,
            \Tests\Providers\Filament\AdminPanelProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        // Model resolver
        $app['config']->set('service-pinger.models.service', \Tests\Fixtures\Models\Service::class);
        $app['config']->set('service-pinger.models.check', \Tests\Fixtures\Models\ServiceCheck::class);

        // Encryption key required by the Filament panel middleware (EncryptCookies)
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));

        // Database (sqlite in-memory)
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceModelResolver = ModelResolver::service();
        $this->serviceCheckModelResolver = ModelResolver::check();

        $this->pingJobResolver = JobResolver::ping();
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
