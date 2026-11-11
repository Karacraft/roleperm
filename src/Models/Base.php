<?php

namespace Karacraft\RolesAndPermissions\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    /** No Timestamps */
    public $timestamps = false;

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug) && ! empty($model->title)) {
                $model->slug = $model->title;
            }
        });
    }


    public function getAttributeSum(): float|int
    {
        $sum = 0;
        foreach(func_get_args() as $attribute){
            $sum += $this->getAttribute($attribute);
        }
        return $sum;
    }

    public function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = ucwords($value);
    }
    public function setSlugAttribute(string $value): void
    {
        $slug = trim($value); // trim the string
        $slug= preg_replace('/[^a-zA-Z0-9 -]/','',$slug ); // only take alphanumerical characters, but keep the spaces and dashes too...
        $slug= str_replace(' ','_', $slug); // replace spaces by dashes
        $slug= strtolower($slug);  // make it lowercase
        $this->attributes['slug'] = $slug;
    }
    
    public function setMethodAttribute(string $value): void
    {
        $this->attributes['method'] = ucwords($value);
    }
    public function setModelAttribute(string $value): void
    {
        $this->attributes['model'] = ucwords($value);
    }

    //  Learn More : https://laravel.com/docs/7.x/upgrade#date-serialization
    protected function serializeDate(DateTimeInterface $date): string { return $date->format('d-m-Y'); }
}
