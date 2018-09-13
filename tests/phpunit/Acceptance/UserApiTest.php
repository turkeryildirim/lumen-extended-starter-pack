<?php
namespace App\Tests\Acceptance;

use App\Constants\UserRoleConstant;
use App\Tests\TestCase;

class UserApiTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testGetAsGuestError()
    {
        $json = $this->json('GET', '/api/user', []);

        $this->assertTrue($json->response->getStatusCode() === 401);
    }

    public function testGetWithInvalidKey()
    {
        $json = $this->json('GET', '/api/user', [], ['Authorization'=>"xxx"]);

        $this->assertTrue($json->response->getStatusCode() === 403);
    }

    public function testGetMethodAsUserError()
    {
        $authorization = $this->getApiToken(UserRoleConstant::USER);
        factory(\App\Models\UserModel::class)->create();
        $json = $this->json('GET', '/api/user', [], ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 403);
    }

    public function testGetMethodAsAdmin()
    {
        $authorization = $this->getApiToken(UserRoleConstant::ADMIN);
        factory(\App\Models\UserModel::class)->create();
        $json = $this->json('GET', '/api/user', [], ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 200);
        $this->assertCount(2, $json->response->original['data']);
    }

    public function testShowMethodAsGuestError()
    {
        $id = factory(\App\Models\UserModel::class)->create()->id;
        $json = $this->json('GET', '/api/user/'.$id, []);

        $this->assertTrue($json->response->getStatusCode() === 401);
    }

    public function testShowMethodAsUserToViewSelf()
    {
        $user = $this->createUser(UserRoleConstant::USER);
        $authorization = $user->api_token;
        $json = $this->json('GET', '/api/user/'.$user->id, [], ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 200);
        $this->assertTrue($json->response->original['data']['id'] == $user->id);
    }

    public function testShowMethodUserAsUserToViewOtherError()
    {
        $authorization = $this->getApiToken(UserRoleConstant::USER);
        $id = factory(\App\Models\UserModel::class)->create()->id;
        $json = $this->json('GET', '/api/user/'.$id, [], ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 403);
    }

    public function testShowMethodAsAdmin()
    {
        $authorization = $this->getApiToken(UserRoleConstant::ADMIN);
        $id = factory(\App\Models\UserModel::class)->create()->id;
        $json = $this->json('GET', '/api/user/'.$id, [], ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 200);
        $this->assertTrue($json->response->original['data']['id'] == $id);
    }

    public function testShowMethodAsAdminError()
    {
        $authorization = $this->getApiToken(UserRoleConstant::ADMIN);
        $id = factory(\App\Models\UserModel::class)->create()->id;
        $json = $this->json('GET', '/api/user/55', [], ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 404);
    }

    public function testCreateMethodAsGuestError()
    {
        $model = (factory(\App\Models\UserModel::class)->make())->toArray();
        $json = $this->json('POST', '/api/user', $model);

        $this->assertTrue($json->response->getStatusCode() === 401);
    }

    public function testCreateMethodAsUserError()
    {
        $authorization = $this->getApiToken(UserRoleConstant::USER);
        $model = (factory(\App\Models\UserModel::class)->make())->toArray();
        $json = $this->json('POST', '/api/user', $model, ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 403);
    }

    public function testCreateMethodAsAdmin()
    {
        $authorization = $this->getApiToken(UserRoleConstant::ADMIN);
        $model=(factory(\App\Models\UserModel::class)->make())->toArray();
        $json = $this->json('POST', '/api/user', $model, ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 200);
        $this->assertTrue($json->response->original['data']['email'] == $model['email']);
    }

    public function testCreateMethodAsAdminError()
    {
        $user = $this->createUser(UserRoleConstant::ADMIN);
        $authorization = $user->api_token;
        $model=(factory(\App\Models\UserModel::class)->make())->toArray();
        $model['email'] = null;
        $json = $this->json('POST', '/api/user', $model, ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 422);

        $model['email'] = $user->email;
        $json = $this->json('POST', '/api/user', $model, ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 412);
    }

    public function testUpdateMethodAsGuest()
    {
        $id=factory(\App\Models\UserModel::class)->create()->id;
        $model=factory(\App\Models\UserModel::class)->make()->toArray();
        $json = $this->json('PUT', '/api/user/'.$id, $model);

        $this->assertTrue($json->response->getStatusCode() === 401);
    }

    public function testUpdateMethodAsUser()
    {
        $authorization = $this->getApiToken(UserRoleConstant::USER);
        $id=factory(\App\Models\UserModel::class)->create()->id;
        $model=factory(\App\Models\UserModel::class)->make()->toArray();
        $json = $this->json('PUT', '/api/user/'.$id, $model, ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 403);
    }

    public function testUpdateMethodAsAdmin()
    {
        $authorization = $this->getApiToken(UserRoleConstant::ADMIN);
        $user = factory(\App\Models\UserModel::class)->create();
        $model = factory(\App\Models\UserModel::class)->make()
            ->toArray();
        $model['password'] = '1234567';
        $json = $this->json('PUT', '/api/user/'.$user->id, $model, ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 200);
        $this->assertTrue($json->response->original['data']['email'] === $model['email']);
        $this->assertTrue($user->email !== $model['email']);
    }

    public function testUpdateMethodAsAdminError()
    {
        $authorization = $this->getApiToken(UserRoleConstant::ADMIN);
        $model = factory(\App\Models\UserModel::class)->create()->toArray();
        $json = $this->json('PUT', '/api/user/55', $model, ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 404);

        $model['first_name'] = 'a';
        $json = $this->json('PUT', '/api/user/'.$model['id'], $model, ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 422);
    }

    public function testDeleteMethodAsGuest()
    {
        $id=factory(\App\Models\UserModel::class)->create()->id;
        $json = $this->json('DELETE', '/api/user/'.$id, []);

        $this->assertTrue($json->response->getStatusCode() === 401);
    }

    public function testDeleteMethodAsUser()
    {
        $authorization = $this->getApiToken(UserRoleConstant::USER);
        $id=factory(\App\Models\UserModel::class)->create()->id;
        $json = $this->json('DELETE', '/api/user/'.$id, [], ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 403);
    }

    public function testDeleteMethodAsAdmin()
    {
        $authorization = $this->getApiToken(UserRoleConstant::ADMIN);
        $id=factory(\App\Models\UserModel::class)->create()->id;
        $json = $this->json('DELETE', '/api/user/'.$id, [], ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 200);
        $this->SeeInDatabase('users', [['id', '=', $id], ['deleted_at', '!=', null]]);
    }

    public function testDeleteMethodAsAdminError()
    {
        $authorization = $this->getApiToken(UserRoleConstant::ADMIN);
        $json = $this->json('DELETE', '/api/user/55', [], ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 404);
    }

    public function testAuthMethod()
    {
        $user = $this->createUser(UserRoleConstant::USER);
        $authorization = $user->api_token;
        $json = $this->json('GET', '/api/user/me', [], ['Authorization'=>"$authorization"]);

        $this->assertTrue($json->response->getStatusCode() === 200);
        $this->assertTrue($json->response->original['data']['email'] === $user->email);
    }
}
