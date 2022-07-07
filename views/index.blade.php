@extends('layouts.app')

@section('title', 'Movies index')

@section('content')
    <h1 class="p-2">Movies list:</h1>
    <div class="row m-2">
        <div class="col-12 d-flex w-100">
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success me-5" type="submit">Search</button>
            </form>

            <a class="btn btn-primary me-1" href="/create" role="button">Add movie</a>
            <a class="btn btn-success" href="/upload" role="button"><i class="fa-solid fa-file-arrow-up"></i>File upload</a>
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
                        <div class="btn-group">
                            <a class="btn btn-warning m-1" href="/{{$movie->id}}">Show</a>
                            <form action="/{{$movie->id}}" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input class="btn btn-danger m-1" name="delete" type="submit" value="Delete">
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection