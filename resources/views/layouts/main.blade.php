<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="@yield('description', 'Discover high-quality fabrics and stylish apparels at Oiza Apparels.')">
  <meta name="robots" content="index,follow">

  <!-- Open Graph Tags -->
  <meta property="og:title" content="@yield('og_title', 'Oiza Apparels')">
  <meta property="og:description" content="@yield('og_description', 'Shop premium fabrics and ready-to-wear collections at Oiza Apparels.')">
  <meta property="og:image" content="@yield('og_image', asset('/img/oiza-logo.jpg'))">
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:type" content="website">

  <!-- Twitter Card Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('og_title', 'Oiza Apparels')">
  <meta name="twitter:description" content="@yield('og_description', 'Shop premium fabrics and ready-to-wear collections at Oiza Apparels.')">
  <meta name="twitter:image" content="@yield('og_image', asset('/img/oiza-logo.jpg'))">

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

  @yield('head')
</head>

<body class="bg-white text-black">
  <header class="w-full pt-5 pb-2.5 font-dm-sans">
    <div class="container max-md:px-3">
      <div class="flex justify-between items-center pb-2 border-b text-xs border-b-[#2222221a]">
        <div class="flex items-center gap-2">
          <x-heroicon-o-phone class="size-4" aria-hidden="true" />
          <a href="tel:(+123)4567890" class="font-dm-sans text-black opacity-75 hover:opacity-100">( +123 ) 456 7890</a>
        </div>
        <div>
          <a href="{{ route('contact') }}" class="text-black opacity-75 hover:opacity-100">Customer Support</a>
        </div>
      </div>

      <section class="pt-2">
        <div class="flex justify-between items-center">
          <h2>
            <a href="/" class="font-playfair-display text-3xl font-medium" aria-label="Oiza Apparels Home">
              OIZA
            </a>
          </h2>

          <nav class="hidden md:flex items-center uppercase font-medium" aria-label="Main navigation">
            <a href="#" class="p-5 inline-block hover:text-[#555]">New Arrivals</a>
            <div class="relative" aria-haspopup="true" aria-expanded="false">
              <div class="p-5 cursor-pointer" role="button" aria-label="Fabrics menu">
                <div class="flex items-center gap-1.5">
                  <span>Fabrics</span>
                  <x-heroicon-c-chevron-down class="h-4 w-auto" aria-hidden="true" />
                </div>
              </div>
              <!-- Dropdown content can be added here -->
            </div>
            <div class="relative" aria-haspopup="true" aria-expanded="false">
              <div class="p-5 cursor-pointer" role="button" aria-label="Ready to wear menu">
                <div class="flex items-center gap-1.5">
                  <span>Ready to wear</span>
                  <x-heroicon-c-chevron-down class="h-4 w-auto" aria-hidden="true" />
                </div>
              </div>
              <!-- Dropdown content can be added here -->
            </div>
            <div class="relative" aria-haspopup="true" aria-expanded="false">
              <div class="p-5 cursor-pointer" role="button" aria-label="Accessories menu">
                <div class="flex items-center gap-1.5">
                  <span>Accessories</span>
                  <x-heroicon-c-chevron-down class="h-4 w-auto" aria-hidden="true" />
                </div>
              </div>
              <!-- Dropdown content can be added here -->
            </div>
          </nav>

          <div class="w-auto">
            <a href="{{ route('cart') }}" aria-label="View shopping cart">
              <x-heroicon-o-shopping-bag class="size-5" aria-hidden="true" />
            </a>
          </div>
        </div>
      </section>
    </div>
  </header>

  <main class="font-dm-sans">
    @yield('content')
  </main>

  <footer>
    <div class="container grid gap-8 text-black py-8">
      <section class="max-sm:pb-6 py-14 bg-cream">
        <div class="grid sm:grid-cols-2 gap-6">
          <div class="px-4 sm:px-6 md:px-12">
            <h2>
              <a href="/" class="font-playfair-display text-5xl font-medium" aria-label="Oiza Apparels Home">
                OIZA
              </a>
            </h2>
            <div class="separator my-4 mr-4"></div>
            <p class="text-black hover:text-[#555]">Choose us for high-quality fabrics, secure checkout, flexible payment options, instant order confirmation, and prompt delivery to your door</p>
          </div>
          <div class="px-4 sm:px-0 grid lg:grid-cols-2 gap-y-6">
            <div>
              <h5 class="uppercase pb-2 font-semibold text-balance">Here to help</h5>
              <p class="mb-4 text-sm text-black hover:text-[#555]">
                Have a question? You may find an answer in our<span>&nbsp;</span>
                <a href="#faq" class="underline hover:text-[#555]">FAQs</a>.
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
                  <a href="tel:(+123)4567890" class="text-black hover:text-[#555] hover:underline">Call Us: ( +123 ) 456-7890</a>
                </div>
                <div class="flex items-center gap-3 text-13">
                  <x-heroicon-o-envelope class="size-3.5" aria-hidden="true" />
                  <a href="mailto:alaoeneye@gmail.com" class="text-black hover:text-[#555] hover:underline">Send us an email</a>
                </div>
              </div>
            </div>
            <div class="lg:px-6">
              <h5 class="uppercase pb-2 font-semibold text-balance">Follow Us</h5>
              <div class="space-y-2">
                <div class="flex items-center gap-3 text-13">
                  <x-ionicon-logo-facebook class="size-3.5" aria-hidden="true" />
                  <a href="https://www.facebook.com/oiza.awomokun" target="_blank" rel="noopener noreferrer" class="text-black hover:text-[#555] hover:underline">Facebook</a>
                </div>
                <div class="flex items-center gap-3 text-13">
                  <x-ionicon-logo-instagram class="size-3.5" aria-hidden="true" />
                  <a href="https://www.instagram.com/oiza_apparel/" target="_blank" rel="noopener noreferrer" class="text-black hover:text-[#555] hover:underline">Instagram</a>
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
            <img src="{{ asset('/img/Visamastercard.webp') }}" alt="Visa and Mastercard payment options" width="64" height="26" loading="lazy">
          </dl>
        </div>
      </section>
    </div>
  </footer>

  @yield('scripts')
</body>

</html>