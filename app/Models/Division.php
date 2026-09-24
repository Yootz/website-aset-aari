<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'master_division';
    protected $primaryKey = 'd_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['d_code', 'd_name', 'd_desc'];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'e_d_code', 'd_code');
    }
}