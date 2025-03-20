<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VizsgaRatings;

class VizsgaShows extends Model
{
    protected $table = 'shows';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    public function ratings()
    {
        return $this->hasMany(VizsgaRatings::class, 'show_id');
    }
    
    public function averageRating()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }
}