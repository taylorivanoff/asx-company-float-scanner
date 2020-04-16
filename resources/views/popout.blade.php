@extends('layouts.app')

@section('content')
<form action="/show" method="post" class="form-inline">
    @csrf
    <div class="form-group pr-2">
        <input class="form-control inline" list="tickers" name="ticker">

        <datalist id="tickers">
            @foreach ($tickers as $ticker)
                <option value="{{ $ticker['ASX code'] }}.AX">
            @endforeach
        </datalist>
    </div>
    <button class="btn btn-success" type="submit">Get Float</button>
</form> 
@endsection