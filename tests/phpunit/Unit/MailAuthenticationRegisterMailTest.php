<?php
namespace App\Tests\Unit;

use App\Mail\AuthenticationRegisterMail;
use App\Models\UserModel;
use App\Tests\TestCase;

/**
 * Class MailAuthenticationRegisterMailTest
 *
 * @package App\Tests\Unit
 */
class MailAuthenticationRegisterMailTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testBuildMethod()
    {
        $user = factory(UserModel::class)->create();
        $mail = new AuthenticationRegisterMail($user);
        $mail->build();

        $this->assertTrue($mail->subject !== null);
        $this->assertTrue($mail->view !== null);
        $this->assertTrue($mail->viewData !== null);
    }
}
