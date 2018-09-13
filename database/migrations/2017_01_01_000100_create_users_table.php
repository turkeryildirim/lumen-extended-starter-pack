<?php
// @codingStandardsIgnoreFile

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Constants\UserRoleConstant;

/**
 * Class CreateUsersTable
 */
class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $enum = get_class_constants_value(UserRoleConstant::class);

            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique('email');
            $table->string('password')->nullable();
            $table->enum('role', $enum)->default(UserRoleConstant::USER);
            $table->boolean('status')->default(0);
            $table->longText('api_token')->nullable();
            $table->string('activation_code')->nullable();
            $table->dateTime('activation_date')->nullable();
            $table->dateTime('last_login_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('users');
    }
}
