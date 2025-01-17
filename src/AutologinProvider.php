<?php
namespace Veneridze\Autologin;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Veneridze\Autologin\Interfaces\AuthenticationInterface;
use Veneridze\Autologin\Interfaces\AutologinInterface;

class AutologinProvider extends PackageServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-autologin')
            ->hasConfigFile('autologin')
            ->hasRoute('autologin')
            ->publishesServiceProvider('AutologinProvider')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->copyAndRegisterServiceProviderInApp();
            });
    }

    public function packageBooted(): void
    {

    }

    public function packageRegistered(): void
    {
        $this->bindAutologinInterface();
        $this->bindAuthenticationInterface();

        $this->registerAutologinProvider();
        $this->registerAutologin();
    }


    /**
     * Bind the autologin provider to the interface.
     *
     * @return void
     */
    protected function bindAutologinInterface()
    {
        $this->app->bind(AutologinInterface::class, function ($app) {
            $provider = $app['config']['autologin.autologin_provider'];

            return new $provider;
        });
    }

    /**
     * Bind the authentication provider to the interface.
     *
     * @return void
     */
    protected function bindAuthenticationInterface()
    {
        $this->app->bind(AuthenticationInterface::class, function ($app) {
            $provider = $app['config']['autologin.authentication_provider'];

            return new $provider;
        });
    }

    /**
     * Register the autologin provider in the IoC container.
     *
     * @return void
     */
    protected function registerAutologinProvider()
    {
        $this->app->singleton('autologin.provider', function ($app) {
            return $app->make(AutologinInterface::class);
        });
    }

    /**
     * Register the autologin manager with the IoC container.
     *
     * @return void
     */
    protected function registerAutologin()
    {
        $this->app->singleton('autologin', function ($app) {
            return new Autologin($app['url'], $app['autologin.provider']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            'autologin',
            'autologin.provider',
            AutologinInterface::class,
            AuthenticationInterface::class
        ];
    }
}
