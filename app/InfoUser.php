<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoUser extends Model
{
        // Definisco valori Info con Chiave esterna User( per relazione 1 - 1)
    protected $fillable = [
       'user_id',
       'bio',
       'linkedin',
       'twitter',
       'facebook',
       'path_photo'
   ];

   // relaione 1 - 1 con appartenenza a user
   public function user()
    {
        return $this->belongsTo('App\User');
    }
}
