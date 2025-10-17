<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CultureMedia extends Model
{
    use HasFactory;

    protected $primaryKey = 'media_id';

    protected $fillable = [
        'culture_id',
        'type',
        'url',
    ];

    public function culture()
    {
        return $this->belongsTo(Culture::class, 'culture_id', 'culture_id');
    }
}
