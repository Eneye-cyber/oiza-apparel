<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Checkout - Oiza Apparels</title>
  <meta name="description"
    content="Complete your purchase at Oiza Apparels. Secure checkout, multiple payment options, and fast delivery.">


  <!-- Preload Key Resources -->
  <link rel="preload" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" as="style">
  <link rel="preload" href="{{ Vite::asset('resources/css/app.css') }}" as="style">

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
    @vite(['resources/css/checkout.css', 'resources/js/app.js'])
  @else
    <link rel="stylesheet" href="{{ asset('/fallback.css') }}">
  @endif
</head>

<body class="bg-white text-black overflow-x-clip">


  <main class="font-dm-sans min-h-80">
    <article class="pb-12">

      {{-- Checkout Page Content --}}
      <div class="mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
          {{-- Left: Checkout Form --}}
          <section class="md:col-span-3 flex h-full py-8">
            <div class="ml-auto max-w-xl w-full h-full">
              <header class="space-y-2">
                <h2>
                  <a href="/"
                    class="font-playfair-display text-2xl font-medium text-primary drop-shadow-xs drop-shadow-primary/20"
                    aria-label="Oiza Apparels Home">
                    OIZA
                  </a>
                </h2>
                <div class="pb-5">
                  <div>
                    <nav aria-label="breadcrumb">
                      <ol itemscope itemtype="https://schema.org/BreadcrumbList" class="flex items-center gap-2.5">

                        {{-- Home --}}
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                          <a itemprop="item" href="/"
                            class="text-xs text-black opacity-80 flex gap-2.5 items-center">
                            <span itemprop="name">Home</span>
                            <x-heroicon-o-chevron-right class="h-3 w-auto" />
                          </a>
                          <meta itemprop="position" content="1" />
                        </li>

                        {{-- Products --}}
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                          <a itemprop="item" href="/shop"
                            class="text-xs text-black opacity-80 flex gap-2.5 items-center">
                            <span itemprop="name">Shop</span>
                            <x-heroicon-o-chevron-right class="h-3 w-auto" />
                          </a>
                          <meta itemprop="position" content="2" />
                        </li>

                        {{-- Cart --}}
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                          <a itemprop="item" href="{{ Route::has('cart') ? route('cart') : '#'}}"
                            class="text-xs text-black opacity-80 flex gap-2.5 items-center">
                            <span itemprop="name">Cart</span>
                            <x-heroicon-o-chevron-right class="h-3 w-auto" />
                          </a>
                          <meta itemprop="position" content="3" />
                        </li>

                        {{-- Checkout --}}
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                          <a itemprop="item" href="{{ route('checkout') }}"
                            class="text-xs text-black opacity-80 flex gap-2.5 items-center breadcrumb-link-active">
                            <span itemprop="name">Checkout</span>
                            <x-heroicon-o-chevron-right class="h-3 w-auto opacity-0" />
                          </a>
                          <meta itemprop="position" content="4" />
                        </li>
                      </ol>
                    </nav>
                  </div>
                </div>

              </header>
              {{-- <h1 class="text-3xl font-bold mb-6">Checkout</h1> --}}

              {{-- Guest vs. Account Creation --}}
              <section class="mb-4">
                <h2 class="text-lg text-black font-semibold mb-4">Account Information</h2>
                <div class="space-y-4">
                  <p class="text-sm text-black opacity-80">Already have an account? <a href="{{ Route::has('login') ? route('login') : '#'}}" class="text-primary hover:underline">Sign in</a> for faster checkout.</p>
                  <p class="text-sm text-black opacity-80">Or continue as a guest.</p>
                </div>
              </section>

              {{-- Contact Information & Account Creation --}}
              <section class="mb-8">
                <h2 class="text-lg text-black font-semibold mb-4">Contact Information</h2>
                <div class="space-y-4 ">
                  <div class="relative">
                    <input type="email" id="email" name="email" placeholder="" class="floating-input peer" autocomplete="email" required>
                    <label for="email" class="floating-label-input floating-label absolute !peer-not-placeholder-shown:top-[-0.5rem]">Email address *</label>
                  </div>
                  <div class="relative">
                    <input type="tel" id="phone" name="phone" placeholder="" class="floating-input peer" autocomplete="tel" required>
                    <label for="phone" class="floating-label-input floating-label absolute">Phone *</label>
                  </div>

                  {{-- Account creation --}}

                  <div class="flex items-center">
                    <input type="checkbox" id="create_account" name="create_account" class="mr-2">
                    <label for="create_account" class="text-sm">Create an account to save your details for future purchases.</label>
                  </div>
                  <div id="password_fields" class="space-y-4 hidden">
                    <div class="relative">
                      <input type="password" id="password" name="password" placeholder="" class="floating-input peer">
                      <label for="password" class="floating-label-input floating-label absolute">Password</label>
                    </div>
                    <div class="relative">
                      <input type="password" id="password_confirmation" name="password_confirmation" placeholder="" class="floating-input peer">
                      <label for="password_confirmation" class="floating-label-input floating-label absolute">Confirm Password</label>
                    </div>
                  </div>
                </div>
              </section>

              {{-- Shipping Address --}}
              <section class="mb-8">
                <h2 class="text-lg text-black font-semibold mb-4">Shipping Address</h2>

                <div class="relative ">
                  <select id="country" name="country" class="floating-select peer" autocomplete="country-name" required>
                    <option value="Nigeria">Nigeria</option>
                    <!-- Add options as needed -->
                  </select>
                  <label for="country" class="floating-label-select floating-label absolute">Country *</label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                  <div class="relative">
                    <input type="text" id="first_name" name="first_name" class="floating-input peer"
                      placeholder=" " autocomplete="given-name" required />
                    <label for="first_name" class="floating-label-input floating-label absolute">First Name *</label>
                  </div>
                  <div class="relative">
                    <input type="text" id="last_name" name="last_name" class="floating-input peer"
                      placeholder=" " autocomplete="family-name" required />
                    <label for="last_name" class="floating-label-input floating-label absolute">Last Name *</label>
                  </div>
                </div>


                <div class="relative mt-4">
                  <input type="text" id="address" name="address" class="floating-input peer"
                    placeholder=" " autocomplete="street-address" required />
                  <label for="address" class="floating-label-input floating-label absolute">Address *</label>
                </div>
                <div class="relative mt-4">
                  <input type="text" id="apartment" name="apartment" class="floating-input peer"
                    placeholder=" " autocomplete="address-line2" />
                  <label for="apartment" class="floating-label-input floating-label absolute">Apartment, suite, etc.
                    (optional)</label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                  <div class="relative">
                    <input type="text" id="city" name="city" class="floating-input peer"
                      placeholder=" " autocomplete="address-level2" required />
                    <label for="city" class="floating-label-input floating-label absolute">City *</label>
                  </div>
                  <div class="relative">
                    <select id="state" name="state" class="floating-select peer" autocomplete="address-level1" required>
                      <option value=""></option>
                      <!-- Add options as needed -->
                    </select>
                    <label for="state" class="floating-label-select floating-label absolute">State *</label>
                  </div>
                  <div class="relative">
                    <input type="text" id="zip" name="zip" class="floating-input peer"
                      placeholder=" " autocomplete="postal-code" required />
                    <label for="zip" class="floating-label-input floating-label absolute">ZIP
                      Code *</label>
                  </div>
                </div>

              </section>

              {{-- Shipping Method Selection --}}
              <section class="mb-8">
                <h2 class="text-lg text-black font-semibold mb-4">Shipping Method</h2>
                <div class="space-y-4">
                  <div class="flex items-center">
                    <input type="radio" id="standard_shipping" name="shipping_method" value="standard" class="mr-2" checked>
                    <label for="standard_shipping" class="text-sm">Standard Shipping (3-5 business days) - Free</label>
                  </div>
                  <div class="flex items-center">
                    <input type="radio" id="express_shipping" name="shipping_method" value="express" class="mr-2">
                    <label for="express_shipping" class="text-sm">Express Shipping (1-2 business days) - ₦5,000</label>
                  </div>
                </div>
              </section>

              {{-- Billing Address --}}
              <section class="mb-8">
                <h2 class="text-lg text-black font-semibold mb-4">Billing Address</h2>
                <div class="flex items-center mb-4">
                  <input type="checkbox" id="billing_same_as_shipping" name="billing_same_as_shipping" class="mr-2" checked>
                  <label for="billing_same_as_shipping" class="text-sm">Same as shipping address</label>
                </div>
                <div id="billing_fields" class="space-y-4 hidden">
                  <div class="relative ">
                    <select id="billing_country" name="billing_country" class="floating-select peer" autocomplete="billing country-name" required>
                      <option value="">Nigeria</option>
                      <!-- Add options as needed -->
                    </select>
                    <label for="billing_country" class="floating-label-select floating-label absolute">Country *</label>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative">
                      <input type="text" id="billing_first_name" name="billing_first_name" class="floating-input peer"
                        placeholder=" " autocomplete="billing given-name" required />
                      <label for="billing_first_name" class="floating-label-input floating-label absolute">First Name *</label>
                    </div>
                    <div class="relative">
                      <input type="text" id="billing_last_name" name="billing_last_name" class="floating-input peer"
                        placeholder=" " autocomplete="billing family-name" required />
                      <label for="billing_last_name" class="floating-label-input floating-label absolute">Last Name *</label>
                    </div>
                  </div>


                  <div class="relative">
                    <input type="text" id="billing_address" name="billing_address" class="floating-input peer"
                      placeholder=" " autocomplete="billing street-address" required />
                    <label for="billing_address" class="floating-label-input floating-label absolute">Address *</label>
                  </div>
                  <div class="relative">
                    <input type="text" id="billing_apartment" name="billing_apartment" class="floating-input peer"
                      placeholder=" " autocomplete="billing address-line2" />
                    <label for="billing_apartment" class="floating-label-input floating-label absolute">Apartment, suite, etc.
                      (optional)</label>
                  </div>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative">
                      <input type="text" id="billing_city" name="billing_city" class="floating-input peer"
                        placeholder=" " autocomplete="billing address-level2" required />
                      <label for="billing_city" class="floating-label-input floating-label absolute">City *</label>
                    </div>
                    <div class="relative">
                      <select id="billing_state" name="billing_state" class="floating-select peer" autocomplete="billing address-level1" required>
                        <option value=""></option>
                        <!-- Add options as needed -->
                      </select>
                      <label for="billing_state" class="floating-label-select floating-label absolute">State *</label>
                    </div>
                    <div class="relative">
                      <input type="text" id="billing_zip" name="billing_zip" class="floating-input peer"
                        placeholder=" " autocomplete="billing postal-code" required />
                      <label for="billing_zip" class="floating-label-input floating-label absolute">ZIP
                        Code *</label>
                    </div>
                  </div>
                </div>
              </section>

              {{-- Order Notes --}}
              <section class="mb-8">
                <h2 class="text-lg text-black font-semibold mb-4">Order Notes</h2>
                <div class="relative">
                  <textarea id="order_notes" name="order_notes" class="floating-input peer h-24" placeholder=" "></textarea>
                  <label for="order_notes" class="floating-label-input floating-label absolute">Special instructions for delivery (optional)</label>
                </div>
              </section>

              {{-- Terms and Conditions Agreement --}}
              <div class="mb-8 flex items-center">
                <input type="checkbox" id="terms_agreement" name="terms_agreement" class="mr-2" required>
                <label for="terms_agreement" class="text-sm">I agree to the <a href="{{ route('terms') }}" class="text-primary hover:underline">Terms & Conditions</a> and <a href="{{ route('privacy') }}" class="text-primary hover:underline">Privacy Policy</a> *</label>
              </div>

              <a href="{{ Route::has('cart') ? route('cart') : '#'}}" class="text-sm text-primary hover:underline mb-4 block">Return to Cart</a>

              {{-- <button type="submit"
                class="w-full bg-primary text-white py-3 px-4 rounded-md font-semibold hover:bg-opacity-90">Pay
                Now</button> --}}
                <footer class="mt-16 py-4 border-t border-whitesmoke flex justify-between">
                  <dl class="w-auto flex items-center text-xs gap-3 sm:gap-8 text-black">
                    <a href="{{ route('privacy') }}" class="text-black hover:text-[#555]">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="text-black hover:text-[#555]">Terms &amp; Condition</a>
                    <a href="{{ route('sitemap') }}" class="text-black hover:text-[#555]">Sitemap</a>
                  </dl>

                  <img class="" src="{{ asset('/img/Visamastercard.webp') }}" alt="Visa and Mastercard payment options"
                    width="48" height="16" loading="lazy">
                </footer>
            </div>

          </section>

          {{-- Right: Sticky Order Summary --}}
          <aside class="md:col-span-2 md:sticky md:top-0 md:self-start md:h-svh bg-cream/10 p-6 py-9">
            <div class="w-full max-w-96">
              {{-- <h2 class="text-lg text-black font-semibold mb-4">Order Summary</h2> --}}
              <div class="space-y-4">
                {{-- Sample Items --}}
                <div class="space-y-4">
                  @foreach ($cartItems as $item)
                    <div class="flex gap-3">
                      <div class="relative">
                        <img src="{{ $item->product['cover_media_url'] ?? '/placeholder.svg' }}"
                          alt="{{ $item->product->name }}"
                          class="w-16 h-16 rounded-lg object-cover bg-muted ring-2 ring-offset-1 ring-white" />
                        <span
                          class="absolute -top-2 -right-2 w-5 h-5 p-0 flex items-center justify-center text-xs bg-primary text-white rounded-sm ring-2 ring-offset-1 ring-white">
                          {{ $item->quantity }}
                        </span>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ $item->product->name }}</p>
                        <p class="text-sm text-black opacity-60">₦{{ $item->price }}</p>
                      </div>
                      <div class="text-sm font-medium">
                        ₦{{ number_format($item->price * $item->quantity, 2) }}</div>
                    </div>
                  @endforeach

                </div>

                <hr class="my-4 border-t border-black/10" />

                {{-- Promo Code --}}
                <div class="mb-4">
                  <h3 class="text-sm font-medium mb-2">Promo Code</h3>
                  <div class="flex gap-2">
                    <input type="text" id="promo_code" name="promo_code" placeholder="Enter code" class="floating-input peer flex-1">
                    <button type="button" class="bg-primary text-white py-2 px-4 rounded-sm font-medium hover:bg-opacity-90">Apply</button>
                  </div>
                </div>

                <hr class="my-4 border-t border-black/10" />

                <div class="space-y-4">
                  <div class="flex justify-between text-sm">
                    <span class="text-black opacity-60">Subtotal</span>
                    <span>₦{{ number_format($subtotal, 2) }}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-black opacity-60">Shipping</span>
                    <span class="text-accent font-medium">Free</span>
                  </div>

                  <hr class="my-4 border-t border-black/10" />

                  <div class="flex justify-between font-semibold text-lg">
                    <span>Total</span>
                    <span>₦{{ number_format($subtotal, 2) }}</span>
                  </div>
                </div>

                {{-- Trust Badges --}}
                <div class="space-y-3 pt-4 border-t border-primary/20">
                  <div class="flex items-center gap-3 text-sm">
                    <x-heroicon-o-truck  class="w-4 h-4 text-primary" />
                    <span class="text-black opacity-60">Free shipping on orders over ₦50</span>
                  </div>
                  <div class="flex items-center gap-3 text-sm">
                    <x-heroicon-o-shield-check class="w-4 h-4 text-primary" />
                    <span class="text-black opacity-60">Secure 256-bit SSL encryption</span>
                  </div>
                </div>

                <button type="submit"
                class="w-full bg-primary text-white py-3 px-4 rounded-sm font-medium hover:bg-opacity-90">Pay
                Now</button>

                <button type="button"
                class="w-full flex items-center justify-center gap-2.5 text-emerald-700 px-4 rounded-sm font-medium hover:bg-opacity-90">
                  <x-ionicon-logo-whatsapp class="size-6 sm:size-8" aria-hidden="true" />
                  <span>Order Using WhatsApp</span>
                </button>

          </aside>
        </div>
      </div>
    </article>
  </main>

  <!-- Simple JS for toggles (e.g., create account, billing same as shipping) -->
  <script>
    document.getElementById('create_account').addEventListener('change', function() {
      document.getElementById('password_fields').classList.toggle('hidden', !this.checked);
    });

    document.getElementById('billing_same_as_shipping').addEventListener('change', function() {
      document.getElementById('billing_fields').classList.toggle('hidden', this.checked);
    });
  </script>

</body>

</html>