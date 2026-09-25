<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function details(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class, 'a_code', 'a_code');
    }
}
