@extends('layouts.app')

@section('content')
<p class="text-monospace d-none d-sm-block mb-md-4 p">
	Instant access to an ASX company's float for active traders.
</p>

<p class="text-monospace d-none d-sm-block mb-md-4 p">
	<small>Enter a ASX stock ticker/symbol.</small>

    <span><small><a href="/companies" target="_blank" class="link ml-4 d-none d-sm-inline"><u>Filter Companies By Float</u></a></small></span>

	<span><small><a href="/" onClick="return popout(this)" class="link ml-4 d-none d-sm-inline"><u>Popout Window</u></a></small></span>

	<span><small><a href="https://github.com/taylorivanoff/asx-float-scanner" target="_blank" class="link ml-4 d-none d-sm-inline"><u>Source Code</u></a></small></span>
</p>

<stock-select></stock-select>
@endsection