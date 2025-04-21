<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidesTable extends Migration
{
    public function up()
{
    Schema::create('slides', function (Blueprint $table) {
        $table->id();
        $table->string('title')->unique(); // unique if using title for upsert
        $table->text('description')->nullable();
        $table->string('image_path')->nullable();
        $table->boolean('enabled')->default(true);
        $table->timestamps();
    });
    
}

    public function down()
    {
        Schema::dropIfExists('slides');
    }
}
