<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDesignerIdToDesignsTable extends Migration
{
    public function up()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->unsignedBigInteger('designer_id')->after('id'); // foreign key column
            $table->foreign('designer_id')->references('id')->on('designers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->dropForeign(['designer_id']);
            $table->dropColumn('designer_id');
        });
    }
}
