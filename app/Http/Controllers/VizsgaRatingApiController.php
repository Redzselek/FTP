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

    function GetAverageRating(Request $request) {
        $request->validate([
            'show_id' => 'required|exists:shows,id'
        ]);
        
        $avgRating = VizsgaRatings::averageRating($request->show_id);
        
        return response()->json([
            'success' => true,
            'average_rating' => $avgRating ?? 0
        ]);
    }
}