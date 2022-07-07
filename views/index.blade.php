@extends('layouts.app')

@section('title', 'Movies index')

@section('content')
    <h1 class="text-center p-5">Movies:</h1>
    <div class="row m-2">
        <div class="col-3">
            <a class="btn btn-primary" href="/create" role="button">Add movie</a>
        </div>
        <div class="col-3">
            <a class="btn btn-success" href="/upload" role="button">File upload</a>
        </div>
    </div>
    <div class="container">
        <table class="table table-responsive">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Release Year</th>
                <th scope="col">Format</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($movies as $movie)
                <tr>
                    <th scope="row">{{$movie->id}}</th>
                    <td>{{$movie->title}}</td>
                    <td>{{$movie->release_year}}</td>
                    <td>{{$movie->format}}</td>
                    <td>
                        <form action="/{{$movie->id}}" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <input class="btn btn-danger" name="delete" type="submit" value="Delete">
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection