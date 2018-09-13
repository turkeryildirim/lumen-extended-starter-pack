<?php
namespace App\Tests\Unit;

use App\Constants\UserRoleConstant;
use App\Tests\TestCase;

/**
 * Class RulesAuthenticationControllerValidationTest
 *
 * @package App\Tests\Unit
 */
class RulesAuthenticationControllerValidationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->rules = null;
        $this->validator = $this->app['validator'];
    }

    public function testLoginRules()
    {
        $file = app_path('app/Http/Validations/AuthenticationController').'/login.php';
        $this->rules = require $file;

        $this->assertFalse($this->validateInput('email', ''));
        $this->assertFalse($this->validateInput('email', null));
        $this->assertFalse($this->validateInput('email', 'aaa'));
        $this->assertTrue($this->validateInput('email', 'aaa@qq.com'));

        $this->assertFalse($this->validateInput('password', ''));
        $this->assertFalse($this->validateInput('password', null));
        $this->assertFalse($this->validateInput('password', '12345'));
        $this->assertTrue($this->validateInput('password', 'çş+*9AA'));
    }

    protected function getInputValidator($field, $value)
    {
        return $this->validator->make(
            [$field => $value],
            [$field => $this->rules[$field]]
        );
    }

    protected function validateInput($field, $value)
    {
        return $this->getInputValidator($field, $value)->passes();
    }
}
