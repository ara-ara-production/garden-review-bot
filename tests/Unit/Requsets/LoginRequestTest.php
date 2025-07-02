<?php

namespace Tests\Unit\Requsets;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(LoginRequest::class)]
class LoginRequestTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function rules_should_return_expected_rules(): void
    {
        $request = new LoginRequest();

        $rules = $request->rules();

        $this->assertArrayHasKey('email', $rules);
        $this->assertContains('required', $rules['email']);
        $this->assertContains('string', $rules['email']);
        $this->assertContains('email', $rules['email']);

        $this->assertArrayHasKey('password', $rules);
        $this->assertContains('required', $rules['password']);
        $this->assertContains('string', $rules['password']);
    }

    #[Test]
    public function authenticate_success_clears_rate_limiter(): void
    {
        RateLimiter::shouldReceive('tooManyAttempts')->once()->andReturn(false);
        RateLimiter::shouldReceive('clear')->once();

        Auth::shouldReceive('attempt')->once()->andReturn(true);

        $request = $this->partialMock(LoginRequest::class, function ($mock) {
            $mock->shouldReceive('only')->with('email', 'password')->andReturn([
                'email' => 'test@example.com',
                'password' => 'password',
            ]);
            $mock->shouldReceive('boolean')->with('remember')->andReturn(false);
            $mock->shouldReceive('throttleKey')->andReturn('throttle-key');
        });

        $request->authenticate();

        $this->addToAssertionCount(1); // чтобы phpunit не жаловался на отсутствие assertions
    }

    #[Test]
    public function authenticate_failure_hits_rate_limiter_and_throws_validation_exception(): void
    {
        RateLimiter::shouldReceive('tooManyAttempts')->once()->andReturn(false);
        RateLimiter::shouldReceive('hit')->once()->with('throttle-key');

        Auth::shouldReceive('attempt')->once()->andReturn(false);

        $request = $this->partialMock(LoginRequest::class, function ($mock) {
            $mock->shouldReceive('only')->with('email', 'password')->andReturn([
                'email' => 'wrong@example.com',
                'password' => 'wrongpass',
            ]);
            $mock->shouldReceive('boolean')->with('remember')->andReturn(false);
            $mock->shouldReceive('throttleKey')->andReturn('throttle-key');
            $mock->shouldReceive('messages')->andReturn([
                'auth.failed' => 'Почта или пароль неверен',
            ]);
        });

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Почта или пароль неверен');

        $request->authenticate();
    }

    #[Test]
    public function ensureIsNotRateLimited_throws_lockout_exception_when_too_many_attempts(): void
    {
        RateLimiter::shouldReceive('tooManyAttempts')->once()->with('throttle-key', 5)->andReturn(true);
        RateLimiter::shouldReceive('availableIn')->once()->with('throttle-key')->andReturn(120);

        Event::fake();

        $request = $this->partialMock(LoginRequest::class, function ($mock) {
            $mock->shouldReceive('throttleKey')->andReturn('throttle-key');
            $mock->shouldReceive('messages')->andReturn([
                'auth.throttle' => 'Слишком много попыток входа, попробуйте позже',
            ]);
        });

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Слишком много попыток входа, попробуйте позже');

        try {
            $request->ensureIsNotRateLimited();
        } catch (ValidationException $e) {
            Event::assertDispatched(Lockout::class);
            throw $e;
        }
    }

    #[Test]
    public function throttleKey_returns_correct_format(): void
    {
        $request = $this->partialMock(LoginRequest::class, function ($mock) {
            $mock->shouldReceive('string')->with('email')->andReturn('Test@Example.com');
            $mock->shouldReceive('ip')->andReturn('127.0.0.1');
        });

        $expected = strtolower('Test@Example.com') . '|127.0.0.1';

        $result = $request->throttleKey();

        $this->assertEquals($expected, $result);
    }
}
