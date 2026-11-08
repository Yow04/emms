<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            //Relation dengan tabel lain
            $table->foreignId('user_id')
            ->constrained('users')
            ->restrictOnDelete();

            $table->foreignId('assigned_to')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

            $table->foreignId('category_id')
            ->constrained('categories')
            ->restrictOnDelete();

            //Informasi tiket
            $table->string('ticket_number',30)->unique();
            $table->string('subject', 160);
            $table->text('description');

            //Status dan Prioritas
            $table->string('priority')->default('medium');
            $table->string('status')->default('open');

            $table->timestamps();

            //Index untuk filter dan searching
            $table->index('status');
            $table->index('priority');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
