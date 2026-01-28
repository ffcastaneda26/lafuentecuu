{{-- resources/views/layouts/main.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal de Noticias')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">

    <!-- HEADER -->
    @include('partials.header')

    <!-- MAIN CONTENT -->
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- FOOTER -->
    @include('partials.footer')


    @stack('scripts')
    <script src="{{ asset('js/category_nav_responsive.js') }}"></script>
    <script src="{{ asset('js/tipo_cambio.js') }}"></script>
    <script src="{{ asset('js/fecha_hora.js') }}"></script>

</body>

</html>
