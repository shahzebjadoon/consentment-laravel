<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Class Handler.
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        GeneralException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {


        // Check for the type of error and if the request expects JSON
        if ($exception instanceof NotFoundHttpException) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Not Found'], 404); // Customize the message if needed
            }
        }

        if ($exception instanceof UnauthorizedException) {
            return redirect()
                ->route(homeRoute())
                ->withFlashDanger(__('You do not have access to do that.'));
        }

        if ($exception instanceof AuthorizationException) {
            return redirect()
                ->back()
                ->withFlashDanger($exception->getMessage() ?? __('You do not have access to do that.'));
        }

        if ($exception instanceof ModelNotFoundException) {
            return redirect()
                ->route(homeRoute())
                ->withFlashDanger(__('The requested resource was not found.'));
        }

        return parent::render($request, $exception);
    }


    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest('/new/login');
    }
}
