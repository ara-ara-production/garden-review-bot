<?php

namespace Tests\Unit\Requsets;

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(HandleInertiaRequests::class)]
class HandleInertiaRequestsTest extends TestCase
{
    #[Test]
    public function share_returns_expected_data(): void
    {
        $middleware = new HandleInertiaRequests();

        $user = new class {
            public $id = 1;
            public $name = 'Test User';
        };

        $request = Request::create('/test', 'GET');
        $request->setUserResolver(fn () => $user);

        Config::set('resourseroutes', ['home' => '/home', 'profile' => '/profile']);

        $sharedData = $middleware->share($request);

        $this->assertArrayHasKey('auth', $sharedData);
        $this->assertArrayHasKey('user', $sharedData['auth']);
        $this->assertSame($user, $sharedData['auth']['user']);

        $this->assertArrayHasKey('routes', $sharedData);
        $this->assertEquals(['home' => '/home', 'profile' => '/profile'], $sharedData['routes']);
    }
}
