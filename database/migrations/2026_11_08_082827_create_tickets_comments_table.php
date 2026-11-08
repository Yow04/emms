<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('tickets_comments', function (Blueprint $table) {
            $table->id();

            //Relasi tiket
            $table->foreignId('ticket_id')
            ->constrained('tickets')
            ->cascadeOnDelete();

            //Relasi ke users
            $table->foreignId('user_id')
            ->constrained('users')
            ->restrictOnDelete();


            //Untuk Komentar
            $table->text('comments');


            $table->timestamps();

            //Indexing untuk komentar
            $table->index(['ticket_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets_comments');
    }
};
