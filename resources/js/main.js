import { CartSystem } from "./cart-system";
// Global variable for escape key handler
let escapeKeyHandler = null;

// Cache DOM elements
const domCache = {
    cartOverlay: null,
    cartContent: null,
    cartTitle: null,
    cartFooter: null,
    cartSubtotal: null,
    headerCartIndicator: null,
    quickViewModal: null,
    csrfToken: null
};

CartSystem.init();

// Initialize DOM cache
function initCache() {
    domCache.cartOverlay = document.getElementById('cart-overlay');
    domCache.cartContent = document.getElementById('cart-content');
    domCache.cartTitle = document.getElementById('cart-title');
    domCache.cartFooter = document.getElementById('cart-footer');
    domCache.cartSubtotal = document.getElementById('cart-subtotal');
    domCache.headerCartIndicator = document.getElementById('cart-count');
    domCache.quickViewModal = document.getElementById('p-quick-view-modal');
    domCache.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
}

// Cart Functions
function openCart() {
    domCache.cartOverlay?.classList.add('is-visible');
}

function closeCart() {
    domCache.cartOverlay?.classList.remove('is-visible');
}

// Throttle function for performance
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}


async function buyNow({ productId, variantId = null, quantity = 1 } = {}, csrfToken = null) {
    try {
        const response = await fetch(`/api/quick-buy`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken ?? domCache.csrfToken,
            },
            body: JSON.stringify({
                quantity: parseInt(quantity),
                product_id: productId,
                variant_id: Number(variantId) !== 0 ? variantId : null
            }),
        });

        if (!response.ok) throw new Error('Failed to add item to cart');
        
        return await response.json();
    } catch (error) {
        console.error('Buy now error:', error);
        alert('Failed to process order. Please try again.');
    }
}

// Product Variant Functions
function switchVariantImage({ variantBtn }) {
    const productCard = variantBtn.closest("[id^='product-']");
    const productId = productCard.id.replace("product-", "");
    const variantId = variantBtn.getAttribute("data-variant-id");
    const newImageSrc = variantBtn.dataset.imageSrc;

    // Update main image
    const mainImg = productCard.querySelector(`#main-image-${productId}`);
    if (mainImg && newImageSrc) mainImg.src = newImageSrc;

    // Update selection state
    productCard.querySelectorAll(".variant-btn").forEach((btn) => btn.classList.remove("active"));
    variantBtn.classList.add("active");

    // Update add-to-cart variant reference
    productCard.querySelectorAll(".add-to-cart").forEach((btn) => {
        btn.dataset.variantId = variantId;
    });
}

// Quick View Modal Functions
function openDialog(productId) {
    if (!domCache.quickViewModal) return;
    
    const content = document.getElementById('modal-content');
    domCache.quickViewModal.classList.add('is-visible');

    // Remove any existing handler first
    if (escapeKeyHandler) {
        document.removeEventListener('keydown', escapeKeyHandler);
    }

    // Create new handler
    escapeKeyHandler = (e) => {
        if (e.key === 'Escape') closeDialog();
    };
    document.addEventListener('keydown', escapeKeyHandler);

    // Show loading state
    content.innerHTML = `
        <div class="h-96 p-4 grid md:grid-cols-2 gap-4">
            <div class="bg-primary/20 animate-pulse"></div>
            <div class="space-y-4 flex flex-col justify-between pt-4">
                <div class="space-y-4">
                    <div class="h-8 bg-primary/20 rounded w-3/4 animate-pulse"></div>
                    <div class="h-6 bg-primary/20 rounded w-1/4 animate-pulse"></div>
                </div>
                <div class="space-y-4">
                    <div class="h-4 bg-primary/20 rounded w-full animate-pulse"></div>
                    <div class="h-4 bg-primary/20 rounded w-full animate-pulse"></div>
                    <div class="h-4 bg-primary/20 rounded w-5/6 animate-pulse"></div>
                </div>
                <div class="h-8 bg-primary/20 rounded w-full animate-pulse"></div>
            </div>
        </div>
    `;

    fetch(`/api/products/${productId}/quick-view`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': domCache.csrfToken
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to fetch product');
        return response.json();
    })
    .then(data => {
        renderQuickView(data, content);
    })
    .catch(error => {
        content.innerHTML = `
            <div class="flex items-center justify-center h-96 p-6">
                <div class="text-center space-y-4 max-w-md">
                    <svg class="h-12 w-12 mx-auto text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Unable to load product</h3>
                    <p class="text-gray-500 text-sm">${error.message}</p>
                    <button class="btn py-2 px-4 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
                            onclick="closeDialog()">
                        Close
                    </button>
                </div>
            </div>
        `;
    });
}

function renderQuickView(data, content) {
    const mainImage = data.cover_media_url || data.variants?.[0]?.media_url || '/placeholder.svg';
    const variants = data.variants || [];

    content.innerHTML = `
        <div class="relative p-4 sm:p-6">
            <button class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-md hover:bg-gray-100 transition-colors"
                    onclick="closeDialog()"
                    aria-label="Close product preview">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4 ">
                    <div class="overflow-hidden w-full max-sm:h-48 max-md:h-64 rounded-lg bg-gray-100 aspect-square">
                        <img src="${mainImage}" 
                             alt="${data.name}" 
                             class="w-full h-full object-cover"
                             id="main-product-image">
                    </div>
                    
                   
                </div>

                <div class="flex flex-col">
                    <div class="">
                        <h1 class="text-lg md:text-2xl font-playfair-display font-semibold mb-1 sm:mb-2">${data.name}</h1>
                        <p class="text-base md:text-lg font-semibold text-gray-900 mb-0.5 sm:mb-1">
                            ${data.discount_price ? `
                                ₦<span class="text-red-600">${data.discount_price}</span>
                                <span class="text-gray-500 line-through text-sm ml-2">${data.price}</span>
                            ` : `₦${data.price}`}
                        </p>
                        <h3 class="text-gray-700"><span class="font-medium">Category:</span> <span style="opacity:70%">${data.category?.name || 'Uncategorized'}</span></h3>

                    </div>

                    <div class="separator my-2.5 opacity-10"></div>

                                

                    <form class="mt-auto flex-1 flex flex-col">
                        ${variants.length > 0 ? `
                            <div class="variant-selection mb-3">
                                <div class="block text-sm font-medium text-gray-700 mb-1">Variation</div>
                                <div class="flex gap-2">
                                      <button type="button" 
                                          class="variant-option rounded-full size-8 transition-colors"
                                          data-variant-id="0"
                                          data-image="${mainImage}"
                                          onclick="selectVariantOption(this)">
                                            <img src="${mainImage}" alt="${data.name}" class="w-full h-full object-cover rounded-full">
                                        </button>
                                    ${variants.map(variant => `
                                        <button type="button" 
                                                class="variant-option rounded-full size-8  transition-colors"
                                                data-variant-id="${variant.id}"
                                                data-image="${variant.media_url}"
                                                onclick="selectVariantOption(this)">
                                            <img src="${variant.media_url}" alt="${variant.name}" class="w-full h-full object-cover rounded-full">
                                        </button>
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}

                        <div class="hidden md:block separator mt-auto mb-2.5 opacity-10"></div>
                        
                        <div class="mb-3">
                            <div class="flex justify-between items-center gap-4">
                                <label for="quantity" class="font-medium text-gray-700">Quantity</label>
                                <div class="flex items-center border border-gray-300 rounded">
                                    <button type="button" 
                                            class="qty-btn px-3 py-2 hover:bg-gray-100 transition-colors"
                                            onclick="updateQuantity(-1)">
                                        -
                                    </button>
                                    <input type="number" 
                                           id="quantity" 
                                           value="1" 
                                           min="1" 
                                           max="${data.max_quantity || 10}"
                                           class="w-16 text-center border-x border-gray-300 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-gold">
                                    <button type="button" 
                                            class="qty-btn px-3 py-2 hover:bg-gray-100 transition-colors"
                                            onclick="updateQuantity(1)">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-1.5">
                            <button type="button"
                                    class="btn hover:border-primary hover:bg-primary hover:text-white text-sm py-3 px-4 tracking-widest uppercase add-to-cart relative group transition-colors"
                                    data-product-id="${data.id}">
                                Add to Cart
                            </button>
                            <button type="button"
                                    class="btn-solid text-white text-sm py-3 px-4 tracking-widest uppercase hover:bg-primary transition-colors buy-now"
                                    data-product-id="${data.id}">
                                Buy Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;

    // Initialize first variant as selected
    // if (variants.length > 0) {
    //     selectVariantOption(document.querySelector('.variant-option'));
    // }

    // Add event listeners
    attachEventListeners(data);
}

function closeDialog() {
    if (!domCache.quickViewModal) return;
    
    domCache.quickViewModal.classList.remove('is-visible');
    
    // Clean up event listener
    if (escapeKeyHandler) {
        document.removeEventListener('keydown', escapeKeyHandler);
        escapeKeyHandler = null;
    }
}

function selectVariant(button) {
    // Update main image
    const mainImage = document.getElementById('main-product-image');
    mainImage.src = button.getAttribute('data-image');

    // Update active state
    document.querySelectorAll('[data-variant-id]').forEach(btn => {
        btn.classList.remove('border-gold', 'ring-2', 'ring-gold');
    });
    button.classList.add('border-gold', 'ring-2', 'ring-gold');
}

function selectVariantOption(button) {
    document.querySelectorAll('.variant-option').forEach(btn => {
        btn.classList.remove('bg-primary', 'text-white', 'border-primary');
        btn.classList.add('border-gray-300', 'text-gray-700');
    });
    button.classList.add('bg-primary', 'text-white', 'border-primary');
    button.classList.remove('border-gray-300', 'text-gray-700');
    // Switch main image to selected variant
    selectVariant(button)
}

function updateQuantity(change) {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value) || 1;
    const newValue = currentValue + change;
    const max = parseInt(input.max) || 10;
    const min = parseInt(input.min) || 1;

    if (newValue >= min && newValue <= max) {
        input.value = newValue;
    }
    
    // Ensure value stays within bounds
    if (input.value < min) input.value = min;
    if (input.value > max) input.value = max;
}

function attachEventListeners(productData) {
    const addToCartBtn = document.querySelector('.add-to-cart');
    const buyNowBtn = document.querySelector('.buy-now');

    // --- Helper: Show/Remove error message ---
    function showVariantError() {
        if (!document.querySelector('.error-msg')) {
            const msg = document.createElement("div");
            msg.className = "error-msg text-red-500 text-xs mt-1";
            msg.innerText = "Please select an option.";
            document.querySelector('.variant-selection').appendChild(msg);
        }
    }

    function removeVariantError() {
        const msg = document.querySelector('.error-msg');
        if (msg) msg.remove();
    }

    // --- Helper: Get selected data ---
    function getSelection() {
        const quantity = document.getElementById('quantity').value;
        const variant = document.querySelector('.variant-option.bg-primary')?.getAttribute('data-variant-id');
        return { quantity, variant };
    }

    // --- Helper: Generic button handler ---
    async function handleAction(button, actionFn, loadingText) {
        const { quantity, variant } = getSelection();

        if (!variant) {
            showVariantError();
            return;
        }

        removeVariantError();

        const originalText = button.textContent;
        button.textContent = loadingText;
        button.disabled = true;

        try {
            const response = await actionFn({
                productId: productData.id,
                variantId: Number(variant) !== 0 ? variant : null,
                quantity
            });

            // Handle "buy now" redirect specifically
            if (actionFn === buyNow && response?.status === 'success' && response.data?.redirect_url) {
                window.location.href = response.data.redirect_url;
            }
        } catch (error) {
            console.error(`${loadingText} error:`, error);
        } finally {
            button.textContent = originalText;
            button.disabled = false;
        }
    }

    // --- Attach listeners ---
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', () => handleAction(addToCartBtn, addToCart, 'Adding...'));
    }

    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', () => handleAction(buyNowBtn, buyNow, 'Buying...'));
    }
}

// Product List Interactions
function initProductListInteractions({
    fetchCart,
    openVariantDialog = null,
    listSelector = ".product-list",
} = {}) {
    if (!fetchCart || typeof fetchCart !== "function") {
        console.error("initProductListInteractions: fetchCart function is required");
        return;
    }

    const lists = document.querySelectorAll(listSelector);
    if (lists.length === 0) return;

    lists.forEach((list) => {
        list.addEventListener("click", throttle(async (e) => {
            const variantBtn = e.target.closest(".variant-btn");
            const addToCartBtn = e.target.closest("button[data-product-id]");

            if (!variantBtn && !addToCartBtn) return;
            e.preventDefault();
            e.stopPropagation();

            // Variant selection
            if (variantBtn) {
                switchVariantImage({ variantBtn });
                return;
            }

            // Add to cart
            const button = addToCartBtn?.closest("button[data-product-id]");
            if (!button) return;

            if (button.dataset.loading === "true") return;

            const productId = button.getAttribute("data-product-id");
            const variantId = button.dataset.variantId || "";
            const productCard = button.closest("[id^='product-']");
            const hasVariants = productCard.querySelectorAll(".variant-btn").length > 1;
            const qtyInput = document.querySelector(`#qty-input-${productId}`) || { value: 1 };

            // If product has variants but none is selected, open dialog instead
            if (hasVariants && !variantId && typeof openVariantDialog === "function") {
                return openVariantDialog(productId);
            }

            try {
                // Loading state
                button.dataset.loading = "true";
                button.disabled = true;
                button.classList.add("opacity-70", "cursor-not-allowed");
                const span = button.querySelector("span");
                if (span) span.textContent = "ADDING...";

                await addToCart({
                    productId: productId,
                    variantId: variantId,
                    quantity: parseInt(qtyInput.value) || 1
                });

            } catch (error) {
                console.error("Failed to add to cart:", error);
            } finally {
                // Reset button
                button.dataset.loading = "false";
                button.disabled = false;
                button.classList.remove("opacity-70", "cursor-not-allowed");
                const span = button.querySelector("span");
                if (span) span.textContent = "ADD TO CART";
            }
        }, 300)); // Throttle to prevent rapid clicks
    });
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    // Initialize cache
    initCache();

    // Set up event listeners
    if (domCache.cartOverlay) {
        domCache.cartOverlay.addEventListener('click', function (event) {
            if (event.target === this) closeCart();
        });
    }

    if (domCache.quickViewModal) {
        domCache.quickViewModal.addEventListener('click', function (event) {
            if (event.target === this) closeDialog();
        });
    }

    // Load initial cart state
    // fetchCart(); // Handled in cart-system.js

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Initialize product interactions
    initProductListInteractions({ 
        fetchCart, 
        openVariantDialog: openDialog 
    });
});

// Error boundary for unhandled errors
window.addEventListener('error', (event) => {
    console.error('Unhandled error:', event.error);
});

// Expose functions to global scope for inline onclick/onchange handlers
window.openCart = CartSystem.openCart;
window.closeCart = CartSystem.closeCart;
window.fetchCart = CartSystem.fetchCart;
window.addToCart = CartSystem.addToCart;
window.buyNow = buyNow;
window.openDialog = openDialog;
window.closeDialog = closeDialog;
window.selectVariantOption = selectVariantOption;
window.updateQuantity = updateQuantity;
window.removeCartItem = CartSystem.removeCartItem;
window.updateCartItem = CartSystem.updateCartItem;
window.initProductListInteractions = initProductListInteractions;