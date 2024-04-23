<?php

namespace App\Core\Services;

use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Utils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseService
{
    /**
     * Generate an error response.
     *
     * @param string $title The error title.
     * @param string $message The error message.
     *
     * @return Response The error response.
     */
    public function generateErrorResponse(string $title, string $message): Response
    {
        return Utils::errorResponse(Codes::$forbidden, [
            'title'   => $title,
            'message' => $message,
            'close'   => _i('Close'),
        ]);
    }

    /**
     * Handle an error and respond with an error response.
     *
     * This method logs an error in the log and returns a standard error response.
     *
     * @param Request $request The HTTP request related to the error.
     *
     * @param Exception $ex The exception object to be logged.
     * @return Response The error response in JSON format.
     */
    public function handleAndRespondToError(Request $request, Exception $ex): Response
    {
        Log::error(
            __METHOD__,
            [
                'exception' => $ex,
                'request'   => $request->all(),
            ],
        );
        return Utils::failedResponse();
    }

    /**
     * Handle an empty transaction object.
     *
     * @param Request $request The request object containing transaction details.
     * @param mixed $information The information object.
     *
     * @return bool|Response False if the transaction is not empty, otherwise a response indicating an error.
     */
    public function handleEmptyTransactionObject(Request $request, mixed $information): bool|Response
    {
        if (empty($information) || empty($information->data)) {
            Log::error('error data, wallet getByClient', [
                'currency'    => session('currency'),
                'request'     => $request->all(),
                'userAuthId'  => $request->user()->id,
                'transaction' => $information,
            ]);

            return $this->generateErrorResponse(
                _i('An error occurred'),
                _i('please contact support'),
            );
        }

        return false;
    }

}