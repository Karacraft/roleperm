<?php

namespace Karacraft\RolesAndPermissions\Models;

use Karacraft\RolesAndPermissions\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Method extends Base
{
    use HasFactory;

    protected $table = 'methods';
    protected $fillable = ['title','slug'];
 
}
