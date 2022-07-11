@extends('layouts.app')

@section('title', 'Movies index')

@section('content')
    <span class="d-inline-block">
        <h1 class="p-2 d-inline-block">Movies list: </h1>
        <a class="btn btn-primary me-1 mb-3 d-inline-block" href="/create" role="button">Add movie</a>
    </span>
    <div class="row m-2">
        <div class="col-8 d-flex">
            <form class="d-flex" role="search" method="GET" action="/">
                <input class="form-control" name="search" type="search" placeholder="Search" aria-label="Search">
                <select class="form-control  me-2" name="filter">
                    <option value="title">by title</option>
                    <option value="star">by star</option>
                </select>
                <button class="btn btn-outline-success me-5" type="submit">Search</button>
            </form>
        </div>
        <div class="col-4">
            <form action="/import" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <input class="form-control d-inline-block" type="file" name="movies_import" accept=".txt">
                    <button type="submit" class="btn btn-success d-inline-block">Upload</button>
                </div>
            </form>
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