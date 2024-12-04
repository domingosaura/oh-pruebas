<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pproductos extends Model
{
    use HasFactory;
    protected $guarded = []; // todos son fillable
    protected $table = 'pproductos';
}
