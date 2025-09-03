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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->constrained();
            $table->foreignId('user_id')->constrained(); // dokter
            $table->text('diagnosis');
            $table->text('prescription');
            $table->text('canvas_image');
            $table->enum('status', ['baru', 'proses', 'selesai']);
            $table->timestamps(); // created_at // waktu dibuat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
