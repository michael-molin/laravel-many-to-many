<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

use App\InfoUser;
use App\User;

class InfoUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        //Chiamata all'utente che non ha le info corrispondenti
        $users = User::doesntHave('info')->get();

        // Creo una nuova istanza per tutti gli utenti senza info, poi associo l'id e tramite faker aggiungo una bio
        foreach ($users as $key => $user) {
            $info = new InfoUser;
            $info->user_id = $user->id;
            $info->bio = $faker->paragraph(2, true);
            $info->save();
        }
    }
}
