<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VizsgaShows;
use App\Models\VizsgaRatings;
use App\Models\VizsgaComments;
use App\Models\VizsgaWatchlist;
use App\Models\VizsgaCategories;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class VizsgaFileApiController extends Controller{
    function UploadShow(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        
        $data = $request->all();
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $destinationPath = public_path('uploads/vizsga/');
            $request->file('file')->move($destinationPath, $fileName);

            $upload = new VizsgaShows();
            $upload->title = $data['title'];
            $upload->description = $data['description'];
            $upload->category = $data['category'];
            $upload->type = $data['type'];
            $upload->season = $data['season'] ?? 0;
            $upload->episode = $data['episode'] ?? 0;
            $upload->image_url = $fileName;
            $upload->user_id = $user->id;

            $upload->save();
            return response()->json(['success' => true, 'message' => 'Show uploaded successfully'], 200);
        }
        return response()->json(['success' => false, 'message' => 'Failed to upload.'], 422);
    }

    function UpdateShow(Request $request) {
        $request->validate([
            'id' => 'required|exists:shows,id',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'category' => 'required|string',
            'type' => 'required|in:movie,series',
            'season' => 'required|integer|nullable',
            'episode' => 'required|integer|nullable',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $show = VizsgaShows::where('id', $request->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $show->title = $request->title;
        $show->description = $request->description;
        $show->category = $request->category;
        $show->type = $request->type;
        $show->season = $request->season;
        $show->episode = $request->episode;

        if ($request->hasFile('file')) {
            // Kép törlés
            $oldImagePath = public_path('uploads/vizsga/' . $show->image_url);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Új kép feltöltés
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/vizsga/'), $fileName);
            $show->image_url = $fileName;
        }

        $show->save();

        return response()->json(['success' => true, 'message' => 'Show successfully updated!']);
    }

    function DeleteShow($id) {
        $show = VizsgaShows::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
            
        if (!$show) {
            return response()->json(['success' => false, 'message' => 'Show not found or you do not have permission to delete it!'], 403);
        }
        
        $show->delete();
        return response()->json(['success' => true, 'message' => 'Show successfully deleted!']);
    }

    function SelectedShow(Request $request, $id) {
        $show = VizsgaShows::find($id);
        return response()->json($show);
    }

    function GetCategories(){
        $categories = VizsgaCategories::all();
        return response()->json($categories);
    }
}