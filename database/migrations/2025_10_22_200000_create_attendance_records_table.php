<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('class_session_id')->nullable();
            $table->unsignedBigInteger('student_id');

            // status: pending, present, absent, late, rejected
            $table->enum('status', ['pending', 'present', 'absent', 'late', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamp('marked_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->timestamps();

            // Foreign keys intentionally omitted to avoid ordering issues during migrations.
            // You may add FK constraints in a later migration if desired.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
