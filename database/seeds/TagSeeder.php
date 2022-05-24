<?php

use Illuminate\Database\Seeder;
use App\Tag;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = config('tags');

        foreach ($tags as $tag) {
            Tag::create ([
                'name' => $tag['name'],
                'slug' => Str::slug($tag['name'])
            ]);
        }
    }
}
