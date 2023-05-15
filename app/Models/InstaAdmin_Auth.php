<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstaAdmin_Auth extends Model
{
    protected $table = 'instaadmin_auth';

    public $timestamps = false;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name','email'
    ];

}
