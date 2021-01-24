<!DOCTYPE html>
<html lang="en">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="工资条">
    <meta name="author" content="Damon Chen">
    <meta name="keyword" content="工资条">
    <title>工资条</title>
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- Main styles for this application-->
    <link href="{{ asset('plugins/coreui/icons/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/coreui/css/coreui.css') }}" rel="stylesheet">
</head>
<body class="c-app flex-row align-items-center"
      style="background-image: url({{ asset('images/bg.jpg') }});background-size: cover;"
      id="login-page">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        <form method="post" action="{{ route('login') }}">
                            {{ csrf_field() }}
                            <h1>登录</h1>
                            <p class="text-muted">工资条</p>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="cil-user"></i>
                                </span>
                                </div>
                                <input class="form-control" type="text" name="username" autofocus required placeholder="用户名">
                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="input-group mb-4">
                                <div class="input-group-prepend"><span class="input-group-text">
                                    <i class=" cil-lock-locked"></i>
                                </span></div>
                                <input class="form-control" type="password" name="password" required placeholder="密码">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-primary px-4" type="submit">登录</button>
                                </div>
                                <div class="col-6 text-right">
                                    {{--                                <button class="btn btn-link px-0" type="button">Forgot password?</button>--}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card text-white bg-primary py-5 d-md-down-none"
                     style="width:44%; background-image: url({{ asset('images/login-bg.png') }});background-size: cover;">
                    <div class="card-body text-center"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CoreUI and necessary plugins-->
<script src="{{ asset('plugins/coreui/js/coreui.bundle.js') }}"></script>
</body>
</html>
