<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gemini_archives', function (Blueprint $table) {
            $table->id();
            $table->text('prompt');
            $table->text('answer');
            $table->timestamps(); // Crée created_at et updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('gemini_archives');
    }
};
