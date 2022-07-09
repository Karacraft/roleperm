<?php

namespace Karacraft\RolesAndPermissions\Traits;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Str;

/**
 * NOTE: NOT USING ANYWHERE
 */
trait RolesAndPermissionsTrait
{
    //  Route Binding - Learn More : https://laravel.com/docs/7.x/routing#explicit-binding
    // public function getRouteKeyName(){return 'slug';}
    /** SETTERS */
    //  Set Slug Value
    public function setSlugAttribute($value)
    {
        $str = preg_replace("'@[^A-Za-z0-9\w\ ]@'", "", $value);
        $this->attributes['slug']= Str::slug($str,'_');
    }
    //  Set Name to Proper Case
    // public function setNameAttribute($value){$this->attributes['name'] = \ucwords($value);}
    // public function setCreatedAtAttribute($value){$this->attributes['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s',$value);}
    protected function serializeDate(DateTimeInterface $date){return $date->format('d-m-Y H:i:s');}
    //*****************-GETTERS-***********************************/
    public function getCreatedAtAttribute($value){return Carbon::createFromFormat('d-m-Y H:i:s',$value)->format('d-m-Y H:i:s');}
    public function getUpdatedAtAttribute($value){return Carbon::createFromFormat('d-m-Y H:i:s',$value)->format('d-m-Y H:i:s');}
    public function getTitleAttribute($value){ return Str::ucfirst($value);}
    /*************************-SETTERS-*****************/
    
    public function setTitleAttribute($value) { $this->attributes['title'] = Str::lower($value);}
    public function setDescriptionAttribute($value) { $this->attributes['description'] = Str::lower($value);}
}