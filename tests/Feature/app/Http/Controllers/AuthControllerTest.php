<?php

namespace Feature\app\Http\Controllers;

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\TestCase;

/**
 * Class AuthControllerTest
 * @package Feature\app\Http\Controllers
 */
class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $token;

    /**
     * @inheritDoc
     */
    public function createApplication()
    {
        return require './bootstrap/app.php';
    }

    public function testShouldNotFondWithWrongCredentials()
    {
        $payload = [
            'email' => 'test@test.com',
            'password' => 'wrong',
        ];

        $request = $this->post(route('authenticate'), $payload);

        $request->assertResponseStatus(404);
        $request->seeJson(['user_not_found']);
    }

    public function testUserCanAuthenticate()
    {
        $user = User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'abc123'
        ];

        $request = $this->post(route('authenticate'), $payload);

        $request->assertResponseStatus(200);
        $request->seeJsonStructure(['access_token', 'token_type', 'expires_in']);
    }

    public function testUserShouldNotRefreshTokenWithWrongToken()
    {
        $headers = [
            'Authorization' => 'Bearer ' . 'wrong-token',
        ];

        $request = $this->post(route('auth-refresh'), [], $headers);
        $request->assertResponseStatus(401);
    }

    public function testUserCanRefreshToken()
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login(User::whereEmail($user->email)->first());
        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];

        $request = $this->json('POST', route('auth-refresh'), [], $headers);

        $request->assertResponseOk();
        $request->seeJsonStructure(['access_token', 'token_type', 'expires_in']);
    }

    public function testShouldFailOnGetUserData()
    {
        $header = [
            'Authorization' => 'Bearer ' . 'wrong-token',
        ];

        $request = $this->post(route('auth-me'), [], $header);
        $request->assertResponseStatus(401);
    }

    public function testShouldGetUserData()
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login(User::whereEmail($user->email)->first());
        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];

        $request = $this->json('POST', route('auth-me'), [], $headers);
        $request->assertResponseStatus(200);
        $request->seeJsonStructure(['id', 'name', 'email', 'created_at', 'updated_at']);
    }

    public function testShouldNotLogoutWithWrongToken()
    {
        $header = [
            'Authorization' => 'Bearer ' . 'wrong-token',
        ];

        $request = $this->post(route('logout'), [], $header);
        $request->assertResponseStatus(401);
    }

    public function testLoggedUserShouldLogout()
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login(User::whereEmail($user->email)->first());
        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];

        $request = $this->post(route('logout'), [], $headers);

        $request->assertResponseStatus(200);
        $request->seeJson(['message' => 'Successfully logged out']);
    }
}
