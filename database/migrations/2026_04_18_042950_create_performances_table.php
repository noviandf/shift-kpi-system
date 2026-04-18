<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (Agen yang dinilai)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Relasi ke tabel users (Supervisor yang menilai)
            $table->foreignId('supervisor_id')->constrained('users')->cascadeOnDelete();

            $table->string('evaluation_month'); // Contoh: "April 2026"
            $table->integer('aht_raw')->nullable(); // Input detik
            $table->integer('qa_raw')->nullable();  // Input skor
            $table->integer('attendance_raw')->nullable(); // Input persentase

            $table->decimal('final_score', 5, 2)->nullable(); // Hasil hitungan otomatis
            $table->string('grade')->nullable(); // A, B, C, D
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performances');
    }
};
