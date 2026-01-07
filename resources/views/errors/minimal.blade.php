<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['h-full', 'dark' => ($appearance ?? 'system') == 'dark'])>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Inline script to detect system dark mode preference and apply it immediately --}}
    <script>
        (function() {
            const appearance = '{{ $appearance ?? "system" }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    {{-- Inline style to set the HTML background color based on our theme in app.css --}}
    <style>
        html {
            background-color: oklch(1 0 0);
        }

        html.dark {
            background-color: oklch(0.145 0 0);
        }
    </style>

    <title>@yield('title')</title>

    @vite('resources/css/app.css')
</head>
<body class="antialiased h-full bg-neutral-900">
<section class=" py-8 flex justify-center items-center lg:py-16 lg:px-6 px-4 h-full">
    <div class="text-center max-w-3xl">
        <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-neutral-100">@yield('code')</h1>
        <p class="mb-4 text-3xl tracking-tight font-bold  md:text-4xl text-white">@yield('message')</p>

        <a href="{{route('home')}}"
           class="inline-flex text-white bg-primary-600 hover:bg-primary-800 focus:ring-4 focus:outline-none
               focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:underline
               my-4">
            Regresar al inicio
        </a>
    </div>
</section>
</body>
</html>
