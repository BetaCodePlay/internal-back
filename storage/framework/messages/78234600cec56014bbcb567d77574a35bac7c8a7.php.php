<?php

namespace App\Exceptions;

use Facade\Ignition\Exceptions\ViewException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
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
        if ($exception instanceof ValidationException) {
            return response()->json(['message' => _i('The given data was invalid'), 'errors' => $exception->validator->getMessageBag()], 422);
        }

        if ($exception instanceof ViewException) {
            Log::error(__METHOD__, ['exception' => $exception, 'uri' => $_SERVER['REQUEST_URI'], 'request' => $request->all(), 'referer' => $_SERVER['HTTP_REFERER'] ?? null, 'host' => $_SERVER['HTTP_HOST'] ?? null]);
        }
        return parent::render($request, $exception);
    }
}
