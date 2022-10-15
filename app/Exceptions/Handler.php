<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                if ($e instanceof HttpException) {
                    return response()->json([], $e->getStatusCode());
                } elseif ($e  instanceof QueryException) {
                    return response()->json(app()->environment() !== 'production' ? ['message' => $e->getMessage()] : [], 500);
                } elseif (get_class($e) === 'Illuminate\Auth\AuthenticationException') {
                    return response()->json(['message' => $e->getMessage()], 401);
                } elseif (get_class($e) !== 'Illuminate\Validation\ValidationException') {
                    if (app()->environment() !== 'production') {
                        return response()->json(
                            [
                                'message' => $e->getMessage(),
                                'exception' => get_class($e),
                                'file' => $e->getFile(),
                                'line' => $e->getLine(),
                                'trace' => $e->getTrace()
                            ],
                            ($e->getCode() ? $e->getCode() : 500)
                        );
                    } else {
                        return response()->json(['message' => $e->getMessage()], ($e->getCode() ? $e->getCode() : 500));
                    }
                }
            }
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
