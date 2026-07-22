<?php

use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

uses(Tests\TestCase::class);

it('denies staff access to admin routes', function () {
    $middleware = new RoleMiddleware();
    $request = Request::create('/admin', 'GET');

    $user = new User();
    $user->role = User::ROLE_STAFF;

    $request->setUserResolver(fn () => $user);

    try {
        $middleware->handle($request, fn () => response('ok'), User::ROLE_ADMIN);
        $this->fail('Expected HttpException was not thrown');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(403);
    }
});
