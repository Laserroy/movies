@extends('layouts.app')

@section('title', 'Movie info')

@section('content')
    <div class="jumbotron">
        <h1 class="text-center m-5">{{$movie->title}}</h1>
        <p>Release Year: {{$movie->release_year}}</p>
        <p>Format: {{$movie->format}}</p>
        <p>Stars:
            {{$movie->stars}}
        </p>
    </div>
@endsection