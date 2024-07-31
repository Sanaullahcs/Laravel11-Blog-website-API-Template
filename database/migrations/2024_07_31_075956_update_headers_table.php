<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHeadersTable extends Migration
{
    public function up()
    {
        Schema::table('headers', function (Blueprint $table) {
            $table->string('logo_url')->nullable()->change();
            $table->string('logo_alt_text')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('headers', function (Blueprint $table) {
            $table->string('logo_url')->nullable(false)->change();
            $table->string('logo_alt_text')->nullable(false)->change();
        });
    }
}

