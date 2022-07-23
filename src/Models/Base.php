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
   
    public function getAttributeSum(){
        $sum = 0;
        foreach(func_get_args() as $attribute){
            $sum += $this->getAttribute($attribute);
        }
        return $sum;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords($value);
    }
    public function setSlugAttribute($value)
    {
        $slug = trim($value); // trim the string
        $slug= preg_replace('/[^a-zA-Z0-9 -]/','',$slug ); // only take alphanumerical characters, but keep the spaces and dashes too...
        $slug= str_replace(' ','_', $slug); // replace spaces by dashes
        $slug= strtolower($slug);  // make it lowercase
        $this->attributes['slug'] = $slug;
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
