<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CurrencyServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
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
     *     tags={"Product"},
     *     summary="List prices in all valid currencies",
     *     description="Returns a json of prices in different currencies",
     *     path="/api/product/{productId}/prices",
     *     @OA\Parameter(
     *         in="path",
     *         name="productId",
     *         description="product id",
     *         @OA\Schema(
     *             required={"productId"},
     *             type="string",
     *         ),
     *     ),
     *     @OA\Response(response="200", description="Prices in valid currencies"),
     *     @OA\Response(response=422, description="Missing Data")
     * ),
     *
     * @param Request $request
     * @param $productId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function showPrices(Request $request, $productId): JsonResponse
    {
        $request['productId'] = $productId;
        $this->validate($request , ['productId' => 'required|string']);

        /** @var \App\Models\Product $product */
        $product = Product::findOrFail($productId);
        $prices = $this->currencyService->convert($product->price);

        return response()->json($prices);
    }

    /**
     * @OA\Get(
     *     tags={"Product"},
     *     summary="Get a list of products",
     *     description="Returns a json of products",
     *     path="/api/product",
     *     @OA\Response(response="200", description="A list of product"),
     * ),
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        /** @var \App\Models\Product $product */
        $products = Product::all();

        return response()->json($products);
    }

    /**
     * @OA\Get(
     *     tags={"Product"},
     *     summary="Get a product by id",
     *     description="Returns a json of one product",
     *     path="/api/product/{id}",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         description="product id",
     *         @OA\Schema(
     *             required={"id"},
     *             type="string",
     *         ),
     *     ),
     *     @OA\Response(response="200", description="A product"),
     *     @OA\Response(response=422, description="Missing Data")
     * ),
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show(Request $request, $id): JsonResponse
    {
        $request['id'] = $id;
        $this->validate($request , ['id' => 'required|string']);

        /** @var \App\Models\Product $product */
        $product = Product::findOrFail($request['id']);

        return response()->json($product->toArray());
    }

    /**
     * @OA\Post(
     *     tags={"Product"},
     *     summary="New product",
     *     description="Add a new product",
     *     path="/api/product",
     *     @OA\SecurityScheme(
     *         securityScheme="bearerAuth",
     *         in="header",
     *         name="bearerAuth",
     *         type="http",
     *         scheme="bearer",
     *         bearerFormat="JWT",
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Post object",
     *         @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A post",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResponse"),
     *     ),
     *     @OA\Response(response=422, description="Missing Data")
     * ),
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request , [
            'description'=>'required|string',
            'price' => 'required|numeric|min:0|max:999999',
        ]);

        /** @var \App\Models\Product $product */
        $product = Product::create($request->only(['description', 'price']));

        return response()->json($product);
    }
}
