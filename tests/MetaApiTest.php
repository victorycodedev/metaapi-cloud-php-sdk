<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Victorycodedev\MetaapiCloudPhpSdk\AccountApi;
use Victorycodedev\MetaapiCloudPhpSdk\CopyFactory;
use Victorycodedev\MetaapiCloudPhpSdk\Http;
use Victorycodedev\MetaapiCloudPhpSdk\MetaStats;

#[CoversNothing]
class MetaapiTest extends TestCase
{
    protected Client $client;

    private string $token;

    protected string $acntUrl = 'https://mt-provisioning-api-v1.agiliumtrade.agiliumtrade.ai';

    // setup
    public function setUp(): void
    {
        parent::setUp();
        $this->token = 'test-token';
    }

    /** @test */
    public function it_can_instantiate_the_accountapi_object(): void
    {
        $object = new AccountApi($this->token);

        $this->assertTrue(is_object($object));
    }

    /** @test */
    public function it_can_instantiate_the_copyfactory_object(): void
    {
        $object = new CopyFactory($this->token);
        $this->assertTrue(is_object($object));
    }

    /** @test */
    public function it_can_instantiate_the_metatstats_object(): void
    {
        $object = new MetaStats($this->token);
        $this->assertTrue(is_object($object));
    }

    /** @test */
    public function it_can_make_a_get_request(): void
    {
        // require the vairables from the Variables.php file
        require_once 'Variables.php';

        // Create a mock.
        $mock = new MockHandler([
            new Response(200, [], $jsonResponse),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $this->client = new Client(['handler' => $handlerStack, 'headers' => [
            'Accept' => 'application/json',
        ], ]);

        $http = new Http($this->token, '', $this->client);

        $response = $http->get("{$this->acntUrl}/users/current/accounts/1eda642a-a9a3-457c-99af-3bc5e8d5c4c9");

        $this->assertEquals($arrayResonse, $response);
        $this->assertIsArray($response);
    }

    /** @test */
    public function it_can_make_a_post_request(): void
    {
        // Create a mock.
        $mock = new MockHandler([
            new Response(200, [], '{
                "id": "1eda642a-a9a3-457c-99af-3bc5e8d5c4c9",
                "state": "DEPLOYED"
              }'),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $this->client = new Client(['handler' => $handlerStack, 'headers' => [
            'Accept' => 'application/json',
        ], ]);

        $http = new Http($this->token, '', $this->client);

        $response = $http->post("{$this->acntUrl}/users/current/accounts");
        $this->assertIsArray($response);
    }

    /** @test */
    public function it_can_make_a_put_request(): void
    {
        // Create a mock.
        $mock = new MockHandler([
            new Response(200, [], ''),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $this->client = new Client(['handler' => $handlerStack, 'headers' => [
            'Accept' => 'application/json',
        ], ]);

        $http = new Http($this->token, '', $this->client);

        $response = $http->put("{$this->acntUrl}/users/current/accounts/1eda642a-a9a3-457c-99af-3bc5e8d5c4c9", [
            'password' => 'password',
            'name'     => 'testAccount',
            'server'   => 'ICMarketsSC-Demo',
        ]);

        $this->assertArrayHasKey('success', $response);
    }

    /** @test */
    public function it_can_make_a_delete_request(): void
    {
        // Create a mock.
        $mock = new MockHandler([
            new Response(200, [], ''),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $this->client = new Client(['handler' => $handlerStack, 'headers' => [
            'Accept' => 'application/json',
        ], ]);

        $http = new Http($this->token, '', $this->client);

        $response = $http->delete("{$this->acntUrl}/users/current/accounts/1eda642a-a9a3-457c-99af-3bc5e8d5c4c9");

        $this->assertArrayHasKey('success', $response);
    }
}
