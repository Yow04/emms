<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('tickets_histories', function (Blueprint $table) {
            $table->id();

            //Tiket yang dimanipulasi
            $table->foreignId('ticket_id')
            ->constrained('tickets')
            ->cascadeOnDelete();

            //Users Activity on Tickets
            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

            //Updating status
            $table->string('old_status', 30)->nullable();
            $table->string('new_status', 30)->nullable();

            //Perubahan assignment
            $table->foreignId('old_assigned_to')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

            $table->foreignId('new_assigned_to')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

            //Keterangan Activity
            $table->text('description')->nullable();

            //Histori hanya tercatat pada pembuatan
            $table->timestamp('created_at')->useCurrent();

            //Indexing untuk mengurutkan data histori
            $table->index(['ticket_id', 'created_at']);
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('tickets_histories');
    }
};
