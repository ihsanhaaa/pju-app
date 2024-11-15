<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kwh extends Model
{
    use HasFactory;

    protected $table = 'kwhs';
    protected $guarded = ['id'];

    public function fotoKwhs()
    {
        return $this->hasMany(FotoKwh::class);
    }

    public function pjus()
    {
        return $this->hasMany(Pju::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
