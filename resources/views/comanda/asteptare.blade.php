@extends('layouts.app')

@section('content')
<comanda-index :response="{{ json_encode($response) }}" ></comanda-index>

@endsection
