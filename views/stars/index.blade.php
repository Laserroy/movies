@extends('layouts.app')

@section('title', 'Stars index')

@section('content')
    <h1 class="p-2">Stars list:</h1>
    <div class="row">
        <div class="col-12">
            <form action="/stars" method="POST">
                <div class="input-group mb-3">
                    <input name="full_name"
                           type="text"
                           maxlength="100"
                           required
                           class="form-control"
                           placeholder="Full name"
                           aria-label="Full name"
                           aria-describedby="button-addon">
                    <button class="btn btn-primary" type="submit" id="button-addon">Add new star</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    <div class="container">
        <table class="table table-responsive">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Full Name</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($stars as $star)
                <tr>
                    <th scope="row">{{$star->id}}</th>
                    <td>{{$star->full_name}}</td>
                    <td>
                        <div class="btn-group">
                            <form action="/stars/{{$star->id}}" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <input class="btn btn-danger text-black m-1" name="delete" type="submit" value="Delete">
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection