<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User2 extends Model
{
    use HasFactory;
    protected $guarded = []; // todos son fillable
    protected $table = 'users2';
}
