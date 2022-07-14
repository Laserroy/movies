@extends('layouts.app')

@section('title', 'Add movie')

@section('content')
    <div class="container-md">
        <h1 class="text-center m-5">Add movie</h1>
        <form id="form" action="/" method="POST">
            <div class="row">
                <div class="col-6 mb-3">
                    <label for="titleInput" class="form-label">Title</label>
                    <input type="text"
                           name="title"
                           pattern="^[^\s].*[^\s]$"
                           title="Surrounding spaces are not allowed"
                           class="form-control"
                           id="titleInput"
                           maxLength="80"
                           required>
                </div>
                <div class="col-6 mb-3">
                    <label for="releaseYearInput" class="form-label">Release Year</label>
                    <input type="number"
                           name="release_year"
                           class="form-control"
                           id="releaseYearInput"
                           min="1850"
                           max="2022"
                           placeholder="1850 - 2022"
                           required>
                </div>
                <div class="col-12 mb-3">
                    <label for="formatSelect" class="form-label">Format</label>
                    <select type="email" name="format" class="form-control" id="formatSelect" required>
                        <option value="VHS">VHS</option>
                        <option value="DVD">DVD</option>
                        <option value="Blu-Ray">Blu-Ray</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label for="starsSelect" class="form-label">Stars</label>
                    <select id="starsSelect" name="stars[]" class="form-control" id="passwordInput" multiple></select>
                </div>
                <div class="d-flex flex-row-reverse">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#starsSelect').select2({
                ajax: {
                    delay: 1000,
                    selectionCssClass: ':all:',
                    dataType: 'json',
                    url: '/stars/typeahead',
                    processResults: function (data) {
                        const stars = $.map(data, function (obj) {
                            obj.text = obj.text || obj.full_name;
                            return obj;
                        });

                        return {
                            results: stars
                        };
                    }
                }
            });
        });
    </script>
@endsection