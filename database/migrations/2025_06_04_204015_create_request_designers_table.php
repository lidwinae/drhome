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
        Schema::create('request_designers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('client_id')->nullable();
            $table->uuid('designer_id')->nullable();
            $table->unsignedBigInteger('purchased_design_id')->nullable();
            
            $table->string('land_size', 50);
            $table->string('land_shape', 50);
            $table->string('sun_orientation', 100);
            $table->string('wind_orientation', 100);
            $table->string('design_reference_path')->nullable();
            $table->string('province');
            $table->string('city');
            $table->decimal('budget', 16, 2)->nullable();
            $table->text('notes')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', [
                'accepted',
                'waiting',
                'rejected',
                'finished'
            ])->default('waiting');
            $table->enum('progress',['form_submitted', 'payment', 'design_start', 'design_end'])->default('form_submitted');
            $table->decimal('payment', 16, 2)->nullable();
            $table->boolean('open_acc')->default(false);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('designer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('purchased_design_id')->references('id')->on('purchased_designs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_designers');
    }
};
