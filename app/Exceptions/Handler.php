<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        // // redirect to the "unable to verify your login" error message
        // $this->renderable(function (MissingShopDomainException $e) {
        //     return redirect()->route('apps.shopify.no-auth');
        // });
    }

    // public function render($request, Throwable $exception)
    // {
    //     // if ($exception instanceof MissingShopDomainException)
    //     if ($exception instanceof \Osiset\ShopifyApp\Exceptions\MissingShopDomainException)
    //     {
    //         return Redirect::secure('login');
    //     }

    //     return parent::render($request, $exception);
    // }
}
