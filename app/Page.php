<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    // Definisco valori Page con Chiave esterna User e Category( per relazione 1 - molti) 
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'summary',
        'body',
        'slug'
    ];

    // relazione 1-molti
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    // relazione 1-molti
    public function category()
   {
       return $this->belongsTo('App\Category');
   }
   //relazione molti - molti
    public function photos()
    {
        return $this->belongsToMany('App\Photo');
    }
    //relazione molti - molti
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }
}
