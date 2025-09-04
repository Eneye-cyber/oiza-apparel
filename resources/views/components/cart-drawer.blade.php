<section class="cart transition-all duration-300 fixed inset-0 z-50 flex justify-end items-stretch bg-black/50"
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
            <button class="w-full bg-primary text-white py-3 text-center font-semibold tracking-wide">
                Continue to Checkout
            </button>
        </footer>
    </aside>
</section>


