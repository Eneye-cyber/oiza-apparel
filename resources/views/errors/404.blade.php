@extends('layouts.main')

@section('title', 'Page Not Found | Oiza Apparels')
@section('description', 'Oops! The page you’re looking for can’t be found. Explore our high-quality fabrics and collections at Oiza Apparels.')
@section('og_title', 'Page Not Found | Oiza Apparels')
@section('og_description', 'The page you’re looking for doesn’t exist. Discover our latest fabrics and apparel collections at Oiza Apparels.')
@section('og_image', asset('/img/oiza-logo.jpg'))

@section('content')
    <section class="bg-cream flex items-center justify-center py-12">
        <div class="container text-center">
            <div class="max-w-2xl mx-auto">
                <h1 class="font-playfair-display text-8xl md:text-9xl font-medium text-[#222] mb-6">404</h1>
                <h2 class="font-dm-sans text-2xl md:text-3xl font-semibold text-[#222] mb-4">Oops! Page Not Found</h2>
                <p class="font-dm-sans text-base text-[#222] opacity-80 mb-8">
                    It looks like the page you're looking for doesn't exist or has been moved. 
                    Let's get you back to exploring our beautiful collections!
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a 
                        href="{{ route('home') }}" 
                        class="box-border border border-[#222] hover:border-[#555] hover:bg-[#222] hover:text-white font-dm-sans font-medium text-sm tracking-wider py-4 px-10 no-underline transition-all duration-300"
                        aria-label="Return to Oiza Apparels homepage">
                        Back to Home
                    </a>
                    <a 
                        href="{{ Route::has('contact') ? route('contact') : '/contact' }}" 
                        class="box-border border border-[#222] hover:border-[#555] hover:bg-[#222] hover:text-white font-dm-sans font-medium text-sm tracking-wider py-4 px-10 no-underline transition-all duration-300"
                        aria-label="Contact Oiza Apparels support">
                        Contact Support
                    </a>
                </div>
                <div class="mt-12">
                    <img 
                        src="{{ asset('img/404-illustration.jpg') }}" 
                        alt="Fabric roll" 
                        class="mx-auto w-64 h-auto object-contain" 
                        style="filter:sepia(1)"
                        width="256"
                        height="204"
                        loading="lazy">
                </div>
            </div>
        </div>
    </section>
@endsection