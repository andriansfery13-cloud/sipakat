<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class XmlLog extends Model
{
    protected $fillable = [
        'period_month',
        'period_year',
        'file_name',
        'file_path',
        'record_count',
        'kecamatan_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope('kecamatan', function (Builder $builder) {
            if (auth()->check() && auth()->user()->role === 'admin_kecamatan') {
                $builder->where('kecamatan_id', auth()->user()->kecamatan_id);
            }
        });

        static::creating(function ($log) {
            if (auth()->check() && auth()->user()->role === 'admin_kecamatan') {
                $log->kecamatan_id = auth()->user()->kecamatan_id;
            }
        });
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('xml.download', $this->id);
    }

    public function getPeriodLabelAttribute(): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return ($months[$this->period_month] ?? '') . ' ' . $this->period_year;
    }
}
