<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pju extends Model
{
    use HasFactory;

    protected $table = 'pjus';
    protected $guarded = ['id'];

    public function kwh()
    {
        return $this->belongsTo(Kwh::class);
    }

    public function parentPju()
    {
        return $this->belongsTo(Pju::class, 'connected_to_pju');
    }

    public function childPju()
    {
        return $this->hasMany(Pju::class, 'connected_to_pju');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function fotoPjus()
    {
        return $this->hasMany(FotoPju::class);
    }
}
