<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <title>{{ $judul }}</title>
    <link rel="stylesheet" href="{!! asset('css/pdf.css') !!}" media="all" />
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{!! asset('assets/img/paperusLogo.jpg') !!}" alt="logo">
        </div>
        <h1>{{ $judulTabel }}</h1>
        <div id="company" class="clearfix">
            <div>Paperus</div>
            <div>Surabaya,<br /> Jawa Timur, Indonesia</div>
            <div>(62) 6282181080609</div>
            <div><a href="mailto:paperus@example.com">paperus@example.com</a></div>
            <div>{{ \Carbon\Carbon::now('Asia/Jakarta')->format('d F Y') }}</div>
        </div>
        <div id="project">
            {{-- <div><span>PROJECT</span> Website development</div>
            <div><span>CLIENT</span> John Doe</div>
            <div><span>ADDRESS</span> 796 Silver Harbour, TX 79273, US</div>
            <div><span>EMAIL</span> <a href="mailto:john@example.com">john@example.com</a></div>
            <div><span>DATE</span> August 17, 2015</div>
            <div><span>DUE DATE</span> September 17, 2015</div> --}}
        </div>
    </header>
    <main>
        @yield('tanggal')
        @yield('tabel')
        <div id="notices">
            {{-- <div>NOTICE:</div>
            <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div> --}}
        </div>
    </main>
    <footer>
        {{ $footer }}
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</body>

</html>
