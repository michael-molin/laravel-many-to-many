<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Definisco valori category con Chiave esterna User( per relazione 1 - molti)
    protected $fillable = [
        'user_id',
        'name',
        'description'
    ];

    // Applico relazione 1 - molti
    public function user()
   {
       return $this->belongsTo('App\User');
   }

   // applico relazione molti - molti
   public function pages()
   {
       return $this->hasMany('App\Page');
   }
}
