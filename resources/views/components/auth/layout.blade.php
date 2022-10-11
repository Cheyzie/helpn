<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{asset("css/app.css")}}" />
    <title>{{ $title }}</title>
</head>
<body>
<div class="wrapper">
    <main>
        <h1 class="title">Helpn</h1>
        <div class="auth">
            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>
