<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'master_aset';

    protected $primaryKey = 'a_code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'a_code',
        'a_name',
        'a_type',
        'a_desc',
        'a_status',
    ];
}
