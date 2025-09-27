<section
  class="cart transition-all duration-300 fixed inset-0 z-50 h-svh overflow-clip flex justify-end items-stretch bg-black/50"
  id="cart-overlay">
  <aside class="w-full max-w-[480px] h-svh bg-white shadow-xl flex flex-col transition-all duration-300"
    id="cart-container" aria-label="Shopping cart">

    <header aria-label="Cart Header" class="py-4 px-6 border-b border-whitesmoke flex justify-between items-start">
      <div>
        <h3 id="cart-title" class="text-lg font-semibold opacity-90 tracking-wider text-primary">
          SHOPPING BAG (0)
        </h3>
        <p class="text-sm opacity-70 tracking-wide">Free shipping on orders over $75</p>
      </div>
      <button aria-label="Close cart" class="ml-auto inline-block py-1.5" onclick="closeCart()">
        <x-heroicon-s-x-mark class="size-6 stroke-3 font-semibold cursor-pointer" />
      </button>
    </header>

    <section class="flex-1 flex flex-col" id="cart-content">
      <p class="text-center text-base opacity-70 tracking-wide my-auto">
        Loading cart...
      </p>
    </section>

    <footer class="mt-auto py-4 px-6 border-t border-whitesmoke" id="cart-footer" style="display: none;">
      <div class="flex justify-between items-center mb-4">
        <span class="text-base opacity-90 tracking-wide">Subtotal</span>
        <span id="cart-subtotal" class="text-base font-semibold tracking-wider">₦0</span>
      </div>

      {{-- <a rel="nofollow" href="{{ route('checkout') }}"
        class="inline-block w-full bg-primary text-white py-3 text-center font-semibold tracking-wide">
        Continue to Checkout
      </a> --}}

      {{-- Terms and Conditions Agreement --}}
      <div class="mb-3 flex items-center">
        <input type="checkbox" id="terms_agreement" name="terms_agreement" class="mr-2">
        <label for="terms_agreement" class="text-sm">I agree to the <a href="{{ route('terms') }}"
            class="text-primary hover:underline">Terms & Conditions</a> and <a href="{{ route('privacy') }}"
            class="text-primary hover:underline">Privacy Policy</a></label>
      </div>
      <button id="checkout-btn"
        class="inline-block w-full bg-primary text-white py-3 text-center font-semibold tracking-wide">
        Continue to Checkout
      </button>
    </footer>
  </aside>
</section>


<script>
function proceedToCheckout(e) {
  e.preventDefault();
  const termsCheckbox = document.getElementById('terms_agreement');
  if (!termsCheckbox.checked) {
    termsCheckbox.classList.add('shake');
    setTimeout(() => termsCheckbox.classList.remove('shake'), 500);
    termsCheckbox.focus();
    return;
  }
  window.location.href = "{{ route('checkout') }}";
}

// Add shake animation CSS
const style = document.createElement('style');
style.textContent = `
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
  20%, 40%, 60%, 80% { transform: translateX(5px); }
}
.shake {
  animation: shake 0.5s ease-in-out;
}
`;
document.head.appendChild(style);

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
  const termsCheckbox = document.getElementById('terms_agreement');
  const checkoutBtn = document.getElementById('checkout-btn');

  function updateCheckoutButton() {
    if (termsCheckbox.checked) {
      checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
      checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
  }

  termsCheckbox.addEventListener('change', updateCheckoutButton);
  checkoutBtn.addEventListener('click', proceedToCheckout);
  updateCheckoutButton(); // Set initial state
});
</script>