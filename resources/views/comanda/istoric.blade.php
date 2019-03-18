@extends('layouts.app')

@section('content')
<comanda-istoric :istoric="{{ json_encode($istoric) }}" ></comanda-istoric>

@endsection
