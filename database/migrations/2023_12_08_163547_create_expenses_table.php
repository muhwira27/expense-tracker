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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama pengeluaran
            $table->string('type'); // Tipe pengeluaran
            $table->decimal('amount', 10, 2); // Jumlah pengeluaran dengan 2 digit desimal
            $table->text('description')->nullable(); // Deskripsi, opsional
            $table->date('date'); // Tanggal pengeluaran
            $table->string('receipt_image')->nullable(); // Gambar struk, opsional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
