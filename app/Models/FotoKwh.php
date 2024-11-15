<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoKwh extends Model
{
    use HasFactory;

    protected $table = 'foto_kwhs';
    protected $guarded = ['id'];
}
