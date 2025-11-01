@extends('layouts.main')

@section('title', 'Home Page')

@section('modal')
  <x-modals.quick-view />
@endsection

@section('content')
  <!-- Hero Section -->
  <section class="py-5 md:py-13 bg-cream">
    <div class="container">
      <div class="grid md:grid-cols-2 gap-6">
        <div class="flex flex-col justify-center items-start gap-5 text-left">
          <h1
            class=" text-6xl md:text-[5.625rem] lg:text-[6.5rem] leading-[0.9] text-primary drop-shadow-sm drop-shadow-primary/20 font-playfair-display mt-5 mb-2.5"
            data-aos="zoom-in" data-aos-delay="150">Oiza Apparels</h1>
          <div data-aos="fade-up" data-aos-delay="200" data-aos-anchor-placement="top-bottom" class="space-y-5">
            <p class="text-[#222] text-base">Choose from a collection of high quality fabrics that suit your
              style</p>
            <a href="{{ Route::has('shop') ? route('shop') : '/not-found' }}"
              class="box-border aspect-auto object-cover border border-primary flex-none w-auto py-4 px-10 no-underline inline-block relative overflow-hidden group hover:border-primary">
              <span
                class="transition-all duration-300 z-10 font-semibold text-sm tracking-wider group-hover:text-white relative">SHOP
                NOW</span>
              <div
                class="transition-all duration-300 z-0 bg-primary rounded-full size-8 absolute top-auto right-[41%] bottom-[-65%] left-auto group-hover:scale-[1000%] group-hover:scale-z-100 transform-style-3d">
              </div>
            </a>

          </div>
        </div>


        <!-- Hero Image  -->
        <div class="grid grid-cols-2 gap-4">
          <div class="flex flex-col" data-aos="fade-up">
            <div class="object-fit overflow-clip mt-auto relative">
              <img class="object-cover xl:h-[436px] drop-shadow-2xl" src="{{ asset('img/hero_3.jpg') }}" alt="hero-1"
                loading="eager" width="308" height="450">
            </div>
          </div>

          <div class="flex flex-col gap-3.5" data-aos="fade-down">
            <div class="flex-1">
              <div class="object-fit overflow-clip relative">

                <img class="max-sm:h-[249px] object-fill drop-shadow-2xl" src="{{ asset('img/hero_2.jpg') }}"
                  alt="hero-2" loading="eager" width="308" height="498">
              </div>

            </div>
            <a href="/shop" class="flex flex-center gap-2 transition-all group hover:text-primary">
              <h5 class="max-sm:text-sm font-medium">View Full Collection</h5>
              <x-heroicon-c-arrow-right class="h-3.5 w-auto stroke-2 text-primary group-hover:text-black" />
            </a>
          </div>

        </div>
      </div>

    </div>
  </section>

  <!-- End Hero Section -->
  <!-- Value Section  -->
  <section class="py-6">
    <div class="container">
      <div class="grid md:grid-cols-3 gap-3" data-aos="zoom in" data-aos-anchor-placement="top-bottom">
        <figure class="py-2 md:py-0 flex items-center gap-3.5">
          <x-ionicon-diamond-outline class="size-8 text-primary stoke-1" />
          <figcaption class="text-sm tracking-wider">
            <p class="font-semibold">Certified</p>
            <span class="opacity-75">Certified of authencity</span>
          </figcaption>
        </figure>

        <figure class="py-2 md:py-0 flex md:justify-center items-center gap-3.5 md:border-x border-black/30">
          <x-heroicon-o-lock-closed class="size-8 text-primary" />
          <figcaption class="text-sm tracking-wider">
            <p class="font-semibold">Secure</p>
            <span class="opacity-75">Certified marketplace</span>
          </figcaption>
        </figure>

        <figure class="py-2 md:py-0 flex md:justify-end items-center gap-3.5">
          <x-heroicon-o-truck class="size-8 text-primary stroke-1" />
          <figcaption class="text-sm tracking-wider">
            <p class="font-semibold">Shipping</p>
            <span class="opacity-75">Fast and reliable delivery</span>
          </figcaption>
        </figure>
      </div>
    </div>
  </section>


  <!-- Grid Promotional Collections banners -->
  <div class="container py-12">
    <div class="grid lg:grid-cols-2 gap-6">

      <article data-aos="zoom-in-right" data-aos-anchor-placement="top-bottom"
        class="promo-banner flex flex-between group">
        <div class="promo-content">
          <div>
            <h3 class="promo-title group-hover:text-primary">Luxury Brocade</h3>
            <h4 class="promo-subtitle">Shine in silk-blend brocade dresses for unforgettable occasions.</h4>
          </div>
          <a href="/shop/fabric/lace/brocade" class="flex flex-center gap-2 transition-all group hover:text-primary">
            <h5 class="text-sm font-medium">View Full Collection</h5>
            <x-heroicon-c-arrow-right class="h-3.5 w-auto stroke-2 text-primary group-hover:text-black" />
          </a>
        </div>

        <figure class="promo-image group-hover:scale-105">
          <img class="max-w-full object-cover inline-block" src="{{ asset('img/mask.png') }}" alt="Luxury Brocade Dress"
            width="260" height="413" loading="lazy" />
        </figure>
      </article>

      <article data-aos="zoom-in-left" data-aos-anchor-placement="top-bottom"
        class="promo-banner flex flex-between group">
        <div class="promo-content">
          <div>
            <h3 class="promo-title group-hover:text-primary">Men's Fabric</h3>
            <h4 class="promo-subtitle">Elevate Your Style with Premium Men's <br /> Fabrics.</h4>
          </div>
          <a href="/" class="flex flex-center gap-2 transition-all group hover:text-primary">
            <h5 class="text-sm font-medium">View Full Collection</h5>
            <x-heroicon-c-arrow-right class="h-3.5 w-auto stroke-2 text-primary group-hover:text-black" />
          </a>
        </div>

        <figure class="promo-image group-hover:scale-105">
          <img class="max-w-full object-cover inline-block" src="{{ asset('img/promo_2.png') }}"
            alt="Men's Fabric materials" width="260" height="414" loading="lazy" />
        </figure>
      </article>

    </div>
  </div>


  <!-- New arrivals  -->
  <article class="max-md:pt-4 py-8">
    <div class="container">
      <div class="flex justify-between items-baseline flex-wrap" data-aos="fade-up" data-aos-delay="100"
        data-aos-anchor-placement="top-bottom">
        <h3 class="section-title-text">New Arrivals</h3>
        <a href="{{ route('shop') }}" class="flex flex-center gap-2 transition-all group hover:text-primary">
          <h5 class="font-medium">View Full Collection</h5>
          <x-heroicon-c-arrow-right class="h-3.5 w-auto stroke-2 text-primary group-hover:text-black" />
        </a>
      </div>

      <section role="list" id="products-listing"
        class="product-list grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 xl:gap-8 py-8" data-aos="fade-up"
        data-aos-delay="200">
        @forelse($products as $product)
          <x-product-card :product="$product" :href="route('shop.product', ['product' => $product['slug']])" />
        @empty
          <div class="col-span-full flex flex-col items-center justify-center py-12 text-center">
            <x-heroicon-o-shopping-bag class="h-16 w-16 text-gray-400 mb-4" />
            <h4 class="text-xl font-semibold text-gray-700 mb-2">No Products Available</h4>
            <p class="text-gray-500 max-w-md mb-6">It looks like we don't have any products to display yet.
              Check back soon for new arrivals!</p>
            <a href="{{ route('contact') }}"
              class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-md hover:bg-primary-dark transition-colors">
              Contact Support
            </a>
          </div>
        @endforelse

      </section>

      <div class="text-center py-8">
        <a href="/shop"
          class="box-border aspect-auto object-cover border border-black flex-none w-auto py-4 px-10 no-underline inline-block relative overflow-hidden group hover:border-primary">
          <span
            class="transition-all duration-300 z-10 font-semibold text-sm tracking-wider group-hover:text-white relative">VIEW
            ALL PRODUCTS</span>
          <div
            class="transition-all duration-300 z-0 bg-primary rounded-full size-8 absolute top-auto right-[41%] bottom-[-65%] left-auto group-hover:scale-[1000%] group-hover:scale-z-100 transform-style-3d">
          </div>
        </a>
      </div>
    </div>
  </article>

  <!-- Promotional banner  -->
  <div class="container py-16">
    <!-- https://www.freepik.com/premium-photo/decorative-pattern-fabric-thai-traditional-style-fabric_22163297.htm#fromView=search&page=1&position=35&uuid=d46d82ac-4ff4-4acf-9f0c-ff7b6255c90a&query=ankara+print -->
    <div class="h-96 py-16 relative flex flex-center bg-center bg-no-repeat"
      style="background-image: url('{{ asset('img/decorative-bg.jpg') }}');">
      <div class="gradient-element"></div>
    </div>
  </div>


  <!-- Featured Collection  -->
  <article class="py-8">
    <div class="container">
      <div class="text-center" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
        <h3 class="section-title-text">Featured Collection</h3>
      </div>

      <section role="list" id="featured-products"
        class="product-list grid sm:grid-cols-2 md:grid-cols-3 gap-8 py-14">
        @foreach ($featured as $product)
          <x-product-card :product="$product" :href="route('shop.product', ['product' => $product['slug']])" />
        @endforeach
      </section>

      <div class="text-center">
        <a href="/shop?isFeatured=true"
          class="box-border aspect-auto object-cover border border-black flex-none w-auto py-4 px-10 no-underline inline-block relative overflow-hidden group hover:border-primary">
          <span
            class="transition-all duration-300 z-10 font-semibold text-sm tracking-wider group-hover:text-white relative">VIEW
            ALL COLLECTION</span>
          <div
            class="transition-all duration-300 z-0 bg-primary rounded-full size-8 absolute top-auto right-[41%] bottom-[-65%] left-auto group-hover:scale-[1000%] group-hover:scale-z-100 transform-style-3d">
          </div>
        </a>
      </div>
    </div>
  </article>


  <!-- FAQ  -->

  <section class="bg-cream">
    <div class="container py-20" id="faq">
      <div class="flex flex-col gap-16">
        <div class="text-center">
          <h3 class="section-title-text">Frequently Asked Questions</h3>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
          @forelse($faq as $item)
            <details
              class="group transition-all duration-300 bg-cream border border-gold [&::-webkit-details-marker]:hidden open:bg-white">
              <summary
                class="p-4 flex items-center gap-4 text-lg font-semibold text-black cursor-pointer hover:bg-gray-50 group-open:hover:bg-white [&::-webkit-details-marker]:hidden">
                <x-heroicon-o-plus class="size-4 stroke-3 text-gold" />
                {{ $item->question }}
              </summary>
              <div class="p-4 text-black opacity-80 border-t border-white">
              {!! \Filament\Forms\Components\RichEditor\RichContentRenderer::make($item->answer)->toHtml() !!}
              </div>
            </details>
          @empty
            <div class="col-span-1 md:col-span-2 text-center py-12">
              <div class="bg-cream border border-gold rounded-lg p-8 max-w-2xl mx-auto">
                <h4 class="text-xl font-semibold text-black mb-2">No FAQs Available</h4>
                <p class="text-black opacity-80">
                  We currently don’t have any frequently asked questions to display. Please check back
                  later or contact us for assistance.
                </p>
                <a href="mailto:sample@mail.com.ng"
                  class="mt-4 inline-block text-gold hover:text-yellow-600 font-semibold underline">
                  Contact Support
                </a>
              </div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </section>

  <!-- Instagram showcase  -->
  <section class="w-full bg-background py-16 md:py-24">
    <div class="container mx-auto px-4 md:px-6">
      <div class="mb-12 flex flex-col items-center justify-center text-center">
        <div class="mb-4 flex items-center gap-2">
          <x-ionicon-logo-instagram class="h-6 w-6 text-foreground" />
          <span class="font-mono text-sm uppercase tracking-wider">Follow Us</span>
        </div>
        <h2 class="mb-4 font-serif text-4xl tracking-tight text-primary md:text-5xl lg:text-6xl">
          @oiza_apparel
        </h2>
        <p class="max-w-2xl text-balance text-base leading-relaxed text-muted-foreground md:text-lg">
          Discover our latest collections and behind-the-scenes moments. Join our community of style enthusiasts.
        </p>
      </div>

      <div class="mb-10 grid grid-cols-2 gap-2 md:grid-cols-3 md:gap-4 lg:gap-6">
        @foreach ($faq as $item)
          <a key="{{ $item->id }}" href="{{ $item?->link }}"
            class="group relative aspect-square overflow-hidden bg-muted transition-all duration-300 hover:opacity-90"
            target="_blank" rel="noopener noreferrer">
            <img src="{{ $item?->imageUrl ?? '/img/imgi_3.webp' }}" alt="{{ $item?->alt }}"
              class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
              loading="lazy" />
            <div
              class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
              <x-ionicon-logo-instagram class="h-8 w-8 text-white md:h-10 md:w-10" />
            </div>
          </a>
        @endforeach

      </div>

      <div class="text-center">
        <a class="btn-solid group inline-flex items-center gap-2 w-auto" href="https://www.instagram.com/oiza_apparel/"
          target="_blank" rel="noopener noreferrer">
          <x-ionicon-logo-instagram class="h-4 w-4 transition-transform group-hover:scale-110" />
          Follow on Instagram
        </a>
      </div>
    </div>
  </section>

  {{-- <section class="py-20">
        <div class="container relative group">
            <div class="grid grid-cols-4 gap-4 h-[360px]">
                <img src="/img/imgi_3.webp" alt="instagram image 1" loading="lazy"
                    class="block w-full h-[360px] object-cover">
                <img src="/img/imgi_2.webp" alt="instagram image 2" loading="lazy"
                    class="block w-full h-[360px] object-cover">
                <img src="/img/imgi_9.webp" alt="instagram image 3" loading="lazy"
                    class="block w-full h-[360px] object-cover">
                <img src="/img/imgi_1.jpg" alt="instagram image 4" loading="lazy"
                    class="block w-full h-[360px] object-cover">
            </div>

            <a href="https://www.instagram.com/oiza_apparel/" target="_blank" rel="noopener noreferrer"
                class="md:opacity-0 md:group-hover:opacity-100 md:transition-opacity duration-300 font-semibold gap-2.5 bg-white shadow-[0_20px_140px_20px_white] md:shadow-[0_20px_160px_100px_white] shadow-cream border border-primary w-auto py-6 px-9 flex flex-center absolute top-1/2 left-1/2 -translate-1/2 overflow-hidden text-primary">
                <x-ionicon-logo-instagram class="size-5" />

                <span class="text-lg font-normal mb-1">oiza_apparel</span>
            </a>
        </div>
    </section> --}}

@endsection
