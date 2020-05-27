<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    // Definisco valori Photo con Chiave esterna User( per relazione 1 - molti)
    protected $fillable = [
       'user_id',
       'name',
       'path',
       'description'
   ];

   // Applico relazione 1 - molti
   public function user()
   {
       return $this->belongsTo('App\User');
   }
   // Applico relazione molti - molti
   public function pages()
    {
        return $this->belongsToMany('App\Page');
    }
}
