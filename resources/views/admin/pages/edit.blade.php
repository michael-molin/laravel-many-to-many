@extends('layouts.app')
@section('content')
    <div class="container">
      <div class="row">
        <div class="col-12">
            {{-- sezione di gestione errori nel caso di ritorno dalla funzione update --}}
            @foreach ($errors->all() as $message)
                {{$message}}
            @endforeach
            {{-- Fine sezione --}}
          <form action="{{route('admin.pages.update' , $page->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="title">Titolo</label>
                {{-- operatore ternario, se è presente un old (in caso di errore e quindi è stato un back), mi restituisce per l'appunto l'old, altrimenti il valore originario presente nella tabella --}}
                <input type="text" name="title" id="title" class="form-control" value="{{old('title') ? old('title') : $page->title}}">
            </div>
            <div class="form-group">
                <label for="summary">Sommario</label>
                <input type="text" name="summary" id="summary" class="form-control" value="{{old('summary') ? old('summary') : $page->summary}}">
            </div>
            <div class="form-group">
                <label for="body">Testo</label>
                {{-- operatore ternario ripetuto anche all'interno del corpo del testo per una corretta visualizzazione su schermo --}}
                <textarea name="body" id="body" cols="30" rows="10" class="form-control" value="{{old('body') ? old('body') : $page->body}}">{{old('body') ? old('body') : $page->body}}</textarea>
            </div>
            <div class="form-group">
                <label for="category_id">Categoria</label>
                <select name="category_id" id="category_id">
                    @foreach ($categories as $category)
                    {{-- operatore ternario: Se è presente un old(category id) OPPURE il precedente valore della categoria id su page (qui rappresentato con il collegamento diretto tramite freccie), è uguale all'id di quella categoria, allora inserisco l'opzione "selezionata") --}}
                        <option value="{{$category->id}}" {{((!empty(old('category_id')) || $category->id == $page->category->id) ? 'selected' : '' )}}> {{$category->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <h3>Tags</h3>
                @foreach ($tags as $tag)
                    <label for="tags-{{$tag->id}}">{{$tag->name}}</label>
                    {{-- operatore ternario: Se esiste un old(tag + chiave che rappresenta lo stesso valore dell'id) E i tag old sono presenti nell'array tag dato dal database sono checked  --}}
                    <input type="checkbox" name="tags[]" value="{{$tag->id}}"
                    {{(is_array(old('tags.')) &&  in_array($tag->id, old('tags'))) ? 'checked' : ''}}>
                @endforeach
            </div>
            <div class="form-group">
                <h3>Photos</h3>
                @foreach ($photos as $photo)
                    <div class="photo-card">
                        <div class="card-top">
                            {{-- IMG oscurato in quanto le istanze di foto precedenti non sono state inserite nello storage --}}
                            {{-- <img class="card-img-top" src="{{asset('storage/' . $photos->path)}}" alt="{{$photo->description}}"> --}}
                        </div>
                        <div class="card-bot">
                            {{-- vedi operatore ternario tags --}}
                            <label for="photos-{{$photo->id}}">{{$photo->name}}</label>
                            <input type="checkbox" name="photos[]" id="photos-{{$photo->id}}" value="{{$photo->id}}" {{(!empty(old('photos')) ||  $page->photos->contains($photo->id)) ? 'checked' : ''}}>
                            <form action="{{route('admin.photos.destroy' , $photo->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <input class="btn btn-danger" type="submit" value="Elimina Foto">
                            </form>
                        </div>
                    </div>
                @endforeach
                {{-- possibilità di aggiungere nuova foto --}}
                <div class="new-photo">
                    <label for="photo">Aggiungi nuova foto: </label>
                    <input type="file" name="photo" id="photo">
                </div>
            </div>
            <input type="submit" value="Salva" class="btn btn-primary">
          </form>
        </div>
      </div>
    </div>
@endsection
