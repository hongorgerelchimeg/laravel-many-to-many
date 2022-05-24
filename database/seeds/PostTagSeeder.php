<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\Tag;

class PostTagSeeder extends Seeder
{
    public function run()
    {
        $posts = Post::all();

        foreach ($posts as $post) {
            $postTags = Tag::inRandomOrder()->limit(rand(1, 7))->get();

            $post->tags()->attach($postTags->pluck('id')->all());
        }
    }
}
