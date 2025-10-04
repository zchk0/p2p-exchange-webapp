<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Вход в аккаунт</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>


<body style="background-attachment: fixed!important;background: linear-gradient(0deg,rgb(27 56 44 / 13%),rgb(242 242 255));">
        <style>
.top-0{top:0!important}.top-50{top:50%!important}.top-100{top:100%!important}.top-line{background:#fff;border-bottom:1px solid #ccc;overflow:hidden}.top-line2{background:#fff;border-bottom:1px solid #ccc;cursor:default;opacity:.7;overflow-x: scroll;transition:all .2s}.top-line2 ._inner{align-items:center;display:flex;padding:15px 15px}.top-line2 ._inner>div{align-items:center;display:flex;font-size:13px;margin-right:30px;white-space:nowrap}.top-line2 ._inner>div ._t{color:#58667e;font-weight:600;margin-right:15px}.top-line2 ._inner>div ._v{background:#f9f9f9;color:#58667e;margin:0 15px 0 0;padding:0 11px}.top-line2 ._inner>div ._v i{color:#384252;font-size:10px;font-style:normal}.top-line2 ._inner>div:last-child{margin-right:0}.top-line2:hover{opacity:1}.top-line2:hover ._inner>div ._t{color:#0a2882}
    
    
    

    
        .container0{max-width: 1150px; align-items: center; display: flex; justify-content: space-between; padding: 0 15px; 
        margin-left: auto; margin-right: auto; padding-left: var(--bs-gutter-x,.75rem); padding-right: var(--bs-gutter-x,.8rem); width: 100%;}
        .nav{display:flex;flex-wrap:wrap;list-style:none;margin-bottom:0;padding-left:0}
        nav{background:#fff;border-bottom:1px solid #ccc}
        .container0>div{font-size:14px}
        
        nav .logo{color:#000;display:inline-block;font-size:25px;font-weight:700;line-height:30px;padding:10px 5px;text-decoration:none}
        .top-buttons{display:flex}.top-buttons>a{background:#536aad;border-radius:10px;color:#fff;margin-left:10px;padding:5px 20px;text-decoration:none}
        .login-req-btn:hover, .btn-primary:hover {color: #fff;background-color: #286090!important;border-color: #204d74;}
        .btn-primary {background-color: #536aad;}
        
        @media (min-width: 900px){.container {width: 600px!important;}}
        .container {width: 320px;}
        .panel-body {padding: 20px;}
        .form-control {height: 40px;}
    </style>
	
	<div class="container" style="padding: 55px 0 50px;">
		<div class="panel panel-primary">
			<div class="panel-heading" style="padding: 15px 20px;background-color: #5a5a5a;border-color: #6b6c6c;text-align: center;margin-bottom: 5px;">Вход в аккаунт</div>
			<form class="panel-body" method="POST" action="{{ route('user.login') }}">
			    @csrf
				<label for="crypto">Имя пользователя или email:</label>
				<input id="email" name="elog" class="form-control" type="text" value="" placeholder="Email / Логин">
				@error('email')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
				<br>
				<label for="password">Пароль:</label>
				<input class="form-control" id="password" name="password" type="password" value="" placeholder="Пароль">
				@error('other')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
				<br>
				<div style="text-align: right;">			
				<button class="btn btn-primary" type="submit" name="sendMe" value="1" style=" padding: 8px 35px;font-size: 15px;">Войти</button>
				</div>
			</form>
		</div>
		<p id="nav">
		<a href="/register">Регистрация</a> | <a href="#">Забыли пароль?</a>			
		</p>
		<p id="backtoblog"><a href="/">← Перейти на главную</a></p>		
		<div style="font-style: italic; color: #c6c9c9; padding-top: 11px;">Александр Зайченко | Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</div>		
	</div>

</body>
</html>
