<?php

namespace Karacraft\Roleperm\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    protected $casts = [
        'updated_at' => 'datetime:d-m-Y',
        'created_at' => 'datetime:d-m-Y',
    ];
    /**
     * Allows to sum up columns in a row
     *
     * @return $collection
     */
    public function getAttributeSum(){
        $sum = 0;
        foreach(func_get_args() as $attribute){
            $sum += $this->getAttribute($attribute);
        }
        return $sum;
    }

    //  Learn More : https://laravel.com/docs/7.x/upgrade#date-serialization
    protected function serializeDate(DateTimeInterface $date) { return $date->format('d-m-Y'); }
}
