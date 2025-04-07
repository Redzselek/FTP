<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\VizsgaShows;
use App\Models\VizsgaRatings;
use App\Models\VizsgaComments;
use App\Models\VizsgaLevel;
use App\Models\VizsgaWatchlist;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class VizsgaController extends Controller
{
    public function Dashboard()
    {
        $user = Auth::user();
        $shows = VizsgaShows::orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user ? ['name' => $user->name, 'email' => $user->email] : null,
                'shows' => $shows
            ]
        ]);
    }
}
