<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $table = 'master_employee';

    protected $primaryKey = 'e_code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['e_code', 'e_name', 'e_d_code'];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'e_d_code', 'd_code');
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'e_code', 'e_code');
    }
}
