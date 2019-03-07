@extends('layouts.app')

@section('content')
<comanda-index :comanda="{{ json_encode($comanda) }}" ></comanda-index>
@endsection
