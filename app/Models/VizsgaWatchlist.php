<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VizsgaWatchlist extends Model
{
    protected $table = 'watchlist';
    protected $primaryKey = 'id';
    public $timestamps = true;
}