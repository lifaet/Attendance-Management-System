<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->foreignId('class_session_id')->nullable()->constrained('class_sessions')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropForeignId('attendance_records_class_session_id_foreign');
            $table->dropColumn('class_session_id');
        });
    }
};