@extends('layouts.meniu-principal')

@section('content')

<sertar-index :response="{{ json_encode($response) }}"></sertar-index>
@endsection
