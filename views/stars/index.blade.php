@extends('layouts.app')

@section('title', 'Stars index')

@section('content')
    <h1 class="p-2">Stars list:</h1>
    <div class="container">
        <div class="row my-3">
            <div class="col-12">
                <form id="add-star-form" action="/stars" method="POST">
                    <div class="input-group mb-3">
                        <input name="full_name"
                               type="text"
                               maxlength="100"
                               pattern="^[^\s].*[^\s]$"
                               title="Surrounding spaces are not allowed"
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
                            <button class="btn btn-danger text-black mx-1 delete-star"
                                    data-action="/stars/{{$star->id}}"
                                    data-name="{{$star->full_name}}"
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
            $('.delete-star').on('click', function (e) {
                e.preventDefault();

                const button = $(this);
                const url = button.data('action');
                const name = button.data('name');

                Swal.fire({
                    title: `Delete star: ${name}?`,
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
                                            title: `${name} ${response.message}`,
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

            $('#add-star-form').on('submit', function (e) {
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
                            }).then(() => window.location.href="/stars")
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