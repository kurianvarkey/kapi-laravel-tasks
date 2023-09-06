<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Kapi\Traits\AppResponseTrait;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use AppResponseTrait;

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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     * @codeCoverageIgnore
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException) {
            return $this->sendThrottleResponse($exception->getMessage());
        }

        $error = 'Internal error occured';
        $code = Response::HTTP_BAD_REQUEST;

        if ($exception instanceof NotFoundHttpException) {
            $error = 'Not Found. Please check your endpoint. ' . $exception->getMessage();
        } elseif ($exception instanceof ModelNotFoundException) {
            $error = 'No record found for given id';
            $code = Response::HTTP_NOT_FOUND;
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $error = 'Method Not Allowed. Please check your endpoint and verb type. ' . $exception->getMessage();
        } elseif ($exception instanceof ValidationException) {
            $error = 'Validation errors: ' . $exception->getMessage();
        } else {
            $error = 'Unknown error: ' . $exception->getMessage() . ' On file ' . $exception->getFile() . ' line ' . $exception->getLine();
        }

        return $this->sendResponse(null, Response::HTTP_BAD_REQUEST, ['code' => $code, 'error' => $error]);
    }
}
