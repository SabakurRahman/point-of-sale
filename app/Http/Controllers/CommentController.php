<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommestByIdResource;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use http\Env\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //
        $allcomments= (new Comment())->getAllComments();
        //dd($allcomments);

        return response()->json([
            'success'=>true,
            'message'=>"All Comments",
            "data"   => $allcomments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request,$store_id)
    {
        //
       $comment = (new Comment())->postComment($store_id,$request);
       //dd($comment);

       return response()->json([
            "success"=>true,
            "message"=>"Comment Post Successfully",
            "data" =>$comment
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($store_id ,Comment $comment)
    {
        //

        $commentBy_id = (new Comment())->findCommentById($comment);

        return response()->json([
            'success'=>true,
            'message'=>"comments By Id",
            "data" =>new CommestByIdResource($comment)
        ]);


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request,$store_id, Comment $comment)
    {
        //
       // dd($comment);
        $updateComment=(new Comment())->updateComment($request,$store_id,$comment);
        return response()->json([
            'success'=>true,
            'message'=>"Comments Updated",
            'data'=>$updateComment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($store_id,Comment $comment)
    {
        //
       $deleteComments = (new Comment())->deleteComment($store_id,$comment);

       return response()->json([
           'success'=>true,
           'message'=>"comment deleted",
           "data"   =>$deleteComments
       ]);
    }
}
