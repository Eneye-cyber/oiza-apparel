<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Discover high-quality fabrics and stylish apparels at Oiza Apparels.')">
    <meta name="keywords" content="@yield('keywords', 'Ankara, fabrics, lace, Mens fabrics, children wears')">
    <meta name="robots" content="index,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph Tags -->
    <meta property="og:title" content="@yield('og_title', 'Oiza Apparels')">
    <meta property="og:description" content="@yield('og_description', 'Shop premium fabrics and ready-to-wear collections at Oiza Apparels.')">
    <meta property="og:image" content="@yield('og_image', asset('/favicon/android-chrome-192x192.png'))">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Oiza Apparels')">
    <meta name="twitter:description" content="@yield('og_description', 'Shop premium fabrics and ready-to-wear collections at Oiza Apparels.')">
    <meta name="twitter:image" content="@yield('og_image', asset('/favicon/android-chrome-192x192.png'))">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ request()->url() }}">

    <!-- Preload Key Resources -->
    <link rel="preload" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" as="style">
    <link rel="preload" href="{{ Vite::asset('resources/css/app.css') }}" as="style">

    <title>@yield('title', 'Oiza Apparels')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('/favicon/site.webmanifest') }}">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('/fallback.css') }}">
    @endif
      <link rel="stylesheet" href="{{ asset('styles/aos.css') }}" />
    @yield('head')
</head>

<body class="bg-white text-black overflow-x-clip">
    <x-header />
    <x-cart-drawer />

    <main class="font-dm-sans min-h-80">
        @yield('content')
    </main>

    <footer>
        <div class="container grid gap-8 text-black py-8">
            <section class="max-sm:pb-6 py-14 bg-cream">
                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="px-4 sm:px-6 md:px-12">
                        <h2>
                            <a href="/" class="font-playfair-display text-5xl font-medium"
                                aria-label="Oiza Apparels Home">
                                OIZA
                            </a>
                        </h2>
                        <div class="separator my-4 mr-4 !bg-primary opacity-100"></div>
                        <p class="text-black hover:text-[#555]">Choose us for high-quality fabrics, secure checkout,
                            flexible payment options, instant order confirmation, and prompt delivery to your door</p>
                    </div>
                    <div class="px-4 sm:px-0 grid lg:grid-cols-2 gap-y-6">
                        <div>
                            <h5 class="uppercase pb-2 font-semibold text-balance text-primary">Here to help</h5>
                            <p class="mb-4 text-sm text-black hover:text-[#555]">
                                Have a question? You may find an answer in our<span>&nbsp;</span>
                                <a href="{{ route('contact') }}#faq" class="underline hover:text-[#555]">FAQs</a>.
                                But you can also contact us:
                            </p>
                            <p class="text-13 mb-3">Customer Services</p>
                            <div class="space-y-2">
                                <div class="flex items-center gap-3 text-13">
                                    <x-heroicon-o-clock class="size-3.5" aria-hidden="true" />
                                    <p class="text-black hover:text-[#555]">Mon-Fri: 9:00 am - 6:00 pm</p>
                                </div>
                                <div class="flex items-center gap-3 text-13">
                                    <x-heroicon-o-phone class="size-3.5" aria-hidden="true" />
                                    <a href="tel:(+123)4567890"
                                        class="text-black hover:text-[#555] hover:underline">Call Us: ( +123 )
                                        456-7890</a>
                                </div>
                                <div class="flex items-center gap-3 text-13">
                                    <x-heroicon-o-envelope class="size-3.5" aria-hidden="true" />
                                    <a href="mailto:alaoeneye@gmail.com"
                                        class="text-black hover:text-[#555] hover:underline">Send us an email</a>
                                </div>
                            </div>
                        </div>
                        <div class="lg:px-6">
                            <h5 class="uppercase pb-2 font-semibold text-balance text-primary">Follow Us</h5>
                            <div class="space-y-2">
                                <div class="flex items-center gap-3 text-13">
                                    <x-ionicon-logo-facebook class="size-3.5" aria-hidden="true" />
                                    <a href="https://www.facebook.com/oiza.awomokun" target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-black hover:text-[#555] hover:underline">Facebook</a>
                                </div>
                                <div class="flex items-center gap-3 text-13">
                                    <x-ionicon-logo-instagram class="size-3.5" aria-hidden="true" />
                                    <a href="https://www.instagram.com/oiza_apparel/" target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-black hover:text-[#555] hover:underline">Instagram</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="flex items-center justify-center sm:justify-between flex-wrap gap-3">
                    <dl class="w-auto flex items-center justify-between gap-3 sm:gap-8 text-black">
                        <a href="{{ route('privacy') }}" class="text-black hover:text-[#555]">Privacy Policy</a>
                        <a href="{{ route('terms') }}" class="text-black hover:text-[#555]">Terms &amp; Condition</a>
                        <a href="{{ route('sitemap') }}" class="text-black hover:text-[#555]">Sitemap</a>
                    </dl>
                    <dl class="w-auto flex items-center justify-between gap-8">
                        <img src="{{ asset('/img/Visamastercard.webp') }}" alt="Visa and Mastercard payment options"
                            width="64" height="26" loading="lazy">
                    </dl>
                </div>
            </section>
        </div>
    </footer>

    <script>
  
    </script>

    <script src="{{ asset('js/main.js') }}"></script>

    <script src="{{ asset('js/aos.js') }}"></script>
    <script>
        AOS.init();
    </script>
    @yield('scripts')
</body>



</html>
