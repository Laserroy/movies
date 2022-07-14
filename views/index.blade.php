@extends('layouts.app')

@section('title', 'Movies index')

@section('content')
    <span class="d-inline-block">
        <h1 class="p-2 d-inline-block">Movies list: </h1>
        <a class="btn btn-primary me-1 mb-3 d-inline-block" href="/create" role="button">Add movie</a>
    </span>

    <div class="container">
        <div class="row my-3">
            <div class="col-6 d-flex">
                <form class="d-flex" role="search" method="GET" action="/">
                    <input class="form-control"
                           name="search"
                           value="{{$search ?? ''}}"
                           type="search"
                           placeholder="Search"
                           aria-label="Search">
                    <select class="form-control  me-2" name="filter">
                        <option value="title">by title</option>
                        <option value="star" {{$filter === 'star' ? 'selected' : ''}}>by star</option>
                    </select>
                    <button class="btn btn-outline-secondary me-5" type="submit">Search</button>
                </form>
            </div>
            <div class="col-6">
                <form id="upload-form" action="/import" method="POST" enctype="multipart/form-data">
                    <div class="input-group">
                        <input class="form-control d-inline-block"
                               id="file_input"
                               type="file"
                               name="movies_import"
                               accept=".txt"
                               title="Only text files with template:&#010;Title: _&#010;Release Year: _&#010;Format: _&#010;Stars: _">
                        <button type="submit" class="btn btn-success d-inline-block">Upload</button>
                    </div>
                </form>
            </div>
        </div>
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
                        <div class="d-flex flex-row">
                            <a class="btn btn-info mx-1 text-black" role="button" href="/{{$movie->id}}">Show</a>
                            <button class="btn btn-danger text-black mx-1 delete-movie"
                                    data-action="/{{$movie->id}}"
                                    data-title="{{$movie->title}}"
                                    type="submit">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.delete-movie').on('click', function (e) {
                e.preventDefault();

                const button = $(this);
                const url = button.data('action');
                const title = button.data('title');

                Swal.fire({
                    title: `Delete movie: ${title}?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "red",
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                })
                    .then(({isConfirmed}) => {
                        if (!isConfirmed) {
                            return;
                        }
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function (data) {
                                try {
                                    const response = JSON.parse(data);
                                    if (response.status === 200) {
                                        Swal.fire({
                                            title: `${title} ${response.message}`,
                                            icon: "info",
                                            confirmButtonText: "Ok",
                                        }).then(() => location.reload())
                                        return;
                                    }
                                } catch (e) {
                                    Swal.fire({
                                        title: "Something went wrong",
                                        icon: "error",
                                        confirmButtonText: "Ok",
                                    })
                                }
                            }
                        });
                    });
            });


            $('#upload-form').on('submit', function (e) {
                e.preventDefault();

                const form = $(this);
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(this),
                    success: function(data){
                        const response = JSON.parse(data);

                        if (response.status === 200) {
                            Swal.fire({
                                title: response.message,
                                icon: "success",
                                confirmButtonText: "Ok",
                            }).then(() => location.reload());
                        }

                        if (response.status === 500) {
                            Swal.fire({
                                title: response.message,
                                icon: "error",
                                confirmButtonText: "Ok",
                            })
                        }
                    }
                });

            });
        });
    </script>
@endsection