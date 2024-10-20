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

    public function scopeSearch($query, $val){
        return $query->where(function($q) use ($val) {
            $q->where('userid', 'like', '%'.$val.'%')
              ->orWhere('dev_name', 'like', '%'.$val.'%')
              ->orWhere('dev_serial', 'like', '%'.$val.'%')
              ->orWhere('dev_address', 'like', '%'.$val.'%')
              ->orWhere('dev_number', 'like', '%'.$val.'%');
        });
    }
}
