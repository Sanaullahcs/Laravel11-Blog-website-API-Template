<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootersTable extends Migration
{
    public function up()
    {
        Schema::create('footers', function (Blueprint $table) {
            $table->id();
            $table->text('contact_information');
            $table->text('quick_links');
            $table->text('social_media_links');
            $table->text('newsletter_signup');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('footers');
    }
}
