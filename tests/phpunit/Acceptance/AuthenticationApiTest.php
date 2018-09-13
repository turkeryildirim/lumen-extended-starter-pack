<?php
namespace App\Tests\Acceptance;

use App\Constants\UserRoleConstant;
use App\Events\AuthenticationRegisterEvent;
use App\Models\UserModel;
use App\Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;

class AuthenticationApiTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testLoginMethodError()
    {
        $json = $this->json('POST', '/api/auth/login', [
            'email' => 'aa',
            'password' => 'bb'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 422);

        $json = $this->json('POST', '/api/auth/login', [
            'email' => $this->userEmail,
            'password' => '1234567'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 404);

        factory(\App\Models\UserModel::class)->create([
            'email' => $this->userEmail,
            'password' => Hash::make('1234567'),
            'status' => 0
        ]);
        $json = $this->json('POST', '/api/auth/login', [
            'email' => $this->userEmail,
            'password' => '123456'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 401);

        $json = $this->json('POST', '/api/auth/login', [
            'email' => $this->userEmail,
            'password' => '1234567'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 403);
    }

    public function testLoginMethodAsAdmin()
    {
        factory(\App\Models\UserModel::class)->create([
            'email' => $this->adminEmail,
            'password' => Hash::make($this->adminPassword)
        ]);

        $json = $this->json('POST', '/api/auth/login', [
            'email' => $this->adminEmail,
            'password' => $this->adminPassword
        ]);

        $this->assertTrue($json->response->getStatusCode() === 200);
        $this->assertNotNull($json->response->original['data']['authorization']);
    }

    public function testLoginMethodAsUser()
    {
        factory(\App\Models\UserModel::class)->create([
            'email' => $this->userEmail,
            'password' => Hash::make($this->userPassword)
        ]);

        $json = $this->json('POST', '/api/auth/login', [
            'email' => $this->userEmail,
            'password' => $this->userPassword
        ]);

        $this->assertTrue($json->response->getStatusCode() === 200);
        $this->assertNotNull($json->response->original['data']['authorization']);
    }

    public function testForgotPasswordMethodError()
    {
        $json = $this->json('POST', '/api/auth/forgot-password', [
            'email'=> 'aaaa'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 422);

        $json = $this->json('POST', '/api/auth/forgot-password', [
            'email'=> $this->userEmail
        ]);

        $this->assertTrue($json->response->getStatusCode() === 404);

        factory(\App\Models\UserModel::class)->create([
            'email' => $this->userEmail,
            'status' => 0
        ]);
        $json = $this->json('POST', '/api/auth/forgot-password', [
            'email'=> $this->userEmail
        ]);
        $this->assertTrue($json->response->getStatusCode() === 403);
    }

    public function testForgotPasswordMethodAsUser()
    {
        $user = $this->createUser(UserRoleConstant::USER);
        $json = $this->json('POST', '/api/auth/forgot-password', [
            'email'=> $user->email
        ]);

        $this->assertTrue($json->response->getStatusCode() === 200);
    }

    public function testForgotPasswordMethodAsAdmin()
    {
        $user = $this->createUser(UserRoleConstant::ADMIN);
        $json = $this->json('POST', '/api/auth/forgot-password', [
            'email'=> $user->email
        ]);

        $this->assertTrue($json->response->getStatusCode() === 200);
    }

    public function testResetPasswordMethodError()
    {
        $json = $this->json('GET', '/api/auth/reset-password', [
            'email'=> 'aaaa',
            'key' => '654321'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 404);

        $user = factory(\App\Models\UserModel::class)->create([
            'email'=> $this->userEmail
        ]);
        $json = $this->json('GET', '/api/auth/reset-password', [
            'email'=> $this->userEmail,
            'key' => '654321'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 401);

        $user->activation_code = '654321';
        $user->status = 0;
        $user->save();
        $json = $this->json('GET', '/api/auth/reset-password', [
            'email'=> $this->userEmail,
            'key' => '654321'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 403);
    }

    public function testResetPasswordMethodAsUser()
    {
        $user = $this->createUser(UserRoleConstant::USER);
        $json = $this->json('GET', '/api/auth/reset-password', [
            'email'=> $user->email,
            'key' => $user->activation_code
        ]);

        $this->assertTrue($json->response->getStatusCode() === 200);
    }

    public function testResetPasswordMethodAsAdmin()
    {
        $user = $this->createUser(UserRoleConstant::ADMIN);
        $json = $this->json('GET', '/api/auth/reset-password', [
            'email'=> $user->email,
            'key' => $user->activation_code
        ]);

        $this->assertTrue($json->response->getStatusCode() === 200);
    }

    public function testRegisterMethodError()
    {
        $json = $this->json('POST', '/api/auth/register', [
            'email'=> 'aaaa'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 422);

        $json = $this->json('POST', '/api/auth/register', [
            'email' => $this->userEmail,
            'password' => 'a'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 422);

        factory(\App\Models\UserModel::class)->create([
            'email' => $this->userEmail,
        ]);
        $json = $this->json('POST', '/api/auth/register', [
            'email'=> $this->userEmail
        ]);

        $this->assertTrue($json->response->getStatusCode() === 412);
    }

    public function testRegisterMethodWithFullDetails()
    {
        $json = $this->json('POST', '/api/auth/register', [
            'email'=> $this->userEmail,
            'password'=>$this->userPassword,
            'first_name'=>'name',
            'last_name'=>'surname'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 200);

        $user = UserModel::findWhere(['email' => $this->userEmail])->first();
        $this->assertTrue($json->response->original['data']['id'] === $user->id);

        Event::assertDispatched(AuthenticationRegisterEvent::class);
    }

    public function testRegisterMethodWithEmail()
    {
        $json = $this->json('POST', '/api/auth/register', [
            'email'=> $this->userEmail
        ]);

        $this->assertTrue($json->response->getStatusCode() === 200);

        Event::assertDispatched(AuthenticationRegisterEvent::class);
    }

    public function testActivateMethodError()
    {
        $json = $this->json('GET', '/api/auth/activate', [
            'email'=> 'aaaa',
            'key' => '654321'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 404);

        $user = factory(\App\Models\UserModel::class)->create([
            'email'=> $this->userEmail
        ]);
        $json = $this->json('GET', '/api/auth/activate', [
            'email'=> $this->userEmail,
            'key' => '654321'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 401);

        $user->activation_code = '654321';
        $user->status = 0;
        $user->save();
        $json = $this->json('GET', '/api/auth/activate', [
            'email'=> $this->userEmail,
            'key' => '654321'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 403);
    }

    public function testActivateMethod()
    {
        // $this->expectsEvents(...);

        factory(\App\Models\UserModel::class)->create([
            'email'=> $this->userEmail,
            'activation_code' => '654321'
        ]);

        $json = $this->json('GET', '/api/auth/activate', [
            'email' => $this->userEmail,
            'key' => '654321'
        ]);

        $this->assertTrue($json->response->getStatusCode() === 200);
    }

    public function testResendActivationMethodError()
    {
        $json = $this->json('POST', '/api/auth/resend-activation', [
            'email'=> 'aaaa',
        ]);

        $this->assertTrue($json->response->getStatusCode() === 422);

        $json = $this->json('POST', '/api/auth/resend-activation', [
            'email'=> $this->userEmail,
        ]);

        $this->assertTrue($json->response->getStatusCode() === 404);

        $user = factory(\App\Models\UserModel::class)->create([
            'email'=> $this->userEmail,
            'activation_code' => '654321',
            'status' => 0
        ]);
        $json = $this->json('POST', '/api/auth/resend-activation', [
            'email'=> $this->userEmail,
        ]);

        $this->assertTrue($json->response->getStatusCode() === 403);

        $user->status = 1;
        $user->activation_date = Carbon::now();
        $user->save();
        $json = $this->json('POST', '/api/auth/resend-activation', [
            'email'=> $this->userEmail,
        ]);

        $this->assertTrue($json->response->getStatusCode() === 401);
    }

    public function testResendActivationMethod()
    {
        //$this->expectsEvents(...);

        factory(\App\Models\UserModel::class)->create([
            'email'=> $this->userEmail,
            'activation_date' => null
        ]);
        $json = $this->json('POST', '/api/auth/resend-activation', [
            'email'=> $this->userEmail,
        ]);

        $this->assertTrue($json->response->getStatusCode() === 200);
    }
}
