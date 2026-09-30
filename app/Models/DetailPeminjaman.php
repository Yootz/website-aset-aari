<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';

    protected $primaryKey = 'dt_code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'dt_code',
        'p_code',
        'a_code',
        'dt_qty',
        'dt_status',
    ];

    protected function casts(): array
    {
        return [
            'dt_qty' => 'integer',
        ];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'p_code', 'p_code');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'a_code', 'a_code');
    }
}
