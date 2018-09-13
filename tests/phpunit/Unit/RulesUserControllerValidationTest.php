<?php
namespace App\Tests\Unit;

use App\Constants\UserRoleConstant;
use App\Tests\TestCase;

/**
 * Class RulesUserControllerValidationTest
 *
 * @package App\Tests\Unit
 */
class RulesUserControllerValidationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->rules = null;
        $this->validator = $this->app['validator'];
    }

    public function testCreateRules()
    {
        $file = app_path('app/Http/Validations/UserController').'/create.php';
        $this->rules = require $file;

        $this->assertFalse($this->validateInput('email', 'aaa'));
        $this->assertTrue($this->validateInput('email', 'aaa@qq.com'));

        $this->assertFalse($this->validateInput('password', '12345'));
        $this->assertTrue($this->validateInput('password', 'çş+*9AA'));

        $this->assertFalse($this->validateInput('first_name', 'a'));
        $this->assertTrue($this->validateInput('first_name', 'ab'));

        $this->assertFalse($this->validateInput('last_name', 'a'));
        $this->assertTrue($this->validateInput('last_name', 'ab'));

        $this->assertFalse($this->validateInput('role', 'a'));
        $this->assertTrue($this->validateInput('role', UserRoleConstant::USER));
        $this->assertTrue($this->validateInput('role', UserRoleConstant::ADMIN));

        $this->assertFalse($this->validateInput('status', null));
        $this->assertFalse($this->validateInput('status', 'aaa'));
        $this->assertFalse($this->validateInput('status', 'false'));
        $this->assertFalse($this->validateInput('status', 'true'));
        $this->assertTrue($this->validateInput('status', ''));
        $this->assertTrue($this->validateInput('status', true));
        $this->assertTrue($this->validateInput('status', false));
    }

    public function testUpdateRules()
    {
        $file = app_path('app/Http/Validations/UserController').'/update.php';
        $this->rules = require $file;

        $this->assertFalse($this->validateInput('email', 'aaa'));
        $this->assertTrue($this->validateInput('email', 'aaa@qq.com'));

        $this->assertFalse($this->validateInput('password', '12345'));
        $this->assertTrue($this->validateInput('password', 'çş+*9AA'));

        $this->assertFalse($this->validateInput('first_name', 'a'));
        $this->assertTrue($this->validateInput('first_name', 'ab'));

        $this->assertFalse($this->validateInput('last_name', 'a'));
        $this->assertTrue($this->validateInput('last_name', 'ab'));

        $this->assertFalse($this->validateInput('role', 'a'));
        $this->assertTrue($this->validateInput('role', UserRoleConstant::USER));
        $this->assertTrue($this->validateInput('role', UserRoleConstant::ADMIN));

        $this->assertFalse($this->validateInput('status', null));
        $this->assertFalse($this->validateInput('status', 'aaa'));
        $this->assertFalse($this->validateInput('status', 'false'));
        $this->assertFalse($this->validateInput('status', 'true'));
        $this->assertTrue($this->validateInput('status', ''));
        $this->assertTrue($this->validateInput('status', true));
        $this->assertTrue($this->validateInput('status', false));
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
