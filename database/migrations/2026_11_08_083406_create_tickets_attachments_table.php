<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tickets_attachments', function (Blueprint $table) {
            $table->id();

            //Relasi dengan tiket
            $table->foreignId('ticket_id')
            ->constrained('tickets')
            ->cascadeOnDelete();

            //Users untuk uplaodd files
            $table->foreignId('user_id')
            ->constrained('users')
            ->restrictOnDelete();

            //Informasi dari files
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type',100);
            $table->unsignedBigInteger('file_size');


            $table->timestamps();

            //Indexing untuk attachments tiket
            $table->index(['ticket_id', 'created_at']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tickets_attachments');
    }
};
