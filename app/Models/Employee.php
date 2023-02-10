<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'company_id','email','phone'
    ];
    public function companies(){
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
    
}
