<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
</head>
<body>
    @if(count($errors))
    <h2>{{$errors}}</h2>
    @endif
    <form action="{{url('/signin')}}" method="POST">
        @csrf
        <label for="emailinp">Email: </label>
        <input type="text" name="email" id="emailinp">
        <br>
        <label for="passinp">Пароль: </label>
        <input type="password" name="password" id="passinp">
        <input type="submit" value="Увійти">
    </form>
</body>
</html>