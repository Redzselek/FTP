<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VizsgaShows extends Model
{
    protected $table = 'shows';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    // Define the relationship with the Ratings model
    public function ratings()
    {
        return $this->hasMany(VizsgaRatings::class, 'show_id');
    }
    
    // Get the average rating for this show
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?: 0;
    }
}