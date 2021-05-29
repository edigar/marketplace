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
     * @param CurrencyServiceInterface $currencyService
     * @return void
     */
    public function __construct(CurrencyServiceInterface $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @OA\Get(
     *     tags={"Price-conversion"},
     *     summary="Convert an amount to a valid currencies list",
     *     description="Returns a json values in valid currencies",
     *     path="/api/price-conversion/{amount}",
     *     @OA\Parameter(
     *         in="path",
     *         name="amount",
     *         description="Amount to be converted",
     *         @OA\Schema(
     *             required={"amount"},
     *             type="number",
     *             minimum=1,
     *             maximum=999999,
     *         ),
     *     ),
     *     @OA\Response(response="200", description="A list with currencies converted"),
     *     @OA\Response(response=422, description="Missing Data")
     * ),
     *
     * @param Request $request
     * @param float   $amount
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function conversion(Request $request, $amount): JsonResponse
    {
        $request['amount'] = $amount;
        $this->validate($request , ['amount'=>'required|numeric|between:0,999999']);

        $prices = $this->currencyService->convert($amount);

        return response()->json($prices);
    }
}
