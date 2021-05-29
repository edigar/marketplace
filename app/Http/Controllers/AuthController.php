<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @var JWTAuth $jwt
     */
    protected $jwt;

    /**
     * Create a new AuthController instance.
     *
     * @param JWTAuth $jwt
     * @return void
     */
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Get a JWT via given credentials.
     * @OA\Post(
     *     tags={"Auth"},
     *     summary="Login",
     *     description="Get a JWT via given credentials",
     *     path="/api/auth/loginh",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Credentials",
     *         @OA\JsonContent(properties={
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         })
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="token",
     *         @OA\JsonContent(properties={
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string"),
     *             @OA\Property(property="expires_in", type="integer"),
     *         }),
     *     ),
     *     @OA\Response(response=422, description="Missing Data"),
     *     @OA\Response(response=404, description="User not found")
     * ),
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        if (!$token = $this->jwt->attempt($request->only('email', 'password'))) {
            return response()->json(['user_not_found'], 404);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @OA\Post(
     *     tags={"Auth"},
     *     summary="About logged user",
     *     description="About Logged user",
     *     path="/api/auth/me",
     *     @OA\SecurityScheme(
     *         securityScheme="bearerAuth",
     *         in="header",
     *         name="bearerAuth",
     *         type="http",
     *         scheme="bearer",
     *         bearerFormat="JWT",
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Logged user data",
     *         @OA\JsonContent(properties={
     *             @OA\Property(property="id", type="string"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="created_at", type="string"),
     *             @OA\Property(property="updated_at", type="string"),
     *         }),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * ),
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @OA\Post(
     *     tags={"Auth"},
     *     summary="Logout",
     *     description="Log the user out",
     *     path="/api/auth/logout",
     *     @OA\SecurityScheme(
     *         securityScheme="bearerAuth",
     *         in="header",
     *         name="bearerAuth",
     *         type="http",
     *         scheme="bearer",
     *         bearerFormat="JWT",
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Logout",
     *         @OA\JsonContent(properties={
     *             @OA\Property(property="message", type="string"),
     *         }),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * ),
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @OA\Post(
     *     tags={"Auth"},
     *     summary="Refresh",
     *     description="Refresh a token",
     *     path="/api/auth/refresh",
     *     @OA\SecurityScheme(
     *         securityScheme="bearerAuth",
     *         in="header",
     *         name="bearerAuth",
     *         type="http",
     *         scheme="bearer",
     *         bearerFormat="JWT",
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Logout",
     *         @OA\JsonContent(properties={
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string"),
     *             @OA\Property(property="expires_in", type="integer"),
     *         }),
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * ),
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
