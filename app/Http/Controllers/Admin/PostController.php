<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    private function getValidators($model) {
        return [
            'title'     => 'required|max:100',
            'slug' => [
                'required',
                Rule::unique('posts')->ignore($model),
                'max:100'
            ],
            'content'   => 'required',
            'tags'          => 'exists:App\Tag,id'
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myPost()
    {

        $posts = Post::where('user_id', Auth::user()->id)->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }


    public function index(Request $request)
    {
        $posts = Post::whereRaw('1 = 1');

        Session::put('posts_url', request()->fullUrl());

        if ($request->search) {
            $posts->where(function($query) use ($request) {
                $query->where('title', 'LIKE', "%$request->search%")
                    ->orWhere('content', 'LIKE', "%$request->search%");
            });
        }

        if ($request->author) {
            $posts->where('user_id', $request->author);
        }
        // preg_match_all('/#(checkbox*)/', $formData['content'], $tags_from_content);

        if ($request->checkbox) {

            $posts->leftJoin('post_tag', 'posts.id', '=', 'post_id')
                    ->whereIn('tag_id', $request->checkbox);

            // $posts->leftJoin('post_tag', 'posts.id', '=', 'post_id')
            //         ->whereIn('tag_id', $tags);

        }




        // $requestStr = $request->__toString();

        // if (Str::contains($requestStr, 'check_box')) {
        //     $checkBoxId = Str::after($requestStr, 'check_box_');
        //     var_dump($checkBoxId);
        //     $posts->leftJoin('post_tag', 'posts.id', '=', 'post_id')->where('tag_id', $checkBoxId);
        // }

        $posts = $posts->paginate(20);

        $users = User::all();
        $tags = Tag::all();


        return view('admin.posts.index', [
            'posts'         => $posts,
            'users'         => $users,
            'tags'          => $tags,
            'request'       => $request
        ]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->getValidators(null));
        $formData = $request->all() + [
            'user_id' => Auth::user()->id
        ];

        $post = Post::create($formData);

        return redirect()->route('admin.posts.show', $post->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate($this->getValidators($post));

        $post->update($request->all());


        return redirect()->route('admin.posts.show', compact('post'));


        // if(session('posts_url')) {
        //     return redirect(session('posts_url'));
        // }

        // return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->back();
    }
}
