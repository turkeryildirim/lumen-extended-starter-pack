<?php
namespace App\Tests\Unit;

use App\Mail\AuthenticationLoginMail;
use App\Models\UserModel;
use App\Tests\TestCase;

/**
 * Class MailAuthenticationLoginMailTest
 *
 * @package App\Tests\Unit
 */
class MailAuthenticationLoginMailTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testBuildMethod()
    {
        $user = factory(UserModel::class)->create();
        $mail = new AuthenticationLoginMail($user);
        $mail->build();

        $this->assertTrue($mail->subject !== null);
        $this->assertTrue($mail->view !== null);
        $this->assertTrue($mail->viewData !== null);
    }
}
