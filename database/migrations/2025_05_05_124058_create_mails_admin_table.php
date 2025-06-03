<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mails_admin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('judul', ['Feedback', 'Request menjadi designer', 'Request menjadi kontraktor'])->default('Feedback');
            $table->enum('role', ['client', 'designer', 'contractor', 'admin'])->default('client');
            $table->text('message');
            $table->string('portfolio_path')->nullable();
            $table->timestamps();
            $table->text('reply')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mails_admin');
    }
};
