<?php


namespace Feature\app\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\TestCase;

/**
 * Class ProductControllerTest
 * @package Feature\app\Http\Controllers
 */
class ProductControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @inheritDoc
     */
    public function createApplication()
    {
        return require './bootstrap/app.php';
    }

    public function testRequestShouldGetAllProducts()
    {
        $request = $this->get(route('product-get'));
        $request->assertResponseOk();
    }

    public function testRequestGetProductShouldReturnNotFoundIfProductNonExists()
    {
        $request = $this->get(route('product-get-id', ['id' => 'abc-123']));

        $request->assertResponseStatus(404);
    }

    public function testRequestGetProductShouldReturnAProduct()
    {
        $product = Product::factory()->create();

        $request = $this->get(route('product-get-id', ['id' => $product->id]));

        $request->assertResponseOk();
        $request->seeJsonContains([
            'id' => $product->id,
            'description' => $product->description,
            'price' => $product->price,
        ]);
    }

    public function testRequestGetProductPricesShouldReturnNotFoundIfProductNonExists()
    {
        $request = $this->get(route('product-prices', ['productId' => 'abc-123']));

        $request->assertResponseStatus(404);
    }

    public function testRequestGetProductPricesShouldReturnStatusOk()
    {
        $product = Product::factory()->create();

        $request = $this->get(route('product-prices', ['productId' => $product->id]));

        $request->assertResponseOk();
    }

    public function testShouldNotSaveProductIfUserNotLogged()
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login(User::whereEmail($user->email)->first());
        $headers = [
            'Authorization' => 'Bearer ' . $token
        ];
        $payload = [
            'description' => 'Product test',
            'price' => 10.50,
        ];

        $request = $this->post(route('product-post', $payload, $headers));

        $request->assertResponseOk();
        $request->seeJsonStructure(['id', 'description', 'price', 'created_at', 'updated_at']);
        $request->seeInDatabase(
            'products',
            ['description' => $payload['description'], 'price' => $payload['price']],
        );
    }
}
