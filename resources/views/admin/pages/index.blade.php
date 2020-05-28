@extends('layouts\app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                        <th>Id</th>
                        <th>Titolo</th>
                        <th>Sommario</th>
                        <th>Testo</th>
                        <th>Categorie</th>
                        <th>Tag</th>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                        <tr>
                            <td>{{$page->id}}</td>
                            <td>{{$page->title}}</td>
                            <td>{{$page->summary}}</td>
                            <td>{{$page->body}}</td>
                            <td>{{$page->category->name}}</td>
                            <td>@foreach ($page->tags as $tag)
                                {{$tag->name}} <br>
                                @endforeach
                            </td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
