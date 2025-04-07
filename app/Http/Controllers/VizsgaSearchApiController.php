<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VizsgaShows;
use App\Models\VizsgaRatings;
use App\Models\VizsgaComments;
use App\Models\VizsgaLevel;
use App\Models\VizsgaWatchlist;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class VizsgaSearchApiController extends Controller
{

    function Search(Request $request)
    {
        $request->validate([
            'search' => 'required|string'
        ]);

        $search = $request->search;

        if (empty(trim($search))) {
            return response()->json([]);
        }

        $shows = VizsgaShows::where('title', 'like', "%$search%")
            ->select('id', 'title', 'type', 'image_url')
            ->get();

        if ($shows->isEmpty()) {
            return response()->json([
                'message' => 'No shows found'
            ]);
        }

        return response()->json($shows);
    }

    function Filter(Request $request)
    {
        $request->validate([
            'filter' => 'required|string'
        ]);

        $filter = $request->filter;

        if (empty(trim($filter))) {
            return response()->json([]);
        }

        $filterTerms = array_map('trim', explode(',', $filter));

        $query = VizsgaShows::query();

        foreach ($filterTerms as $term) {
            $query->orWhere('category', 'like', "%$term%");
        }

        $shows = $query->get();

        if ($shows->isEmpty()) {
            return response()->json([
                'message' => 'No shows found'
            ]);
        }

        return response()->json($shows);
    }
}