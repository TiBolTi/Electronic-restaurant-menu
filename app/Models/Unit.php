<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function Topping()
    {
        return $this->hasMany(Topping::class);
    }

    public function getFullNameUnit()
    {
        return $this->name;
    }


    public function getAbbreviationUnit()
    {
        $reductions = [
            'Штучно' => 'шт',
            'Граммы' => 'гр',
            'Миллилитры' => 'мл',
            'Литры' => 'л',
            'Чайные ложки' => 'ч.л',
            'Столовые ложки' => 'ст.л',
        ];

        if (array_key_exists($this->name, $reductions)) {
            return $reductions[$this->name];
        } else {
            return '';
        }
    }

    public function getUnitName()
    {
        return $this->getFullNameUnit() . ' (' . $this->getAbbreviationUnit() . ')';
    }
}
