<?php

namespace app\Models;

use framework\core\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];
}