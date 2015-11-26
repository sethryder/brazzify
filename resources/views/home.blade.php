@extends('layouts.master')

@section('content')
    <div class="jumbotron">
        <p class="lead">Make any image more {{ $sexy_word }}.</p>
        <form action="/brazzify" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label for="url">Image URL</label>
                <input type="text" class="form-control" id="url" name="url" placeholder="Image URL">
            </div>
            <div class="form-group">
                <label for="logo-location">Logo Location</label>
                <select class="form-control" id="logo-location" name="logo-location">
                    <option value="bottom">Bottom Center (default)</option>
                    <option value="bottom-left">Bottom Left</option>
                    <option value="bottom-right">Bottom Right</option>
                    <option value="center">Center</option>
                    <option value="left">Center Left</option>
                    <option value="right">Center Right</option>
                    <option value="top">Top Center</option>
                    <option value="top-left">Top Left</option>
                    <option value="top-right">Top Right</option>

                </select>
            </div>
            <p><input type="submit" class="btn btn-lg btn-danger" value="Brazzify"></p>
        </form>
    </div>

@endsection