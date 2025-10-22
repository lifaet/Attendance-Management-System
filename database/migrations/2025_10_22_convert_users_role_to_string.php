<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

return new class extends Migration
{
    public function up(): void
    {
        // Some DB drivers (sqlite used in tests) don't support DROP COLUMN / CHANGE
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'sqlite') {
            // Skip destructive conversion on sqlite (test DB). Leave enum as-is.
            return;
        }

        // Add a temporary column, copy values, then replace the enum column with a varchar
        DB::statement("ALTER TABLE `users` ADD COLUMN `role_new` VARCHAR(255) NOT NULL DEFAULT 'student'");
        DB::statement("UPDATE `users` SET `role_new` = `role` WHERE `role` IS NOT NULL");
        DB::statement("ALTER TABLE `users` DROP COLUMN `role`");
        DB::statement("ALTER TABLE `users` CHANGE `role_new` `role` VARCHAR(255) NOT NULL DEFAULT 'student'");
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'sqlite') {
            // Nothing to do for sqlite
            return;
        }

        // Attempt to convert back to enum with common values
        DB::statement("ALTER TABLE `users` ADD COLUMN `role_old` ENUM('admin','teacher','student') NOT NULL DEFAULT 'student'");
        DB::statement("UPDATE `users` SET `role_old` = `role` WHERE `role` IS NOT NULL");
        DB::statement("ALTER TABLE `users` DROP COLUMN `role`");
        DB::statement("ALTER TABLE `users` CHANGE `role_old` `role` ENUM('admin','teacher','student') NOT NULL DEFAULT 'student'");
    }
};