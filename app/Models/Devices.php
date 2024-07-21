<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    use HasFactory;

    protected $fillable = [
        'userid',
        'dev_name',
        'dev_serial',
        'dev_address',
        'dev_number',
    ];
}
