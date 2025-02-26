<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('manga_pages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('comic_id')->constrained()->onDelete('cascade'); // Foreign key to comics table
        $table->string('file_path');  // File path of the manga page
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('manga_pages');
}
};
