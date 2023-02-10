<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $fillable = [
        'title', 'description', 'company_id','user_id','body','employee_id'
    ];

    public function users(){
        return $this->hasMany(User::class, 'user_id');
    }
    public function employees(){
        return $this->hasMany(Employee::class  , 'employee_id');
    }
    public function companies(){
        return $this->hasMany(Company::class  , 'company_id');
    }
}
