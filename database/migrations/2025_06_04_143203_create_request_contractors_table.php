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
    Schema::create('request_contractors', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->uuid('client_id')->nullable();
        $table->uuid('contractor_id')->nullable();
        $table->unsignedBigInteger('purchased_design_id')->nullable();
        $table->string('province', 100);
        $table->string('city', 100);
        $table->string('land_size', 50);
        $table->string('land_shape', 50);
        $table->decimal('budget', 16, 2)->nullable();
        $table->text('notes')->nullable();
        $table->date('deadline')->nullable();
        $table->enum('status', [
            'accepted',
            'waiting',
            'rejected',
            'finished'
        ])->default('waiting');
        $table->enum('progress',['design_submitted', 'payment', 'construction_start', 'construction_end'])->default('design_submitted');
        $table->decimal('payment', 16, 2)->nullable();
        $table->boolean('open_acc')->default(false);
        $table->timestamps();

        $table->foreign('client_id')->references('id')->on('users')->onDelete('set null');
        $table->foreign('contractor_id')->references('id')->on('users')->onDelete('set null');
        $table->foreign('purchased_design_id')->references('id')->on('purchased_designs')->onDelete('set null');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_contractor');
    }
};
