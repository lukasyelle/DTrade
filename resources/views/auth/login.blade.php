@extends('layouts.app')

@section('body')
    <el-col :lg="6" :sm="12" class="center">
        <el-card>
            <div slot="header">
                {{ __('Login') }}
            </div>
            <el-form method="POST" action="{{ route('login') }}">
                @csrf
                <el-form-item label="{{ __('E-Mail Address') }}" class="{{ $errors->has('email') ? 'is-error' : '' }}">
                    <div class="el-input">
                        <input id="email" type="email" class="el-input__inner" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    @if ($errors->has('email'))
                        <span class="el-form-item__error">{{ $errors->first('email') }}</span>
                    @endif
                </el-form-item>
                <el-form-item label="{{ __('Password') }}" class="{{ $errors->has('password') ? 'is-error' : '' }}">
                    <div class="el-input">
                        <input id="password" type="password" class="el-input__inner" name="password" required>
                    </div>
                    @if ($errors->has('password'))
                        <span class="el-form-item__error" role="alert">{{ $errors->first('password') }}</span>
                    @endif
                </el-form-item>
                <input class="hidden" type="checkbox" name="remember" id="remember" checked>
                <button type="submit" class="el-button el-button--primary full-width">Login</button>
                @if (Route::has('password.request'))
                    <br /><br />
                    <el-link  href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </el-link>
                @endif
            </el-form>
        </el-card>
    </el-col>
@endsection
