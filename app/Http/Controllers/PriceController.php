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
     * @OA\Get(
     *     tags={"price-conversion"},
     *     summary="Returns a list of currencies converted from amount",
     *     description="Returns a json of currencies",
     *     path="/api/v1/price-conversion/{amount}",
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

        $result = $this->currencyService->convert($amount);

        return response()->json($result);
    }
}
