@extends('layouts.main')

@section('title', 'Order Status | Oiza Apparels')
@section('description', 'Check the status of your order with Oiza Apparels. View details after successful payment or retry if payment failed.')
@section('og_title', 'Order Status - Oiza Apparels')
@section('og_description', 'Track your order status at Oiza Apparels. Confirm successful payments or handle failed transactions for your apparel purchases.')
@section('og_image', asset('/img/order-status-hero.jpg'))

@section('content')
<section class="py-14 md:py-16 bg-cream">
  <div class="container">
    <div class="text-center">
      <h5 class="tracking-[6px] font-medium text-gold text-sm mb-2">ORDER STATUS</h5>
      <h1 class="section-title-text md:text-5xl font-medium text-black">Track Your Order</h1>
      <p class="text-base text-black opacity-80 mt-4 lg:max-w-xl mx-auto text-justify">
        View the status of your recent order. If your payment was successful, you'll see confirmation details below. If it failed, we provide options to retry.
        <br><br>
        For any issues or questions about your order, feel free to contact us.
      </p>
    </div>

    <!-- Order Status Display -->
    <div class="bg-white p-4 sm:p-8 mt-8">
      @if(session('success'))
        <div class="text-center py-8">
          <x-heroicon-o-check-circle class="size-16 text-green-600 mx-auto mb-4" aria-hidden="true" />
          <h2 class="text-2xl font-semibold text-black mb-2">Payment Successful!</h2>
          <p class="text-black opacity-80 mb-6">Your order has been placed successfully. Thank you for shopping with Oiza Apparels.</p>
          
          <div class="bg-cream border border-black/10 rounded-md p-6 max-w-md mx-auto">
            <h3 class="font-semibold text-black mb-2">Order Details</h3>
            <p class="text-sm text-black opacity-80"><strong>Order ID:</strong> {{ session('order_number') ?? 'N/A' }}</p>
            <p class="text-sm text-black opacity-80"><strong>Total Amount:</strong> <span id="orderAmount">{{ session('order_amount') ?? 'N/A' }}</span></p>
            <p class="text-sm text-black opacity-80"><strong>Status:</strong> Confirmed</p>
            <p class="text-sm text-black opacity-80"><strong>Estimated Delivery:</strong> {{ session('estimated_delivery')  ?? 'N/A'}}</p>
          </div>
          
          <a href="{{ route('shop') }}" class="btn-solid uppercase mt-6 inline-block">
            Continue Shopping
          </a>
        </div>
      @elseif(session('error'))
        <div class="text-center py-8">
          <x-heroicon-o-x-circle class="size-16 text-red-600 mx-auto mb-4" aria-hidden="true" />
          <h2 class="text-2xl font-semibold text-black mb-2">Payment Failed</h2>
          <p class="text-black opacity-80 mb-6">We're sorry, your payment could not be processed. Please try again or contact support if the issue persists.</p>
          
          <div class="flex flex-col sm:flex-row gap-4 justify-center mt-6">
              <a href="{{ route('contact') }}" class="w-full btn-outline uppercase px-6 py-3 border border-black text-black hover:text-primary hover:border-primary">
              Contact Support
            </a>
            <a href="{{ route('checkout') }}" class="btn-solid hover:bg-primary hover:border-primary uppercase px-6 py-3">
              Retry Payment
            </a>
          
          </div>
        </div>
      @else
        <div class="text-center py-8">
          <h2 class="text-2xl font-semibold text-black mb-4">Enter Order Details to Check Status</h2>
          <form 
          action="{{ route('payment.callback') }}" 
          method="GET" class="max-w-md mx-auto space-y-4">
            @csrf
            <div>
              <label for="paymentReference" class="text-sm font-semibold text-black">Order ID</label>
              <input
                type="text"
                id="paymentReference"
                name="paymentReference"
                class="mt-2 w-full border border-black rounded-md py-3 px-4 text-black focus:outline-none focus:border-[#555]"
                placeholder="Enter your Order ID"
                value="{{ old('paymentReference', '') }}"
                required
                aria-describedby="order-error">
              @error('paymentReference')
              <span id="order-error" class="text-red-600 text-sm mt-1">{{ $message }}</span>
              @enderror
            </div>
            <button type="submit" class="btn-solid uppercase w-full">
              Check Status
            </button>
          </form>
        </div>
      @endif
    </div>
  </div>
</section>

<section>
  <div class="container py-14 md:py-16">
    <div class="text-center">
      <h3 class="section-title-text">Order Support</h3>
    </div>
    <div class="py-12 grid md:grid-cols-3 gap-3">
      <div class="flex items-center gap-3">
        <x-heroicon-o-phone class="size-5 lg:size-8 text-black" aria-hidden="true" />
        <div>
          <p class="text-sm font-semibold text-black">Phone</p>
          <a href="tel:(+123)4567890" class="text-black hover:text-[#555] hover:underline">
            (+123) 456-7890
          </a>
        </div>
      </div>
      <div class="flex flex-center max-md:border-y max-md:py-2 md:border-x border-black/15 gap-3">
        <x-heroicon-o-envelope class="size-5 lg:size-8 text-black" aria-hidden="true" />
        <div>
          <p class="text-sm font-semibold text-black">Email</p>
          <a href="mailto:alaoeneye@gmail.com" class="text-black hover:text-[#555] hover:underline">
            alaoeneye@gmail.com
          </a>
        </div>
      </div>
      <div class="flex justify-end items-center gap-3">
        <x-heroicon-o-clock class="size-5 lg:size-8 text-black" aria-hidden="true" />
        <div>
          <p class="text-sm font-semibold text-black">Hours</p>
          <p class="text-black opacity-80">Mon-Fri: 9:00 AM - 6:00 PM</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="pb-20">
  <div class="container py-16 border-y border-black/35 space-y-6">
    <div class="text-center">
      <h3 class="section-title-text">Stay Connected</h3>
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

<!-- FAQ -->

@endsection

@section('scripts')
<script>
    const CURRENCY_CODE = 'NGN';
    const LOCALE = 'en-NG';

    function formatMoneySafe(amount, currencyCode = CURRENCY_CODE, locale = LOCALE, options = {}) {
      if (amount === null || amount === undefined) return 'N/A';
      const n = Number(amount);
      if (Number.isNaN(n)) return '';
      const defaultOptions = { minimumFractionDigits: 2, maximumFractionDigits: 2, ...options };
      try {
        return new Intl.NumberFormat(locale, { style: 'currency', currency: currencyCode, ...defaultOptions }).format(n);
      } catch (err) {
        // fallback simple formatting
        return `${currencyCode} ${(n).toFixed(defaultOptions.minimumFractionDigits)}`;
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      const priceSpan = document.getElementById('orderAmount')
      const amount = formatMoneySafe({{ session('order_amount') }})
      if(priceSpan) {
        priceSpan.textContent = amount
      }
    })
</script>
@endsection