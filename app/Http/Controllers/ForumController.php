<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Forum;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ForumController extends Controller
{
    //   FORUM SECTION   //
    // get forum posts
    public function index(Request $request) {
        $forums = Forum::latest()
        ->skip($request->index * 10) 
        ->take(10)
        ->get();

        $userNames = [];
        $upVotes = [];
        $downVotes = [];

        foreach($forums as $forum) {
            $user = User::find($forum->user_id);
            if($user) {
                $userNames[] = $user->name;
            }

            $upVote = Vote::where('forum_id', $forum->id)
            ->where('is_upvote', '=', 1)
            ->count();

            $downVote = Vote::where('forum_id', $forum->id)
            ->where('is_upvote', '=', 0)
            ->count();
            
            $upVotes[] = $upVote;
            $downVotes[] = $downVote;
        }

        return response()->json(['data' => $forums, 'users' => $userNames, 'upVotes' => $upVotes, 'downVotes' => $downVotes]);
    }

    // function to get total post count math.ceil(count / 10) return the final count value
    public function paginationCount() {
        $paginationCount = Forum::count();

        Log::debug($paginationCount);

        return response()->json([['paginationCount' => $paginationCount]]);
    }

    // get specific forum post with comments
    public function show(Request $forumID) {
        $forum = Forum::findOrFail($forumID);
        $comments = Comment::findOrFail($forumID);

        return response()->json([
            'forum' => $forum, 
            'comment' => $comments
        ]);
    }

    // create new forum
    public function create() {
        return response()->json(['message' => 'Your Forum Post has been Created.'], 200);
    }

    // edit forum
    public function edit(Request $forumID) {
        $forum = Forum::findOrFail($forumID);

        return response()->json(['message' => 'Forum Post Updated.'], 200);
    }

    // delete forum
    public function destroy(Request $forumID) {
        $forum = Forum::findOrFail($forumID);
        $forum->delete();

        return response()->json(['message' => 'Forum Post has been Deleted.'], 200);
    }

    //   COMMENT SECTION   //
    // create new comment
    public function createComment(Request $forumID) {
        return response()->json(['message' => 'Your Comment has been Created for Post ' + $forumID], 200);
    }

    // edit comment
    public function editComment(Request $commentID) {
        $comment = Comment::findOrFail($commentID);

        return response()->json(['message' => 'Forum Post Updated.'], 200);
    }

    // delete comment
    public function destroyComment(Request $commentID) {
        $comment = Comment::findOrFail($commentID);
        $comment->delete();

        return response()->json(['message' => 'Comment has been Deleted.'], 200);
    }
}
