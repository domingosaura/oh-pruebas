<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Impuesto extends Model
{
    use HasFactory;
    //use SoftDeletes;

    //protected $fillable = ['name', 'color'];
    protected $guarded = []; // todos son fillable

    public function item(){

        //return $this->belongsToMany(Item::class);
    }
}