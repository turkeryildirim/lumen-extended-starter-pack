<?php
// @codingStandardsIgnoreFile

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Constants\UserGenderConstant;

/**
 * Class CreateUserMetaTable
 */
class CreateUserMetaTable extends Migration
{
    public function up()
    {
        Schema::create('user_meta', function (Blueprint $table) {
            $enum = get_class_constants_value(UserGenderConstant::class);

            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
	        $table->unsignedBigInteger('user_id')->index('user_meta_user_id');
            $table->enum('gender', $enum)->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->dateTime('birth_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('user_meta', function (Blueprint $table) {
            $table->foreign('user_id', 'user_meta_user_id_fk')
                ->references('id')
                ->on('users');
        });
    }

    public function down()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            $table->dropForeign('user_meta_user_id_fk');
        });

        Schema::drop('user_meta');
    }
}
