<?php
namespace App\Tests\Integration;

use App\Services\PushNotificationService;
use App\Tests\TestCase;

/**
 * Class ServicePushNotificationServiceTest
 *
 * @package App\Tests\Integration
 */
class ServicePushNotificationServiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testPushNotificationServiceErrors()
    {
        $service = new PushNotificationService();

        try {
            $service->send();
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'PUSH_SERVICE_NO_TOKEN');
        }

        $service->setTo('1234567890');

        try {
            $service->send();
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'PUSH_SERVICE_NO_TITLE');
        }

        $service->setTitle('testing');

        try {
            $service->send();
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'PUSH_SERVICE_NO_MESSAGE');
        }

        $service->setMessage('message');

        try {
            $service->send();
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'SMS_SERVICE_NO_RESPONSE');
        }

        try {
            $service->setTo('');
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'PUSH_SERVICE_NO_TOKEN');
        }

        try {
            $service->setTitle('');
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'PUSH_SERVICE_NO_TITLE');
        }

        try {
            $service->setMessage('');
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'PUSH_SERVICE_NO_MESSAGE');
        }
    }

    public function testSetToMethod()
    {
        $service = new PushNotificationService();
        $service->reset();
        $current_to = $service->getTo();
        $service->setTo('1234567899');

        $this->assertTrue($current_to !== $service->getTo());
        $this->assertTrue('1234567899' === $service->getTo());
    }

    public function testSetTitleMethod()
    {
        $service = new PushNotificationService();
        $service->reset();
        $current_message = $service->getTitle();
        $service->setTitle('testing');

        $this->assertTrue($current_message !== $service->getTitle());
        $this->assertTrue('testing' === $service->getTitle());
    }

    public function testSetMessageMethod()
    {
        $service = new PushNotificationService();
        $service->reset();
        $current_message = $service->getMessage();
        $service->setMessage('testing');

        $this->assertTrue($current_message !== $service->getMessage());
        $this->assertTrue('testing' === $service->getMessage());
    }

    public function testSendMethod()
    {
        $service = new PushNotificationService();
        $sent = true;

        try {
            $service->setTo('1234567890')
            ->setTitle('title')
            ->setMessage('message')
            ->send();
        } catch (\Exception $e) {
        }

        $this->assertTrue($sent === true);
    }

    public function testResetMethod()
    {
        $service = new PushNotificationService();
        $service->setTo('1234567890')
            ->setTitle('title')
            ->setMessage('message')
            ->reset();

        $this->assertNull($service->getTo());
        $this->assertNull($service->getMessage());
        $this->assertNull($service->getTitle());
    }

    public function testGetEndPointMethod()
    {
        $service = new PushNotificationService();
        $this->assertEquals('https://myprovider.push', $service->getEndPoint());
    }
}
