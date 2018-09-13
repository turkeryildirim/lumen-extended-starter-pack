<?php
namespace App\Tests\Unit;

use App\Constants\UserStatusConstant;
use App\Models\UserModel;
use App\Services\EmailService;
use App\Tests\TestCase;

/**
 * Class HelperTest
 *
 * @package App\Tests\Unit
 */
class HelperTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testGetClassConstantsFunction()
    {
        $result = get_class_constants('');
        $this->assertTrue($result === false);

        $result = get_class_constants(EmailService::class);
        $this->assertTrue($result === false);

        $result = get_class_constants(UserStatusConstant::class);
        $this->assertTrue(is_array($result) === true);

        foreach ($result as $item) {
            $val = constant('\App\Constants\UserStatusConstant::'.$item);
            $this->assertNotNull($val);
        }
    }

    public function testGetClassConstantsValueFunction()
    {
        $result = get_class_constants_value(EmailService::class);
        $this->assertTrue($result === false);

        $result = get_class_constants_value(UserStatusConstant::class);
        $this->assertTrue(is_array($result) === true);

        $this->assertEquals(UserStatusConstant::ACTIVE, $result[0]);
        $this->assertEquals(UserStatusConstant::PASSIVE, $result[1]);
    }

    public function testConfigPathFunction()
    {
        $this->assertEquals(app()->basePath() . DIRECTORY_SEPARATOR. 'config', config_path());
        $this->assertEquals(app()->basePath() . DIRECTORY_SEPARATOR. 'config', configPath());
    }

    public function testAppPathFunction()
    {
        $this->assertEquals(app()->basePath(), app_path());
    }

    public function testConvertToDatetimeFunction()
    {
        $this->assertTrue(convert_to_datetime('abc') === false);
        $this->assertTrue(convert_to_datetime('21/12/2012') === false);
        $this->assertTrue(convert_to_datetime('21-12-2012') === '2012-12-21 00:00:00');
        $this->assertTrue(convert_to_datetime('21-12-2012', 'Y/m') === '2012/12');
    }

    public function testToObjectFunction()
    {
        $object = to_object([]);
        $this->assertTrue(null === $object);

        $object = to_object([1,2,3]);
        $this->assertTrue(null === $object);

        $array = ['a' => 'b'];
        $object = to_object($array);
        $this->assertTrue($array['a'] === $object->a);

        $array = ['a' => 'b',1,2];
        $object = to_object($array);
        $this->assertTrue($array['a'] === $object->a);
        $array = ['a' => 'b', 'c' => ['x' => 'y']];
        $object = to_object($array);
        $this->assertTrue($array['c']['x'] === $object->c->x);
    }

    public function testCreateApiTokenFunction()
    {
        $user = factory(UserModel::class)->create();
        $token = create_authorization_token($user);

        $this->assertTrue($user->api_token !== $token);
    }
}
