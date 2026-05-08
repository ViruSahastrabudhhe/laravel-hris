<?php

namespace App\Console\Commands;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use Illuminate\Console\Command;

class MarkAbsentAttendances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendances:mark-absent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Attendance::whereDate('date', today())
            ->where('number_of_scans', '<', 4)
            ->update([
                'attendance_status' => AttendanceStatus::Absent->value
            ]);
    }
}
