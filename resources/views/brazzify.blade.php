@extends('layouts.master')

@section('content')

    <h4 class="text-center">Look at what you did!</h4>

    <img src="http://i.imgur.com/{{ $imgur_id }}.jpg" class="img-responsive center-block" />

    <p class="text-center">
        <p>&nbsp;</p>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" class="form-control" id="url" value="http://i.imgur.com/{{ $imgur_id }}.jpg">
        </div>
        <div class="form-group">
            <label for="markdown">Markdown</label>
            <input type="text" class="form-control" id="markdown" value="[Brazzify.me](http://i.imgur.com/{{ $imgur_id }}.jpg)">
        </div>
        <div class="form-group">
            <label for="bbcode">BBCode</label>
            <input type="text" class="form-control" id="bbcode" value="[url=http://brazzify.me][imghttp://i.imgur.com/{{ $imgur_id }}.jpg[/img][/url]">
        </div>
    </p>

@endsection