<?php
namespace App\Tests\Database;

use App\Models\UserMetaModel;
use App\Tests\TestCase;

/**
 * Class FactoryUserMetaFactoryTest
 *
 * @package App\Tests\Database
 */
class FactoryUserMetaFactoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testUserMetaFactory()
    {
        $meta = factory(UserMetaModel::class)->make()->toArray();

        $this->assertArrayHasKey('user_id', $meta);
        $this->assertArrayHasKey('gender', $meta);
        $this->assertArrayHasKey('phone', $meta);
        $this->assertArrayHasKey('city', $meta);
        $this->assertArrayHasKey('address', $meta);
        $this->assertArrayHasKey('birth_date', $meta);
    }
}
