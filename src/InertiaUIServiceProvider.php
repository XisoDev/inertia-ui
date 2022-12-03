<?php

namespace Xiso\InertiaUI;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

use Laravel\Jetstream\Jetstream;
use Xiso\InertiaUI\Console\CreateThemeCommand;
use Xiso\InertiaUI\Console\InstallCommand;

use Xiso\InertiaUI\Handlers\ThemeHandler;
use Xiso\InertiaUI\Services\InertiaManager;

class InertiaUIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . "/config/media-library.php",'media-library');
        $this->mergeConfigFrom(__DIR__ . "/config/tenancy.php",'tenancy');
        $this->mergeConfigFrom(__DIR__ . "/config/inertia-ui.php",'inertia-ui');
        $this->mergeConfigFrom(__DIR__ . "/config/jetstream.php",'jetstream');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                commands: [
                    InstallCommand::class,
                    CreateThemeCommand::class,
                ],
            );
        }
        $this->bootInertia();

        //load lang
        $this->loadTranslationsFrom(__DIR__.'/lang', 'inertia-ui');

        $this->publishes([
            __DIR__.'/lang' => $this->app->langPath('vendor/xisodev/inertia-ui'),
        ],'xisoui-lang');

        $this->publishes([
            __DIR__.'/config/tenancy.php' => config_path('tenancy.php'),
            __DIR__.'/config/inertia-ui.php' => config_path('inertia-ui.php'),
            __DIR__.'/config/media-library.php' => config_path('media-library.php'),
            __DIR__.'/config/jetstream.php' => config_path('jetstream.php'),
        ],'xisoui-config');

        //load migration
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations'),
        ], 'xisoui-migrations');
    }

    /**
     * Boot any Inertia related services.
     *
     * @return void
     */
    protected function bootInertia()
    {
        $inertiaManager = new InertiaManager();

        //jet stream의 inertia render 를 themehander 에있는 render로 바꿔치기한다.
        Jetstream::$inertiaManager = $inertiaManager;

        Fortify::loginView(function () {
            $themeHandler = new ThemeHandler();
            return $themeHandler->render('Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            $themeHandler = new ThemeHandler();
            return $themeHandler->render('Auth/ForgotPassword', [
                'status' => session('status'),
            ]);
        });

        Fortify::resetPasswordView(function (Request $request) {
            $themeHandler = new ThemeHandler();
            return $themeHandler->render('Auth/ResetPassword', [
                'email' => $request->input('email'),
                'token' => $request->route('token'),
            ]);
        });

        Fortify::registerView(function () {
            $themeHandler = new ThemeHandler();
            return $themeHandler->render('Auth/Register');
        });

        Fortify::verifyEmailView(function () {
            $themeHandler = new ThemeHandler();
            return $themeHandler->render('Auth/VerifyEmail', [
                'status' => session('status'),
            ]);
        });

        Fortify::twoFactorChallengeView(function () {
            $themeHandler = new ThemeHandler();
            return $themeHandler->render('Auth/TwoFactorChallenge');
        });

        Fortify::confirmPasswordView(function () {
            $themeHandler = new ThemeHandler();
            return $themeHandler->render('Auth/ConfirmPassword');
        });
    }
}
