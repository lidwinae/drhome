<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Drop kolom `name` jika ada (di luar closure!)
        if (Schema::hasColumn('designers', 'name')) {
            Schema::table('designers', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }

        // Tambahkan kolom user_id jika belum ada
        if (!Schema::hasColumn('designers', 'user_id')) {
            Schema::table('designers', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            });
        } else {
            // Jika sudah ada, coba drop foreign key lama jika ada (safe)
            try {
                Schema::table('designers', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                });
            } catch (\Throwable $e) {
                // Foreign key belum ada atau error
            }

            // Ulangi buat foreign key
            Schema::table('designers', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // Tambahkan CHECK CONSTRAINT (opsional, bukan production)
        if (app()->environment() !== 'production') {
            try {
                DB::statement('ALTER TABLE designers ADD CONSTRAINT check_designer_role CHECK (
                    user_id IS NULL OR EXISTS (
                        SELECT 1 FROM users WHERE users.id = designers.user_id AND users.role = "designer"
                    )
                )');
            } catch (\Throwable $e) {
                logger()->error('Constraint check_designer_role gagal: '.$e->getMessage());
            }
        }
    }

    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE designers DROP CHECK check_designer_role');
        } catch (\Throwable $e) {
            //
        }

        Schema::table('designers', function (Blueprint $table) {
            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $e) {
                //
            }

            $table->dropColumn('user_id');

            if (!Schema::hasColumn('designers', 'name')) {
                $table->string('name')->nullable();
            }
        });
    }
};
