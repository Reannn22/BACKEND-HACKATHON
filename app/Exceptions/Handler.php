<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*') || $request->expectsJson() || $request->wantsJson()) {
                $status = 500;

                if ($e instanceof HttpException) {
                    $status = $e->getStatusCode();
                } elseif ($e instanceof ValidationException) {
                    $status = 422;
                }

                return response()->json([
                    'message' => $e->getMessage(),
                    'status' => 'error'
                ], $status)->header('Content-Type', 'application/json');
            }
        });

        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
        });
    }
}
