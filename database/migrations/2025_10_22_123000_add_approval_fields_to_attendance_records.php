<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->string('status')->default('pending')->change(); // changes status to include pending
            $table->timestamp('teacher_approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('approval_notes')->nullable();
        });
    }

    public function down()
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->string('status')->default('absent')->change();
            $table->dropColumn(['teacher_approved_at', 'approved_by', 'approval_notes']);
        });
    }
};