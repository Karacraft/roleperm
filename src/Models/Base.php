<?php

namespace Karacraft\RolesAndPermissions\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    /** No Timestamps */
    public $timestamps = false;
    /** Cast datetime to d-m-Y */
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

    /**
     * Set Title Attribute & Slug
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords($value);
    }
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = strtolower(trim(preg_replace("'@[^A-Za-z0-9\w\ ]@'", '_', $value)));
    }
    
    public function setMethodAttribute($value)
    {
        $this->attributes['method'] = ucwords($value);
    }
    public function setModelAttribute($value)
    {
        $this->attributes['model'] = ucwords($value);
    }

    //  Learn More : https://laravel.com/docs/7.x/upgrade#date-serialization
    protected function serializeDate(DateTimeInterface $date) { return $date->format('d-m-Y'); }
}
