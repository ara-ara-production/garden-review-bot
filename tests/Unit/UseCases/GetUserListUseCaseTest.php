<?php

namespace Tests\Unit\UseCases;

use App\Services\UserService;
use App\UseCases\Admin\Brunch\GetBrunchListUseCase;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

#[CoversMethod(GetBrunchListUseCase::class, 'use')]
class
GetUserListUseCaseTest extends TestCase
{
    public function test_use_returnsPaginatorArray_whenServiceSucceeds()
    {
        // Мокаем UserService
        /** @var UserService|MockObject $userServiceMock */
        $userServiceMock = $this->createMock(UserService::class);

        // Создаем фейковый пагинатор (можно передать пустой)
        $fakePaginator = $this->createMock(LengthAwarePaginator::class);

        // Ожидаем, что getPaginator вызовется и вернет $fakePaginator
        $userServiceMock->expects($this->once())
            ->method('getPaginator')
            ->willReturn($fakePaginator);

        // Создаем use case с замоком
        $useCase = new GetBrunchListUseCase($userServiceMock);

        $result = $useCase->use();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('paginator', $result);
        $this->assertSame($fakePaginator, $result['paginator']);
    }

    public function test_use_returnsErrorMessage_whenServiceThrows()
    {
        $userServiceMock = $this->createMock(UserService::class);

        $userServiceMock->expects($this->once())
            ->method('getPaginator')
            ->willThrowException(new \Exception('Something went wrong'));

        $useCase = new GetBrunchListUseCase($userServiceMock);

        $result = $useCase->use();

        $this->assertIsString($result);
        $this->assertEquals('Something went wrong', $result);
    }
}
