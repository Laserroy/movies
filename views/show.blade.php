@extends('layouts.app')

@section('title', 'Movie info')

@section('content')
    <div class="jumbotron">
        <h1 class="text-center m-5">{{$movie->title}}</h1>
        <p>Release Year: {{$movie->release_year}}</p>
        <p>Format: {{$movie->format}}</p>
        <p>Stars:</p>
        <ol>
            @foreach($movie->stars as $star)
                <li>{{$star->fullname}}</li>
            @endforeach
        </ol>
    </div>
@endsection