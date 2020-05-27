<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{   // Definisco valori Tag con Chiave esterna User( per relazione 1 - molti)
    protected $fillable = [
      'user_id',
      'name'
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
