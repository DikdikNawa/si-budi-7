<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cultures', function (Blueprint $table) {
            $table->id('culture_id');
            $table->string('name');
            $table->text('description');
            $table->enum('category', [
                'Bahasa',
                'Tradisi Lisan',
                'Manuskrip',
                'Adat Istiadat',
                'Ritus',
                'Pengetahuan Tradisional',
                'Teknologi Tradisional',
                'Seni',
                'Permainan Rakyat',
                'Olahraga Tradisional',
                'Cagar Budaya'
            ]);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('submitted_by')->nullable();
            $table->string('curated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cultures');
    }
};
