<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VizsgaRatings extends Model
{
    protected $table = 'ratings';
    protected $primaryKey = 'id';
    public $timestamps = true;
}