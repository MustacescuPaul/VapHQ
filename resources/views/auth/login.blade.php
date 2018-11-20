@extends('layouts.app')

@section('content')

<div class="columns">
    <div class="column is-one-third is-offset-one-third m-t-100">
        <div class="card">
            <div class="card-content">
                <h1 class="title">Log in</h1>
                <form action="{{ route('login') }}" method="POST" role="form">
                    {{csrf_field()}}
                <div class="field">
                    <label for="username" class="label">Username</label>
                    <p class="control">
                        <input type="text" class="input {{$errors->has('username') ? 'is-danger' : ''}}" name="username" id="username" value="{{old('username')}}"  required>
                    </p>
                     @if ($errors->has('username'))
                        <p class="help is-danger">{{$errors->first('username')}}</p>
                    @endif
                </div>
                <div class="field">
                    <label for="password" class="label">Password</label>
                    <p class="control">
                        <input type="password" class="input {{$errors->has('password') ? 'is-danger' : ''}}" name="password" id="password" required>
                    </p>
                    @if ($errors->has('password'))
                        <p class="help is-danger">{{$errors->first('password')}}</p>
                    @endif
                </div>
                
                <b-checkbox name="remember" class="m-t-20">Remember Me</b-checkbox>
                <button class="button is-primary is-outlined is-fullwidth m-t-30">Log In</button>
            </div>
            </form>
        </div>
        <h5 class="has-text-centered m-t-20"><a href="{{ route('password.request') }}" class="is-muted">Forgot your password?</a></h5>
    </div>
</div>
@endsection
