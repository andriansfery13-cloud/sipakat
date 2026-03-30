<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'gross_salary',
        'allowance',
        'bonus',
        'total_income',
        'pph21',
        'kecamatan_id',
    ];

    protected $casts = [
        'gross_salary' => 'decimal:2',
        'allowance' => 'decimal:2',
        'bonus' => 'decimal:2',
        'total_income' => 'decimal:2',
        'pph21' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::addGlobalScope('kecamatan', function (Builder $builder) {
            if (auth()->check() && auth()->user()->role === 'admin_kecamatan') {
                $builder->where('kecamatan_id', auth()->user()->kecamatan_id);
            }
        });

        static::creating(function ($payroll) {
            if (auth()->check() && auth()->user()->role === 'admin_kecamatan') {
                $payroll->kecamatan_id = auth()->user()->kecamatan_id;
            }
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeForPeriod($query, $month, $year)
    {
        if ($month) {
            $query->where('month', $month);
        }
        if ($year) {
            $query->where('year', $year);
        }
        return $query;
    }

    public function getFormattedTotalIncomeAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->total_income, 0, ',', '.');
    }

    public function getFormattedPph21Attribute(): string
    {
        return 'Rp ' . number_format((float) $this->pph21, 0, ',', '.');
    }
}
