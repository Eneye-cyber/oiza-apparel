@extends('layouts.main')

@section('title', 'Home Page')

@section('content')
<!-- Hero Section -->
<section class="py-5 md:py-13 bg-cream">
  <div class="container">
    <div class="grid md:grid-cols-2 gap-6">
      <div class="flex flex-col justify-center items-start gap-5 text-left">
        <h1 class=" text-6xl md:text-[90px] lg:text-[120px] leading-[0.9] font-playfair-display mt-5 mb-2.5" data-aos="zoom-in" data-aos-delay="150">Oiza Apparels</h1>
        <div data-aos="fade-up" data-aos-delay="200" class="space-y-5">
          <p class="text-[#222] text-base">Choose from a collection of high quality fabrics that suit your style</p>
          <a
            href="{{Route::has('shop') ? route('shop') : '/not-found'}}"
            class="box-border aspect-auto object-cover border border-black flex-none w-auto py-4 px-10 no-underline inline-block relative overflow-hidden group hover:border-primary">
            <span class="transition-all duration-300 z-10 font-semibold text-sm tracking-wider group-hover:text-white relative">SHOP NOW</span>
            <div class="transition-all duration-300 z-0 bg-primary rounded-full size-8 absolute top-auto right-[41%] bottom-[-65%] left-auto group-hover:scale-[1000%] group-hover:scale-z-100 transform-style-3d"></div>
          </a>

        </div>
      </div>


      <!-- Hero Image  -->
      <div class="grid grid-cols-2 gap-4 md:gap-6">
        <div class="flex flex-col" data-aos="fade-up">
          <div class="object-fit overflow-clip mt-auto relative">
            <div class="gradient-element"></div>
            <!--  -->
            <!-- <img class="object-fill" src="https://cdn.prod.website-files.com/66d5c8a0f16078270af4fa77/66d5c8b6f65633bf7dd0ddd7_Image-02.jpg" alt="hero-1" loading="eager" width="308" height="450"> -->
            <img class="object-fill xl:h-[436px]" src="{{ asset('img/hero_1.jpg') }}" alt="hero-1" loading="eager" width="308" height="450">
            <!-- <img class="object-fill" src="https://i.pinimg.com/1200x/3e/4e/10/3e4e104858d8110e43458ae8e8ff28d1.jpg" alt="hero-1" loading="eager" width="308" height="450"> -->
          </div>
        </div>

        <div class="flex flex-col gap-3.5" data-aos="fade-down">
          <div class="flex-1">
            <div class="object-fit overflow-clip relative">
              <div class="gradient-element -rotate-y-180"></div>

              <!-- <img class="object-fill" src="https://cdn.prod.website-files.com/66d5c8a0f16078270af4fa77/66d5c8b63cd3dcbf3d12ce24_Image-01.jpg" alt="hero-1" loading="eager" width="308" height="498"> -->
              <img class="object-fill" src="{{ asset('img/hero_2.jpg') }}" alt="hero-2" loading="eager" width="308" height="498">
            </div>

          </div>
          <a href="/" class="flex flex-center gap-2 transition-all group hover:text-primary">
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
    <div class="grid md:grid-cols-3 gap-3" data-aos="fade-up">
      <figure class="flex items-center gap-2.5">
        <x-ionicon-diamond-outline class="size-8 text-primary stoke-1" />
        <figcaption class="text-sm">
          <p class="font-semibold">Certified</p>
          <span class="opacity-75">Certified of authencity</span>
        </figcaption>
      </figure>

      <figure class="flex justify-center items-center gap-2.5 border-x border-black/30">
        <x-heroicon-o-lock-closed class="size-8 text-primary" />
        <figcaption class="text-sm">
          <p class="font-semibold">Secure</p>
          <span class="opacity-75">Certified marketplace</span>
        </figcaption>
      </figure>

      <figure class="flex justify-end items-center gap-2.5">
        <x-heroicon-o-truck class="size-8 text-primary stroke-1" />
        <figcaption class="text-sm">
          <p class="font-semibold">Shipping</p>
          <span class="opacity-75">Fast and reliable delivery</span>
        </figcaption>
      </figure>
    </div>
  </div>
</section>

<!-- New arrivals  -->
<article class="py-8">
  <div class="container">
    <div class="flex justify-between items-baseline flex-wrap" data-aos="fade-up" data-aos-delay="100">
      <h3 class="section-title-text">New Arrivals</h3>
      <a href="{{ route('shop') }}" class="flex flex-center gap-2 transition-all group hover:text-primary">
        <h5 class="font-medium">View Full Collection</h5>
        <x-heroicon-c-arrow-right class="h-3.5 w-auto stroke-2 text-primary group-hover:text-black" />
      </a>
    </div>

    <section role="list" id="products-listing" class="product-list grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 py-8" data-aos="fade-up" data-aos-delay="200">
      @forelse($products as $product)
        <x-product-card :product="$product" :href="route('shop.product', ['product' => $product['slug']])" />
      @empty
          <div class="col-span-full flex flex-col items-center justify-center py-12 text-center">
            <x-heroicon-o-shopping-bag class="h-16 w-16 text-gray-400 mb-4" />
            <h4 class="text-xl font-semibold text-gray-700 mb-2">No Products Available</h4>
            <p class="text-gray-500 max-w-md mb-6">It looks like we don't have any products to display yet. Check back soon for new arrivals!</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-md hover:bg-primary-dark transition-colors">
              Contact Support
            </a>
          </div>
      @endforelse
      
    </section>
  </div>
</article>

<!-- Grid Promotional Collections banners -->
<div class="container py-12">
  <div class="grid md:grid-cols-2 gap-8">
    <div data-aos="zoom-in-right" class="h-72 py-16 relative flex flex-center bg-no-repeat bg-cover bg-[url('https://media.istockphoto.com/id/2023459576/photo/textile-clothe.jpg?s=2048x2048&w=is&k=20&c=9FaCglBCZ7b3H-ZGVmqZQeKyXjPbhuT1MOXoQbyQDZY=')]">
    </div>

    <div data-aos="zoom-in-left" class="h-72 py-16 relative flex flex-center bg-no-repeat bg-cover bg-right-top bg-[url('https://media.istockphoto.com/id/869548636/photo/colorful-fabric.jpg?s=2048x2048&w=is&k=20&c=HI6MnPQCVCMpU0Fmn268S0hMECeV7T33O3tizTbt-cQ=')]">
    </div>
  </div>
</div>
<!-- Featured Collection  -->
<article class="py-8">
  <div class="container">
    <div class="text-center">
      <h3 class="section-title-text">Featured Collection</h3>
    </div>

    <section role="list" id="featured-products" class="product-list grid sm:grid-cols-2 md:grid-cols-3 gap-8 py-14">
      @foreach($featured as $product)
        <x-product-card :product="$product" :href="route('shop.product', ['product' => $product['slug']])" />
      @endforeach
    </section>

    <div class="text-center">
      <a
        href="#"
        class="box-border aspect-auto object-cover border border-black flex-none w-auto py-4 px-10 no-underline inline-block relative overflow-hidden group hover:border-primary">
        <span class="transition-all duration-300 z-10 font-semibold text-sm tracking-wider group-hover:text-white relative">VIEW ALL COLLECTION</span>
        <div class="transition-all duration-300 z-0 bg-primary rounded-full size-8 absolute top-auto right-[41%] bottom-[-65%] left-auto group-hover:scale-[1000%] group-hover:scale-z-100 transform-style-3d"></div>
      </a>
    </div>
  </div>
</article>

<!-- Promotional banner  -->
<div class="container py-16">
  <!-- https://www.freepik.com/premium-photo/decorative-pattern-fabric-thai-traditional-style-fabric_22163297.htm#fromView=search&page=1&position=35&uuid=d46d82ac-4ff4-4acf-9f0c-ff7b6255c90a&query=ankara+print -->
  <div class="h-96 py-16 relative flex flex-center bg-center bg-no-repeat" style="background-image: url('{{ asset('img/decorative-bg.jpg') }}');">
    <div class="gradient-element"></div>
  </div>
</div>
<!-- FAQ  -->

<section class="bg-cream">
  <div class="container py-20" id="faq">
    <div class="flex flex-col gap-16">
      <div class="text-center">
        <h3 class="section-title-text">Frequently Asked Questions</h3>
      </div>

      <div class="grid md:grid-cols-2 gap-4">
        @forelse($faq as $item)
          <details class="group transition-all duration-300 bg-cream border border-gold [&::-webkit-details-marker]:hidden open:bg-white">
            <summary class="p-4 flex items-center gap-4 text-lg font-semibold text-black cursor-pointer hover:bg-gray-50 group-open:hover:bg-white [&::-webkit-details-marker]:hidden">
              <x-heroicon-o-plus class="size-4 stroke-3 text-gold" />
              {{ $item->question }}
            </summary>
            <div class="p-4 text-black opacity-80 border-t border-white">
              {{ $item->answer }}
            </div>
          </details>
        @empty
          <div class="col-span-1 md:col-span-2 text-center py-12">
            <div class="bg-cream border border-gold rounded-lg p-8 max-w-2xl mx-auto">
              <h4 class="text-xl font-semibold text-black mb-2">No FAQs Available</h4>
              <p class="text-black opacity-80">
                We currently don’t have any frequently asked questions to display. Please check back later or contact us for assistance.
              </p>
              <a href="mailto:sample@mail.com.ng" class="mt-4 inline-block text-gold hover:text-yellow-600 font-semibold underline">
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
<section class="py-20">
  <div class="container relative group">
    <div class="grid grid-cols-4 gap-4 h-[360px]">
      <img src="/img/imgi_3.webp" alt="instagram image 1" loading="lazy" class="block w-full h-[360px] object-cover">
      <img src="/img/imgi_2.webp" alt="instagram image 2" loading="lazy" class="block w-full h-[360px] object-cover">
      <img src="/img/imgi_9.webp" alt="instagram image 3" loading="lazy" class="block w-full h-[360px] object-cover">
      <img src="/img/imgi_1.jpg" alt="instagram image 4" loading="lazy" class="block w-full h-[360px] object-cover">
    </div>

    <a href="https://www.instagram.com/oiza_apparel/" target="_blank" rel="noopener noreferrer"
      class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-black font-semibold gap-2.5 bg-white shadow-[0_20px_160px_100px_white] shadow-cream border border-primary w-auto py-6 px-9 flex flex-center absolute top-1/2 left-1/2 -translate-1/2 overflow-hidden">
      <x-ionicon-logo-instagram class="size-5" />

      <span class="text-lg font-normal mb-1 text-primary">@oiza_apparel</span>
    </a>
  </div>
</section>

<x-modals.quick-view />
@endsection