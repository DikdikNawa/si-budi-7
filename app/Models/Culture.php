<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Culture extends Model
{
    use HasFactory;

    protected $primaryKey = 'culture_id';

    protected $fillable = [
        'name',
        'description',
        'category',
        'latitude',
        'longitude',
        'status',
        'submitted_by',
        'curated_by',
    ];

    public function media()
    {
        return $this->hasMany(CultureMedia::class, 'culture_id', 'culture_id');
    }
}
