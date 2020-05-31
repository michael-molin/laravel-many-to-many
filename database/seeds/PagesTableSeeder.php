<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Page;
use App\User;
use App\Category;
use App\Tag;
use App\Photo;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for($i = 0; $i < 10; $i++) {
            $now = Carbon::now()->format('d-m-yy');
            $user =  User::inRandomOrder()->first();
            $category =  Category::inRandomOrder()->first();
            $page = new Page;
            $page->user_id = $user->id;
            $page->category_id = $category->id;
            $page->title = $faker->sentence(6, true);
            $page->body = $faker->paragraph(6, true);
            $page->summary = $faker->sentence(6, true);
            $page->slug =  Str::slug($data['title'] , '-' ). $now;
            $page->save();

            $photos = Photo::inRandomOrder()->limit(3)->get();
            $page->photos()->attach($photos);

            $tags = Tag::inRandomOrder()->limit(3)->get();
            $page->tags()->attach($tags);
        }
    }
}

?>
