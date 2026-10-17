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
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            border: 0 solid;
        }

        html {
            background-color: oklch(1 0 0);
            font-family: sans-serif;
            height: 100%;
        }

        html.dark {
            background-color: oklch(0.145 0 0);
        }

        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            height: 100%;
            background-color: oklch(20.5% 0 0);
        }

        section {
            padding-block: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-inline: 1rem;
            height: 100%;
        }

        div {
            max-width: 48rem;
            text-align: center;
        }

        h1 {
            margin-bottom: 1rem;
            font-size: 4.5rem;
            line-height: 1;
            letter-spacing: -0.025em;
            font-weight: 800;
            color: oklch(97% 0 0);
        }

        p {
            margin-bottom: 1rem;
            font-size: 1.875rem;
            line-height: 1.2;
            letter-spacing: -0.025em;
            font-weight: 700;
            color: #ffffff;
        }

        a {
            display: inline-flex;
            color: #ffffff;
            margin-block: 1rem;
            text-align: center;
            font-size: 0.875rem;
            line-height: calc(1.25 / 0.875);
            padding-inline: 1.25rem;
            padding-block: 0.625rem;
            font-weight: 500;
            border-radius: 0.5rem;
            text-decoration-line: none;

            &:hover {
                @media (hover: hover) {
                    text-decoration-line: underline;
                }
            }

            &:focus {
                --tw-outline-style: none;
                outline-style: none;
            }
        }

        @media (width >= 48rem) {
            p {
                font-size: 2.25rem;
                line-height: calc(2.5 / 2.25);
            }
        }


        @media (width >= 64rem ) {
            section {
                padding-block: 4rem;
                padding-inline: 1.5rem;
            }

            h1 {
                font-size: 8rem;
                line-height: 1;
            }
        }
    </style>

    <title>@yield('title')</title>
</head>
<body>
<section>
    <div>
        <h1>@yield('code')</h1>
        <p>@yield('message')</p>

        <a href="{{route('home')}}">
            Regresar al inicio
        </a>
    </div>
</section>
</body>
</html>
