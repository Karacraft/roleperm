<?php

namespace Karacraft\RolesAndPermissions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Karacraft\RolesAndPermissions\Support\Config;

class Method extends Base
{
    use HasFactory;

    protected $fillable = ['title','slug'];

    public function getTable(): string
    {
        return Config::table('methods');
    }
}
