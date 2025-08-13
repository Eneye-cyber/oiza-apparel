@extends('layouts.main')

@section('title', 'Contact Us | Oiza Apparels')
@section('description', 'Get in touch with Oiza Apparels for inquiries, support, or feedback. Reach us via phone, email, or our contact form.')
@section('og_title', 'Contact Oiza Apparels')
@section('og_description', 'Contact Oiza Apparels for support, inquiries, or feedback about our high-quality fabrics and apparel collections.')
@section('og_image', asset('/img/contact-hero.jpg'))

@section('content')
<section class="py-14 md:py-16 bg-cream ">
  <div class="container">


    <div class="grid lg:grid-cols-2 gap-12">

      <!-- Contact Information -->
      <div class="flex flex-col justify-center gap-6">
        <div class="">
          <h5 class=" tracking-[6px] font-medium text-gold text-sm mb-2">CONTACT US</h5>
          <h1 class="font-playfair-display text-4xl md:text-5xl font-medium text-black">Get in Touch</h1>
          <p class="text-base text-black opacity-80 mt-4 lg:max-w-xl text-justify">
            Have a question or need assistance? We're here to help with your fabric and apparel needs. Reach out via the form below or our contact details.
            <br>
            <br>
            Whether you have questions about our fabric collections, need assistance with an order, or simply want to share your thoughts, please don't hesitate to reach out to us.
          </p>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="bg-white p-4 sm:p-8">
        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
          @csrf
          <div>
            <label for="name" class="font-dm-sans text-sm font-semibold text-black">Name</label>
            <input
              type="text"
              id="name"
              name="name"
              class="mt-2 w-full border border-black rounded-md py-3 px-4 font-dm-sans text-black focus:outline-none focus:border-[#555]"
              placeholder="Your Name"
              required
              aria-describedby="name-error">
            @error('name')
            <span id="name-error" class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
          <div>
            <label for="email" class="font-dm-sans text-sm font-semibold text-black">Email</label>
            <input
              type="email"
              id="email"
              name="email"
              class="mt-2 w-full border border-black rounded-md py-3 px-4 font-dm-sans text-black focus:outline-none focus:border-[#555]"
              placeholder="Your Email"
              required
              aria-describedby="email-error">
            @error('email')
            <span id="email-error" class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
          <div>
            <label for="message" class="font-dm-sans text-sm font-semibold text-black">Message</label>
            <textarea
              id="message"
              name="message"
              rows="3"
              class="mt-2 w-full border border-black rounded-md py-3 px-4 font-dm-sans text-black focus:outline-none focus:border-[#555]"
              placeholder="Your Message"
              required
              aria-describedby="message-error"></textarea>
            @error('message')
            <span id="message-error" class="text-red-600 text-sm mt-1">{{ $message }}</span>
            @enderror
          </div>
          <button
            type="submit"
            class="cursor-pointer border border-black bg-black text-white hover:border-[#555] hover:bg-black hover:text-white font-dm-sans font-medium text-sm tracking-wider py-4 px-10 transition-all duration-300 w-full">
            Send Message
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="container py-14 md:py-16">
    <div class="text-center">
      <h3 class="font-playfair-display text-3xl md:text-4xl">Contact Information</h3>
    </div>
    <div class="py-12 grid md:grid-cols-3 gap-3">
      <div class="flex items-center gap-3">
        <x-heroicon-o-phone class="size-5 lg:size-8 text-black" aria-hidden="true" />
        <div>
          <p class="font-dm-sans text-sm font-semibold text-black">Phone</p>
          <a href="tel:(+123)4567890" class="font-dm-sans text-black hover:text-[#555] hover:underline">
            (+123) 456-7890
          </a>
        </div>
      </div>
      <div class="flex justify-center items-center max-md:border-y max-md:py-2  md:border-x border-black/15 gap-3">
        <x-heroicon-o-envelope class="size-5 lg:size-8 text-black" aria-hidden="true" />
        <div>
          <p class="font-dm-sans text-sm font-semibold text-black">Email</p>
          <a href="mailto:alaoeneye@gmail.com" class="font-dm-sans text-black hover:text-[#555] hover:underline">
            alaoeneye@gmail.com
          </a>
        </div>
      </div>
      <div class="flex justify-end items-center gap-3">
        <x-heroicon-o-clock class="size-5 lg:size-8 text-black" aria-hidden="true" />
        <div>
          <p class="font-dm-sans text-sm font-semibold text-black">Hours</p>
          <p class="font-dm-sans text-black opacity-80">Mon-Fri: 9:00 AM - 6:00 PM</p>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="pb-20">
  <div class="container py-16 border-y border-black/35 space-y-6">
    <div class="text-center">
      <h3 class="font-playfair-display text-3xl md:text-4xl">Let's Get in Touch!</h3>
    </div>

    <div class="flex flex-center gap-4 mt-2">
      <a href="https://www.facebook.com/oiza.awomokun" target="_blank" rel="noopener noreferrer" class="text-black hover:text-[#555]" aria-label="Follow Oiza Apparels on Facebook">
        <x-ionicon-logo-facebook class="size-10" aria-hidden="true" />
      </a>
      <a href="https://www.instagram.com/oiza_apparel/" target="_blank" rel="noopener noreferrer" class="text-black hover:text-[#555]" aria-label="Follow Oiza Apparels on Instagram">
        <x-ionicon-logo-instagram class="size-10" aria-hidden="true" />
      </a>
    </div>
  </div>
</section>
<!-- FAQ  -->

<section class="bg-cream">
  <div class="container py-20">
    <div class="flex flex-col gap-16">
      <div class="text-center">
        <h3 class="font-playfair-display text-4xl">Frequently Asked Questions</h3>
      </div>

      <div class="grid md:grid-cols-2 gap-4">
        @foreach($faq as $item)
        <details class="group transition-all duration-300 bg-cream border border-gold [&::-webkit-details-marker]:hidden open:bg-white">
          <summary class="p-4 flex items-center gap-4 text-lg font-semibold text-black cursor-pointer hover:bg-gray-50 group-open:hover:bg-white [&::-webkit-details-marker]:hidden">
            <x-heroicon-o-plus class="size-4 stroke-3 text-gold" />
            {{ $item['question'] }}
          </summary>
          <div class="p-4 text-black opacity-80 border-t border-white">
            {{ $item['answer'] }}
          </div>
        </details>
        @endforeach

      </div>
    </div>
  </div>
</section>
@endsection