<?php

namespace Xiso\InertiaUI;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Xiso\InertiaUI\Console\InstallCommand;
use Xiso\InertiaUI\Handlers\ThemeHandler;

class InertiaUIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
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
                ],
            );
        }
        $this->bootInertia();

        //register hook
        add_action('siteMenu', function($user) {
            $user->sendWelcomeMail();
        }, 20, 1);

        //load lang
        $this->loadTranslationsFrom(__DIR__.'/lang', 'inertia-ui');
        $this->publishes([
            __DIR__.'/lang' => $this->app->langPath('vendor/xisodev/inertia-ui'),
        ],'lang');
    }

    /**
     * Boot any Inertia related services.
     *
     * @return void
     */
    protected function bootInertia()
    {
        $themeHandler = new ThemeHandler();

        Fortify::loginView(function () use ($themeHandler){
            return $themeHandler->render('Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        });

        Fortify::requestPasswordResetLinkView(function () use ($themeHandler){
            return $themeHandler->render('Auth/ForgotPassword', [
                'status' => session('status'),
            ]);
        });

        Fortify::resetPasswordView(function (Request $request) use ($themeHandler){
            return $themeHandler->render('Auth/ResetPassword', [
                'email' => $request->input('email'),
                'token' => $request->route('token'),
            ]);
        });

        Fortify::registerView(function () use ($themeHandler){
            return $themeHandler->render('Auth/Register');
        });

        Fortify::verifyEmailView(function () use ($themeHandler){
            return $themeHandler->render('Auth/VerifyEmail', [
                'status' => session('status'),
            ]);
        });

        Fortify::twoFactorChallengeView(function () use ($themeHandler){
            return $themeHandler->render('Auth/TwoFactorChallenge');
        });

        Fortify::confirmPasswordView(function () use ($themeHandler){
            return $themeHandler->render('Auth/ConfirmPassword');
        });
    }
}
