<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avisos extends Model
{
    use HasFactory;
    protected $guarded = []; // todos son fillable
    protected $table = 'avisos';
}