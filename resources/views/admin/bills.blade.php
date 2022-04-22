<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/admin/bills.css'); }}">
    <title>Document</title>
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <div class="_container header__container">HEADER</div>
        </header>
        <main class="main">
            <div class="_container main__container">
                <div class="main__content">
                    @foreach($bills as $bill)
                    <div class="main__bill">
                        <div class="bill__text">{{$bill->title}} #{{$bill->id}}</div>
                        <div class="bill_admin">
                            <button>ban: {{$bill->user->email}}</button>
                            <button>del</button>
                        </div>
                    </div>
                    @endforeach
                    {{ $bills->links() }}
                </div>
                <div class="main__sidebar">FILTERS</div>
            </div>
        </main>
        <footer class="footer">
            <div class="_container footer__container">FOOTER</div>
        </footer>
    </div>
</body>
</html>