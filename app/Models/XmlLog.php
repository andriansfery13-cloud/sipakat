<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XmlLog extends Model
{
    protected $fillable = [
        'period_month',
        'period_year',
        'file_name',
        'file_path',
        'record_count',
    ];

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
