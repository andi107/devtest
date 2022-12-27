<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $e)
    {
        if (env('APP_DEBUG')) {
            return parent::render($request, $e);
        }
        if ($e instanceof ValidationException) {
            return $e->response;
        }

        $message = $e->getMessage();

        if (!$message) {
            $message = 'Internal Server Error.';
        }

        $user = Auth::user();

        if (isset($user->_id)) {
            Log::error(sprintf('Exception Message: %s - User: %s', $message, $user->_id));
        } else {
            Log::error(sprintf('Exception Message: %s', $message));
        }

        return response()->json([
            'error' => $message,
        ], 500)
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-Frame-Options', 'DENY')
            ->header('X-XSS-Protection', '1; mode=block')
            ->header('Strict-Transport-Security', 'max-age=7776000; includeSubDomains');
    }
}
