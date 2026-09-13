<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('topic.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|max:250',
            'content' => 'required',
            'category_id' => 'required',
        ]);
        debugbar()->debug($validatedData);
        $userId = Auth::user()->id;

        $slug = Str::slug($validatedData['title']);

        $topicData = [
            'title' => $validatedData['title'],
            'slug' => $slug,
            'category_id' => $validatedData['category_id'],
            'user_id' => $userId,
        ];

        $createdTopic = Topic::query()->create($topicData);

        $createdPost = Post::query()->create([
            "content" => $validatedData['content'],
            "topic_id" => $createdTopic->id,
            "user_id" => $userId,
        ]);
        debugbar()->info($createdTopic,$createdPost);

        return redirect()->route('topic.show', [
            'topic' => $createdTopic,
            'slug' => $slug,
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic)
    {
        $topic->load('category');
        $topic->loadCount('posts');

        $posts = $topic->posts()
        ->with('user')

        ->oldest('created_at')
        ->paginate(10);

        debugbar()->info($posts);
        return view('topic.show', compact('topic','posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
