<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;

class CurrencyApiException extends \Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an Json response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request): JsonResponse
    {
        return response()->json($this->getMessage(), $this->getCode());
    }
}
