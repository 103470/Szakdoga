<?php

namespace App\Models\Brands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\Brands\Type;
use App\Models\Brands\Vintage;
use App\Models\FuelType;
use App\Models\PartVehicle;

class BrandModel extends Model
{
    use SoftDeletes;

    protected $table = 'brand_models';

    protected $fillable = [
        'type_id',
        'name',
        'ccm',
        'kw_hp',
        'engine_type',
        'fuel_type_id',
        'year_from',
        'month_from',
        'year_to',
        'month_to',
        'frame',
        'body_type',
        'updated_by',
        'deleted_by'
    ];

    public function getFullNameAttribute()
    {
        $fuel = $this->fuelType ? Str::title($this->fuelType->name) . ' ' : '';
        return $fuel . $this->name;
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function brand()
    {
        return $this->type ? $this->type->brand() : null;
    }

    public function vintage()
    {
        return $this->belongsTo(Vintage::class, 'frame', 'frame');
    }

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class);
    }

    public function partVehicles()
    {
        return $this->hasMany(PartVehicle::class, 'unique_code', 'unique_code');
    }

    public function getYearRangeAttribute()
    {
        return sprintf(
            '%d/%02d - %d/%02d',
            $this->year_from,
            $this->month_from,
            $this->year_to,
            $this->month_to
        );
    }

    public function getCcmFormattedAttribute()
    {
        return $this->ccm ? $this->ccm . ' ccm' : null;
    }

    public function getKwLeFormattedAttribute()
    {
        if (!$this->kw_hp) return null;

        $parts = explode('/', $this->kw_hp);
        if (count($parts) === 2) {
            return $parts[0] . ' kW / ' . $parts[1] . ' LE';
        }

        return $this->kw_hp;
    }

    public function belongsToVintage(Vintage $vintage): bool
    {
        $yearMatch = $this->year_from <= $vintage->year_to && $this->year_to >= $vintage->year_from;
        $frameMatch = $vintage->frame ? $this->frame === $vintage->frame : true;

        return $yearMatch && $frameMatch;
    }

    public function scopeForVintage($query, $vintage)
    {
        return $query->where('type_id', $vintage->type_id)
            ->when($vintage->frame, function ($q) use ($vintage) {
                $q->where('frame', $vintage->frame);
            })
            ->when(!empty($vintage->body_type), function ($q) use ($vintage) {
                $q->where('body_type', $vintage->body_type);
            });
    }

    public static function groupedByFuel($models)
    {
        $benzin = $models->filter(fn($m) => $m->fuelType && $m->fuelType->name === 'benzin')->sortBy('name');
        $dizel  = $models->filter(fn($m) => $m->fuelType && $m->fuelType->name === 'dízel')->sortBy('name');

        return [
            'Benzin' => $benzin,
            'Dízel'  => $dizel,
        ];
    }

    protected static function booted()
    {
        static::saving(function ($model) {

            if (empty($model->name) && $model->type) {
                $model->name = $model->type->name;
            }

            $fuelTypeName = $model->fuelType ? $model->fuelType->name : 'unknown';

            if ($model->frame) {
                $validFrames = Vintage::pluck('frame')->filter()->toArray();
                if (!in_array($model->frame, $validFrames)) {
                    throw new \Exception("A frame érték érvénytelen: {$model->frame}");
                }
            }

            if (empty($model->unique_code)) {
                $input = $model->name . '_' .
                        $model->engine_type . '_' .
                        $model->year_from . '_' .
                        $model->month_from . '_' .
                        $model->year_to . '_' .
                        $model->month_to . '_' .
                        $fuelTypeName;

                if ($model->ccm) {
                    $input .= '_' . $model->ccm;
                }

                if ($model->kw_hp) {
                    $input .= '_' . $model->kw_hp;
                }

                if ($model->frame) {
                    $input .= '_' . $model->frame;
                }

                $hash = abs(crc32($input));
                $model->unique_code = 1000 + ($hash % 9000000);
            }

            if (empty($model->slug)) {
                $model->slug = Str::slug((string) $model->unique_code);
            }
        });
    }
}
