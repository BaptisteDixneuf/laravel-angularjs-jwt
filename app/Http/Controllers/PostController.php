<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response as HttpResponse;

class PostController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$posts = Post::all();
        echo $posts;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->slug = $request->slug;
        $post->user_id = $request->user_id;
        $post->save();
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

        try {
            $post = Post::findOrFail($id);
            echo $post;
        } catch (ModelNotFoundException  $e) {
            return Response::json(['error' => 'The specified resource does not exist.'], HttpResponse::HTTP_NOT_FOUND);
        }


    }

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request,$id)
	{
        $post = new Post();
        $post = Post::find($id);

        $post->title = $request->title;
        $post->content = $request->content;
        $post->slug = $request->slug;
        $post->user_id = $request->user_id;

		$post->save();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Post::destroy($id);
	}

}
