<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo 'Users: ' . \DB::table('users')->count() . "\n";
echo 'Classes: ' . \DB::table('classes')->count() . "\n";
echo 'Class_student: ' . \DB::table('class_student')->count() . "\n";
echo 'Attendance: ' . \DB::table('attendance_records')->count() . "\n";
