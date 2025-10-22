<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            // Add the class_session_id column
            $table->foreignId('class_session_id')->nullable()->after('class_id')
                ->constrained('class_sessions')->onDelete('set null');
            
            // Update status enum to include pending and rejected
            $table->dropColumn('status');
            $table->enum('status', ['pending', 'present', 'absent', 'late', 'rejected'])->after('student_id');
            
            // Add approval fields
            $table->text('approval_notes')->nullable()->after('notes');
            $table->timestamp('marked_at')->nullable()->after('approval_notes');
            $table->timestamp('approved_at')->nullable()->after('marked_at');
            $table->foreignId('approved_by')->nullable()->after('approved_at')
                ->constrained('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            // Drop approval fields
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_by', 'approved_at', 'marked_at', 'approval_notes']);
            
            // Drop session relation
            $table->dropForeign(['class_session_id']);
            $table->dropColumn('class_session_id');
            
            // Restore original status enum
            $table->dropColumn('status');
            $table->enum('status', ['present', 'absent', 'late']);
        });
    }
};