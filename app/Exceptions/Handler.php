<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {

            $code = $exception->getCode();
            if (method_exists($exception, 'getStatusCode')) {
                $code = $exception->getStatusCode();
            }

            if (empty($code)) {
                $code = 500;
            }

            $result = ['error' => $exception->getMessage()];

            if ($exception instanceof ValidationException) {
                $code = $exception->status;
                $result['fields'] = $exception->errors();
                if ($result['error'] === 'The given data was invalid.') {
                    $result['error'] = 'Произошла ошибка';
                }
            } elseif ($exception instanceof QueryException) {
                $code = 500;
            }

            // Для отладки
            $result['trace'] = $exception->getTrace();

            return response()->json($result, $code);
        }

        return parent::render($request, $exception);
    }

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
    }
}
