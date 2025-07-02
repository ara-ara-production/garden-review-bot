<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Inertia\Testing\AssertableInertia;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(AuthenticatedSessionController::class)]
class AuthenticatedSessionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_displays_login_view_with_expected_props(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertInertia(fn (AssertableInertia $page) =>
        $page->component('Login')
            ->where('canResetPassword', false)
            ->where('status', null)
        );
    }

    public function test_store_authenticates_and_redirects(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Session::has('_token'));
    }

    public function test_destroy_logs_out_and_redirects_to_home(): void
    {
        $user = \App\Models\User::factory()->create();

        $this->be($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');

        $this->assertGuest();
        $this->assertTrue(Session::has('_token'));
    }

}
