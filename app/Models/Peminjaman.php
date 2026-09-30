<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $primaryKey = 'p_code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'p_code',
        'e_code',
        'tgl_pinjam',
        'tgl_balik',
        'p_status',
        'p_desc',
    ];

    protected function casts(): array
    {
        return [
            'tgl_pinjam' => 'date',
            'tgl_balik' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'e_code', 'e_code');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class, 'p_code', 'p_code');
    }
}
