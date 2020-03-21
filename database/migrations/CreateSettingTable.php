<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $table = config('admin.extensions.setting.table', 'setting');
        Schema::connection($connection)->create($table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->unique();
            $table->text('val');
            $table->string('label');
            $table->text('options');
            $table->string('help');
            $table->string('type');
            $table->string('tag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $table = config('admin.extensions.config.table', 'admin_config');

        Schema::connection($connection)->dropIfExists($table);
    }
}
