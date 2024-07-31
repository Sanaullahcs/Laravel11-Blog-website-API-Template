<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeadersTable extends Migration
{
    public function up()
    {
        Schema::create('headers', function (Blueprint $table) {
            $table->id();
            $table->string('logo_url')->nullable(); // Ensure this is nullable if you want it to be optional
            $table->string('logo_alt_text')->nullable(); // Ensure this is nullable if you want it to be optional
            $table->json('navigation_menu');
            $table->json('search_bar');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('headers');
    }
}
