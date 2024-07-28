<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Triger extends Model
{
    use HasFactory;
    protected $table = 'trigger'; 
    
    protected $fillable = [
        'device_id',
        'res_time',
    ];
}
