<?php

namespace App\Models;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function postComment($store_id, StoreCommentRequest $request)
    {

       return self::query()->create($this->prepareCommentData($store_id,$request));

    }

    private function prepareCommentData($store_id, StoreCommentRequest $request)
    {
        return [
            'store_id'  => $store_id,
            'post_id'   => $request->input('post_id'),
            'comment_id'=> $request->input('comment_id'),
            'user_id'   => Auth::id(),
            'comment'   => $request->comment,
            'is_approve'=> 1
        ];
    }

    public function getAllComments()
    {
        return self::all();
    }

    public function updateComment(UpdateCommentRequest $request, $store_id, Comment $comment)
    {
        $updateCommentDta =[
            'store_id'  => $store_id,
            'post_id'   => $request->input('post_id') ?? $comment->post_id,
            'comment_id'=> $request->input('comment_id') ?? $comment->comment_id,
            'user_id'   => Auth::id(),
            'comment'   => $request->comment ?? $comment->comment,
            'is_approve'=> $request->input('is_approve') ?? $comment->is_approve,
        ];

       return $comment->update($updateCommentDta);

    }

    public function deleteComment($store_id, Comment $comment)
    {
        return $comment->delete();
    }

    public function findCommentById(Comment $comment)
    {
        return self::query()->find($comment);

    }
}
