<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('culture_media', function (Blueprint $table) {
            $table->id('media_id');
            $table->foreignId('culture_id')->constrained('cultures', 'culture_id')->onDelete('cascade');
            $table->enum('type', ['photo', 'video', 'audio']);
            $table->string('url');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('culture_media');
    }
};
