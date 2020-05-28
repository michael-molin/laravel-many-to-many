@extends('layouts\app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <tr>
                        <td><a class="btn btn-primary" href="{{route('admin.pages.index')}}">Torna alla Index</a></td>
                    </tr>
                </table>
                <table class="table">
                    <thead>
                        <th>Id</th>
                        <th>Titolo</th>
                        <th>Sommario</th>
                        <th>Testo</th>
                        <th>Categorie</th>
                        <th>Tag</th>
                        <th colspan="2"> Azioni</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$page->id}}</td>
                            <td>{{$page->title}}</td>
                            <td>{{$page->summary}}</td>
                            <td>{{$page->body}}</td>
                            {{-- tramite la relazione presente tra le tabelle e la funzione category nel Model di page posso accedere ai dati della tabella categoria collegata presente nella tabella page  --}}
                            <td>{{$page->category->name}}</td>
                            {{-- Dato che ho più tag collegali alla pagina, con un for each posso stamparli tutti --}}
                            <td>@foreach ($page->tags as $tag)
                                {{$tag->name}} <br>
                                @endforeach
                            </td>
                            <td><a class ="btn btn-secondary" href="{{route('admin.pages.edit', $page->id)}}">Modifica</a></td>
                            <td>
                                {{-- Se l'id con cui ho fatto l'accesso alla sezione login è uguale all'id utente nel dato page->user_id (ovvero il creatore del post) allora mi compare l'opzione per cancellare il post --}}
                                @if(Auth::id()== $page->user_id)
                                    {{-- Quando si crea il delete, ricordarsi di fare un form, cambiare il method e aggiungere il token --}}
                                    <form action="{{route('admin.pages.destroy' , $page->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input class="btn btn-danger" type="submit" value="Elimina">
                                    </form>
                                
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
