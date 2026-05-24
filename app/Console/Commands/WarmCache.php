<?php

namespace App\Console\Commands;

use App\Models\Position;
use App\Models\Department;
use App\Models\WorkSchedule;
use App\Services\Employee\EmployeeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WarmCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm
                {--employees : Warm employees cache}
                {--positions : Warm positions cache}
                {--departments : Warm departments cache}
                {--workSchedules : Warm work schedules cache}
                {--periods : Warm periods cache}
                {--all : Warm all caches}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-populate the cache with frequently accessed data';

    protected EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService) {
        parent::__construct();
        $this->employeeService = $employeeService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = microtime(true);
        $itemsCached = 0;

        $this->info('Starting cache warming...');

        if ($this->option('all') || $this->option('positions')) {
            $itemsCached += $this->warmPositionCaches();
        }

        if ($this->option('all') || $this->option('departments')) {
            $itemsCached += $this->warmDepartmentCaches();
        }

        if ($this->option('all') || $this->option('workSchedules')) {
            $itemsCached += $this->warmWorkScheduleCaches();
        }

        if ($this->option('all') || $this->option('employees')) {
            $itemsCached += $this->warmEmployeeCaches();
        }

        $duration = round(microtime(true) - $startTime, 2);
        $this->info("Cache warming complete! Cached {$itemsCached} items in {$duration}s");

        return Command::SUCCESS;
    }

    protected function warmEmployeeCaches() {
        $this->info('Caching employees...');

        $this->employeeService->getAll();
        $this->employeeService->getEmployeeStats();

        return 1;
    }

    protected function warmPositionCaches(): int {
        $this->info('Caching positions...');

        $positions = Position::get();

        Cache::put(
            'position:all',
            $positions,
            now()->addDay()
        );

        $totalPositions = Position::count();
        $totalFilled = Position::pluck('id')->filter()->unique()->count();
        $totalVacants = max(0, $totalPositions - $totalFilled);

        Cache::remember('position_stat:all', now()->addHour(), fn() => [
            'total_positions'    => $totalPositions,
            'total_filled'       => $totalFilled,
            'total_vacant'      => $totalVacants,
        ]);

        return 1;
    }

    protected function warmDepartmentCaches(): int {
        $this->info('Caching departments...');

        $departments = Department::get();

        Cache::put(
            'department:all',
            $departments,
            now()->addDay()
        );

        Cache::remember('department_stat:all', now()->addHour(), fn() => [
            'total_departments'  => Department::count(),
            'total_active'       => Department::where('is_active', true)->count(),
            'total_inactive'      => Department::where('is_active', false)->count(),
        ]);

        return 1;
    }

    protected function warmWorkScheduleCaches(): int {
        $this->info('Caching work schedules...');

        $workSchedules = WorkSchedule::get();

        Cache::put(
            'workSchedule:all',
            $workSchedules,
            now()->addDay()
        );

        return 1;
    }
}
