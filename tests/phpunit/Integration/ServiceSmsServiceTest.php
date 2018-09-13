<?php
namespace App\Tests\Integration;

use App\Services\SmsService;
use App\Tests\TestCase;

/**
 * Class ServiceSmsServiceTest
 *
 * @package App\Tests\Integration
 */
class ServiceSmsServiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testSmsServiceErrors()
    {
        $service = new SmsService();

        try {
            $service->send();
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'SMS_SERVICE_NO_PHONE');
        }

        $service->setTo('1234567890');

        try {
            $service->send();
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'SMS_SERVICE_NO_MESSAGE');
        }

        $service->setMessage('testing');

        try {
            $service->send();
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'SMS_SERVICE_NO_RESPONSE');
        }

        try {
            $service->setTo('1');
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'SMS_SERVICE_INVALID_PHONE');
        }

        try {
            $service->setTo('1234567890123456789');
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'SMS_SERVICE_INVALID_PHONE');
        }

        try {
            $service->setMessage('<a href="google.com"></a>');
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'SMS_SERVICE_EMPTY_MESSAGE');
        }
    }

    public function testSetToMethod()
    {
        $service = new SmsService();
        $service->reset();
        $current_to = $service->getTo();
        $service->setTo('1234567899');

        $this->assertTrue($current_to !== $service->getTo());
        $this->assertTrue('01234567899' === $service->getTo());
    }

    public function testResetMethod()
    {
        $service = new SmsService();
        $service->setTo('1234567890')
            ->setMessage('test')
            ->reset();

        $this->assertNull($service->getTo());
        $this->assertNull($service->getMessage());
    }

    public function testSetMessageMethod()
    {
        $service = new SmsService();
        $service->reset();
        $current_message = $service->getMessage();
        $service->setMessage('<a href="google.com">test&</a>');

        $this->assertTrue($current_message !== $service->getMessage());
        $this->assertTrue('test&amp;' === $service->getMessage());
    }

    public function testSendMethod()
    {
        $service = new SmsService();
        $sent = true;

        try {
            $service->setTo('1234567890')
            ->setMessage('test')
            ->send();
        } catch (\Exception $e) {
        }

        $this->assertTrue($sent === true);
    }

    public function testGetEndPointMethod()
    {
        $service = new SmsService();
        $this->assertEquals('https://myprovider.sms', $service->getEndPoint());
    }
}
