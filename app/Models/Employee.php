<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'npwp',
        'nik',
        'ptkp_status',
        'position',
        'employee_status',
    ];

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function getFormattedNpwpAttribute(): string
    {
        $npwp = preg_replace('/[^0-9]/', '', $this->npwp);
        if (strlen($npwp) === 15) {
            return substr($npwp, 0, 2) . '.' .
                   substr($npwp, 2, 3) . '.' .
                   substr($npwp, 5, 3) . '.' .
                   substr($npwp, 8, 1) . '-' .
                   substr($npwp, 9, 3) . '.' .
                   substr($npwp, 12, 3);
        }
        if (strlen($npwp) === 16) {
            return substr($npwp, 0, 4) . '.' .
                   substr($npwp, 4, 4) . '.' .
                   substr($npwp, 8, 4) . '.' .
                   substr($npwp, 12, 4);
        }
        return $this->npwp;
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('npwp', 'like', "%{$search}%")
              ->orWhere('nik', 'like', "%{$search}%");
        });
    }
}
