<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'kecamatan_id'];

    protected static function booted()
    {
        static::addGlobalScope('kecamatan', function (Builder $builder) {
            if (auth()->check() && auth()->user()->role === 'admin_kecamatan') {
                $builder->where(function ($q) {
                    $q->where('kecamatan_id', auth()->user()->kecamatan_id)
                      ->orWhereNull('kecamatan_id');
                });
            }
        });

        static::creating(function ($setting) {
            if (auth()->check() && auth()->user()->role === 'admin_kecamatan') {
                $setting->kecamatan_id = auth()->user()->kecamatan_id;
            }
        });
    }

    public static function getValue(string $key, $default = null): ?string
    {
        // Karena Global Scope membolehkan kecamatan_id = milik admin ATAU null
        // Kita order by kecamatan_id desc agar setting khusus tenant (bukan null) yg diambil pertama.
        $setting = static::where('key', $key)
                    ->orderBy('kecamatan_id', 'desc')
                    ->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue(string $key, $value): void
    {
        $attributes = ['key' => $key];
        
        if (auth()->check() && auth()->user()->role === 'admin_kecamatan') {
            $attributes['kecamatan_id'] = auth()->user()->kecamatan_id;
        }

        static::updateOrCreate($attributes, ['value' => $value]);
    }
}
