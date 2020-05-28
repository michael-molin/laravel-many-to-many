<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\category;
use App\User;
use App\Tag;
use App\Photo;
use App\Page;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index' , compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $users = User::all();
        $tags = Tag::all();
        $photos = Photo::all();
        $pages = Page::all();

        return view('admin.pages.create' , compact('categories', 'users' , 'tags' , 'photos', 'pages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // assegno a data tutti i valori ricevuti dalla funzione create (che a sua volta rimanda a store passando i dati a $request)
        $data = $request->all();
        // Tramite Carbon recupero la data odierna in termini di giorni-mesi-anni e la concateno al titolo trasformato per compensare lo slug
        $now = Carbon::now()->format('d-m-yy');
        $data['slug'] = Str::slug($data['title'] , '-' ). $now;
        // assegno all'user_id l'id dell'utente attualmente loggato che sta creando la pagina
        $data['user_id'] = Auth::id();

        $validator = Validator::make($data, [
            'title' => 'required| string| max: 200',
            'body' => 'required',
            'user_id' => 'required',
            'slug' => 'required|unique:pages',
            // controllo che esista l'id della categoria presso la tabella categories voce: id
            'category_id' => 'required|exists:categories,id',
            // controllo di ricevere gli array con tutte le foto e tutti i tag disponibili, e controllo poi che esistano in tutto l'array
            'tags' => 'required|array',
            'photos' => 'required|array',
            'tags.*' => 'exists:tags,id',
            'photos.*' => 'exists:photos,id'
        ]);

        // se la validazione fallisce, torno a create e restituisco come input gli errori del validator
        if ($validator->fails()) {
            return redirect()->route('admin.pages.create')
            ->withErrors($validator)
            ->withInput();
        }

        //Creo una nuova istanza Page e assegno i valori di data. Poi faccio un check se il salvataggio è andato a buon fine
        $page = new Page;
        $page->fill($data);
        $saved = $page->save();
        if (!$saved) {
            dd('errore salvataggio');
        }

        //In questo caso creo dei nuovi dati nella tabella di mezzo (pivot) dato che la relazione tra pages-tags e pages-photo è di Molti - Molti
        $page->tags()->attach($data['tags']);
        $page->photos()->attach($data['photos']);

        return redirect()->route('admin.pages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        // trovo la pagina con l'id associato e restituisco lo show con i valori di tale pagina, in caso di errore FindOrFail mi da in automatico un 404
        // $page = Page::findOrFail(id);

        // Se voglio utilizzare lo slug invece, FindOrFail non funziona, ma si può rimediare con un Where
        $page = Page::where('slug', $slug)->first();
        if (empty($page)) {
            abort('404');
        }
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Trovo la pagina corrispondente tramite id, dato che posso modificare/rimuovere/aggiungere pure la categoria, i tags e le foto presenti devo caricarmi tutti i dati presenti nelle corrispondenti tabelle
        $page = Page::findOrFail($id);
        $categories = Category::all();
        $photos = Photo::all();
        $tags = Tag::all();
        return view('admin.pages.edit' , compact('page' , 'categories' , 'photos' , 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // check se chi modifica è anche l'autore, se non lo è, 404 e tutti a casa
        $page = Page::findOrFail($id);
        $author = $page->user_id;
        $actual_user = Auth::id();
        if ($author != $actual_user) {
            abort('404');
        }

        $data = $request->all();
        $page ->fill($data);
        $updated = $page->update();
        if (!$updated) {
            dd('errore aggiornamento dati');
        }

        // sincronizzo tabella ponte, l'update mi restituisce l'id del dato modificato, quindi vado a sincronizzare sia l'id pagina che l'id tag/foto
       $page->tags()->sync($data['tags']);
       $page->photos()->sync($data['photos']);

       return redirect()->route('admin.pages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        // prima di cancellare, elimino i collegamenti della tabella ponte
        $page->tags()->detach();
        $page->photos()->detach();
        $deleted = $page->delete();

        if(!$deleted){
            return redirect()->back();
        }

        return redirect()->route('admin.pages.index');
    }
}
