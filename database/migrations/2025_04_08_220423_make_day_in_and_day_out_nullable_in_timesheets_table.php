<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('timesheets', function (Blueprint $table) {
            // Cambiar a datetime y permitir null
            $table->datetime('day_in')->nullable()->change();
            $table->datetime('day_out')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('timesheets', function (Blueprint $table) {
            // Revertir a datetime sin null (si originalmente no eran null)
            $table->datetime('day_in')->nullable(false)->change();
            $table->datetime('day_out')->nullable(false)->change();
        });
    }
};
