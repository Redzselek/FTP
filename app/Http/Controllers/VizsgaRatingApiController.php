<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VizsgaRatings;

class VizsgaRatingApiController extends Controller{

    function GetRatings() {
        $ratings = VizsgaRatings::all();
        return response()->json($ratings); 
    }

    function AddRating(Request $request){
        $request->validate([
            'show_id' => 'required',
            'rating' => 'required'
        ]);
        $userId = Auth::id();
        $rating = VizsgaRatings::updateOrCreate(
            ['user_id' => $userId, 'show_id' => $request->show_id],
            ['rating' => $request->rating]
        );
        // Check if this was a new record or an update
        $wasRecentlyCreated = $rating->wasRecentlyCreated;
        
        return response()->json([
            'success' => true, 
            'message' => $wasRecentlyCreated ? 'Rating successfully added!' : 'Rating successfully updated!',
            'action' => $wasRecentlyCreated ? 'created' : 'updated',
            'rating' => $rating
        ]);
    }

    function DeleteRating(Request $request){
        $request->validate([
            'show_id' => 'required|exists:shows,id'
        ]);
        $rating = VizsgaRatings::where('user_id', Auth::id())
            ->where('show_id', $request->show_id)
            ->first();
            
        if ($rating) {
            $rating->delete();
            return response()->json(['success' => true, 'message' => 'Rating successfully deleted!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Rating not found!']);
        }
    }

    function GetAverageRating(Request $request, $show_id) {
        $avgRating = VizsgaRatings::averageRating($show_id);
        $userRating = null;
        
        if (Auth::check()) {
            $userId = Auth::id();
            $userRatingObj = VizsgaRatings::where('user_id', $userId)
                ->where('show_id', $show_id)
                ->first();
                
            if ($userRatingObj) {
                $userRating = $userRatingObj->rating;
            }
        }
        
        return response()->json([
            'average_rating' => round($avgRating ?? 0, 2),
            'user_rating' => $userRating
        ]);
    }

    function GetAllAverageRating() {
        $avgRatings = VizsgaRatings::all();
        $showIds = $avgRatings->pluck('show_id')->unique();
        $result = [];
        
        // Calculate average ratings for each show
        foreach ($showIds as $showId) {
            $avgRating = $avgRatings->where('show_id', $showId)->avg('rating');
            $roundedRating = round($avgRating ?? 0, 2);
            $result[] = [
                'show_id' => $showId,
                'average_rating' => $roundedRating
            ];
        }

        // Sort in descending order by average_rating
        usort($result, function($a, $b) {
            return $b['average_rating'] <=> $a['average_rating'];
        });
        
        return response()->json($result);
    }
}