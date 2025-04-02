<?php

namespace App\Http\Controllers;
use App\Models\VizsgaComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class VizsgaCommentController extends Controller
{
    public function index()
    {
        return VizsgaComments::all();
    }

    public function GetShowComments(Request $request)
    {
        return VizsgaComments::where('show_id', $request->show_id)->get();
    }

    public function SaveComment(Request $request)
    {
        $comment = VizsgaComments::create([
            'show_id' => $request->show_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        
        return response()->json(['success' => true, 'message' => 'Comment created successfully', 'comment' => $comment], 201);
    }

    public function UpdateComment(Request $request)
    {
        $show_id = $request->show_id;
        if (!$comment = VizsgaComments::find($show_id)) {
            return response()->json(['success' => false, 'message' => 'Comment not found'], 404);
        }

        if ($comment->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $comment->show_id = $request->show_id;
        $comment->comment = $request->comment;
        $comment->edited = 1;
        $comment->save();

        return response()->json(['success' => true, 'message' => 'Comment updated successfully', 'comment' => $comment], 200);
    }

    public function DeleteComment(Request $request, $id)
    {
        if (!$comment = VizsgaComments::find($id)) {
            return response()->json(['success' => false, 'message' => 'Comment not found'], 404);
        }

        if ($comment->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['success' => true, 'message' => 'Comment deleted successfully'], 200);
    }
}
