<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class QrAttendanceScan extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'qr_code_hash',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && now()->isAfter($this->expires_at);
    }

    #[Scope]
    protected function currentMonth(Builder $query): void {
        $query->whereYear('created_at', '=', Carbon::now()->year)
            ->whereMonth('created_at', '=', Carbon::now()->month);
    }

    #[Scope]
    protected function betweenCurrentMonth(Builder $query): void {
        $query->whereDate('created_at', '>=', Carbon::now()->startOfMonth())
            ->whereDate('created_at', '<=', Carbon::now()->endOfMonth());
    }
}
