@extends('layouts.fara-navbar')

@section('content')

<redirect-index :menu="{{ json_encode($menu) }}"></redirect-index>
@endsection
