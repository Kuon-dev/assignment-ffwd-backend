<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller{
    // get all quizzes of the current user (user_id) 
    public function index(Request $request) {
        Log::debug("Hello");
        $userID = $request->user;
        $title = $request->title;
    
        $quizzes = Quiz::where("user_id", $userID)
            ->where("title", "like", "%{$title}%")
            ->orderByDesc("attempted_date")
            ->latest()
            ->skip($request->index * 10)
            ->take(10)
            ->get();
    
        // $quizzesCount = Quiz::where("user_id", $userID)
        //     ->where("title", "like", "%{$title}%")
        //     ->count();
    
        return response()->json([
            "data" => $quizzes,
            // "quizzes_count" => $quizzesCount,
        ]);
    }

    // Get single score based on id
    public function getScore(Request $request) {
        $quizScore= Quiz::where("id", $request->score_id)->get();
        return response()->json([
            "score" => $quizScore,
        ]);

    }
}
