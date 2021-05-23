<?php

namespace App\Http\Controllers;

use App\Services\CurrencyServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class PriceController
 * @package App\Http\Controllers
 */
class PriceController extends Controller
{
    /** @var CurrencyServiceInterface $currencyService */
    private $currencyService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CurrencyServiceInterface $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @param Request $request
     * @param float   $amount
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function conversion(Request $request, $amount): JsonResponse
    {
        $request['amount'] = $amount;
        $this->validate($request , ['amount'=>'required|numeric|between:0,999999']);

        $result = $this->currencyService->convert($amount);

        return response()->json($result);
    }
}
