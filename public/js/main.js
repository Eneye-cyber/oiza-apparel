// Global variable for escape key handler
let escapeKeyHandler = null;

// Cart Functions
function openCart() {
  const cartOverlay = document.getElementById('cart-overlay');
  cartOverlay.classList.add('is-visible');
}

function closeCart() {
  document.getElementById('cart-overlay').classList.remove('is-visible');
}

document.getElementById('cart-overlay').addEventListener('click', function (event) {
  if (event.target === this) closeCart();
});

async function fetchCart() {
  try {
    let res = await fetch("/cart");
    if (!res.ok) throw new Error('Failed to fetch cart');
    let data = await res.json();
    renderCart(data);
  } catch (error) {
    console.error('Cart fetch error:', error);
    const cartContent = document.getElementById("cart-content");
    if (cartContent) {
      cartContent.innerHTML = `
        <div class="text-center py-8 text-red-600">
          Failed to load cart. Please try again.
        </div>
      `;
    }
  }
}

function renderCart(data) {
  const cartTitle = document.getElementById("cart-title");
  const cartContent = document.getElementById("cart-content");
  const cartFooter = document.getElementById("cart-footer");
  const cartSubtotal = document.getElementById("cart-subtotal");
  const headerCartIndicator = document.getElementById("cart-count");

  cartTitle.textContent = `SHOPPING BAG (${data.count})`;

  if (data.items.length === 0) {
    cartContent.innerHTML = `
      <div class="flex flex-col items-center justify-center py-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <p class="text-lg text-gray-600 mb-4">Your cart is empty</p>
        <button onclick="closeCart()" class="btn bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark transition-colors">
          Continue Shopping
        </button>
      </div>
    `;
    cartFooter.style.display = "none";
    return;
  }

  // Update cart count indicator
  if (headerCartIndicator) {
    // headerCartIndicator.textContent = data.count;
    if (data.count > 0) {
      headerCartIndicator.classList.remove("hidden");
      headerCartIndicator.classList.add("flex");
    } else {
      headerCartIndicator.classList.add("hidden");
      headerCartIndicator.classList.remove("flex");
    }
  }

  cartContent.innerHTML = `
    <div class="flex-1 overflow-y-auto py-2 px-6 max-h-[calc(100svh-200px)]">
      ${data.items.map(item => `
        <figure class="flex items-stretch gap-3 py-4 border-whitesmoke not-last-of-type:border-b">
          <img class="w-[30%] h-24 object-contain border border-primary" 
               src="${item.product.cover_media_url ?? '/placeholder.png'}"
               alt="${item.product.name}" loading="lazy" />

          <figcaption class="flex-1 flex flex-col">
            <div class="flex flex-between gap-2">
              <h4 class="text-sm font-semibold opacity-90 tracking-wide line-clamp-1">${item.product.name}</h4>
              <button onclick="removeCartItem(${item.id})" class="ml-auto inline-block hover:underline">
                <span class="text-sm text-primary cursor-pointer">Remove</span>
              </button>
            </div>

            <p class="text-sm tracking-wide">${item.product.category?.name ?? 'Uncategorized'}</p>
            <div class="flex ">
              <p class="text-sm opacity-70 tracking-wide pr-2">Size: ${item.product.size ?? '6 yards'}</p>
              <span class="text-sm opacity-70">/</span>
              <p class="text-sm opacity-70 tracking-wide pl-2">Color: ${item.product.main_color ?? 'Blue'}</p>
            </div>

            <div class="flex items-end mt-auto">
              <span class="text-sm font-semibold tracking-wider">₦${(item.price * item.quantity).toLocaleString()}</span>
              <input type="number" min="1" value="${item.quantity}" 
                  class="inline-block w-16 h-9 ml-auto bg-light-gray rounded-sm p-2 pr-3 border border-gray-300"
                  onchange="updateCartItem(${item.id}, this.value)" />
            </div>
          </figcaption>
        </figure>
      `).join('')}
    </div>
  `;

  cartSubtotal.textContent = `₦${data.subtotal.toLocaleString()}`;
  cartFooter.style.display = "block";
}

async function updateCartItem(itemId, quantity) {
  const validQuantity = Math.max(1, parseInt(quantity) || 1);
  await fetch(`/cart/update/${itemId}`, {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ quantity: validQuantity })
  });
  fetchCart();
}

async function removeCartItem(itemId) {
  await fetch(`/cart/remove/${itemId}`, {
    method: "DELETE",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
    }
  });
  fetchCart();
}

// Add to Cart Function
async function addToCart({ productId, variantId = null, quantity = 1 } = {}) {
  try {
    const response = await fetch(`/cart/add/${productId}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({
        quantity: parseInt(quantity),
        variant_id: variantId
      }),
    });

    if (!response.ok) throw new Error('Failed to add item to cart');
    
    const result = await response.json();
    
    // Refresh cart and open it
    await fetchCart();
    openCart();
    
    return result;
  } catch (error) {
    console.error('Add to cart error:', error);
    alert('Failed to add item to cart. Please try again.');
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
  const modal = document.getElementById('p-quick-view-modal');
  const content = document.getElementById('modal-content');

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

  modal.classList.add('is-visible');

  // Remove any existing handler first
  if (escapeKeyHandler) {
    document.removeEventListener('keydown', escapeKeyHandler);
  }

  // Create new handler
  escapeKeyHandler = (e) => {
    if (e.key === 'Escape') closeDialog();
  };
  document.addEventListener('keydown', escapeKeyHandler);

  fetch(`/api/products/${productId}/quick-view`, {
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
  })
    .then(response => {
      if (!response.ok) throw new Error('Failed to fetch product');
      return response.json();
    })
    .then(data => {
      const mainImage = data.cover_media_url || data.variants?.[0]?.media_url || '/placeholder.svg';
      const variants = data.variants || [];

      content.innerHTML = `
      <div class="relative p-6">
        <!-- Close Button -->
        <button class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center bg-white rounded-full shadow-md hover:bg-gray-100 transition-colors"
                onclick="closeDialog()"
                aria-label="Close product preview">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <div class="grid md:grid-cols-2 gap-6">
          <!-- Product Images -->
          <div class="space-y-4">
            <div class="overflow-hidden rounded-lg bg-gray-100 aspect-square">
              <img src="${mainImage}" 
                   alt="${data.name}" 
                   class="w-full h-full object-cover"
                   id="main-product-image">
            </div>
            
            <!-- Variant Selection -->
            ${variants.length > 0 ? `
              <div class="flex gap-2 overflow-x-auto py-2">
                ${variants.map(variant => `
                  <button class="flex-shrink-0 w-16 h-16 rounded border-2 border-transparent hover:border-gold transition-colors"
                          data-variant-id="${variant.id}"
                          data-image="${variant.media_url}"
                          onclick="selectVariant(this)">
                    <img src="${variant.media_url}" 
                         alt="${variant.name}" 
                         class="w-full h-full object-cover rounded">
                  </button>
                `).join('')}
              </div>
            ` : ''}
          </div>

          <!-- Product Details -->
          <div class="flex flex-col">
            <div class="mb-4">
              <h3 class="text-gold text-lg tracking-wider">${data.category?.name || 'Uncategorized'}</h3>
              <h1 class="text-2xl font-playfair-display font-semibold mb-2">${data.name}</h1>
              <p class="text-lg font-semibold text-gray-900">
                ₦${data.discount_price ? `
                  <span class="text-red-600">${data.discount_price}</span>
                  <span class="text-gray-500 line-through text-sm ml-2">${data.price}</span>
                ` : data.price}
              </p>
            </div>

            <!-- Description -->
            <div class="prose prose-sm text-gray-600 mb-6">
              ${data.description}
            </div>

            <!-- Quantity & Add to Cart -->
            <form class="mt-auto space-y-4">
              ${variants.length > 0 ? `
                <div class="variant-selection">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                  <div class="flex gap-2">
                    ${variants.map(variant => `
                      <button type="button" 
                              class="variant-option px-4 py-2 border rounded-md text-sm transition-colors"
                              data-variant-id="${variant.id}"
                              onclick="selectVariantOption(this)">
                        ${variant.name}
                      </button>
                    `).join('')}
                  </div>
                </div>
              ` : ''}

              <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                  <label for="quantity" class="font-medium text-gray-700">Quantity</label>
                  <div class="flex items-center border border-gray-300 rounded-md">
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
                           class="w-16 text-center border-x border-gray-300 py-2 focus:outline-none focus:ring-1 focus:ring-gold">
                    <button type="button" 
                            class="qty-btn px-3 py-2 hover:bg-gray-100 transition-colors"
                            onclick="updateQuantity(1)">
                      +
                    </button>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-3 pt-4">
                <button type="button"
                        class="btn border border-gray-300 hover:border-primary text-sm py-3 px-4 tracking-widest uppercase add-to-cart relative group transition-colors"
                        data-product-id="${data.id}">
                  Add to Cart
                </button>
                <button type="button"
                        class="btn-solid bg-primary text-white text-sm py-3 px-4 tracking-widest uppercase hover:bg-primary-dark transition-colors buy-now"
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
      if (variants.length > 0) {
        selectVariantOption(document.querySelector('.variant-option'));
      }

      // Add event listeners
      attachEventListeners(data);
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

function closeDialog() {
  const modal = document.getElementById('p-quick-view-modal');
  modal.classList.remove('is-visible');
  
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
  // Add to cart functionality
  document.querySelector('.add-to-cart').addEventListener('click', async function () {
    const quantity = document.getElementById('quantity').value;
    const variant = document.querySelector('.variant-option.bg-primary')?.getAttribute('data-variant-id');
    
    // Show loading state
    const originalText = this.textContent;
    this.textContent = 'Adding...';
    this.disabled = true;
    
    try {
      await addToCart({
        productId: productData.id,
        variantId: variant,
        quantity: quantity
      });
    } finally {
      // Reset button state
      this.textContent = originalText;
      this.disabled = false;
    }
  });

  // Buy now functionality
  document.querySelector('.buy-now').addEventListener('click', async function () {
    const quantity = document.getElementById('quantity').value;
    const variant = document.querySelector('.variant-option.bg-primary')?.getAttribute('data-variant-id');
    
    // Add to cart and redirect to checkout
    try {
      await addToCart({
        productId: productData.id,
        variantId: variant,
        quantity: quantity
      });
      // Redirect to checkout page
      window.location.href = '/checkout';
    } catch (error) {
      console.error('Buy now error:', error);
    }
  });
}

document.getElementById('p-quick-view-modal').addEventListener('click', function (event) {
  if (event.target === this) closeDialog();
});

// Product List Interactions
/**
* Initialize product list interactions
* Supports:
*  - Variant image switching
*  - Add-to-cart requests
*  - Optional variant selection dialog (if product has variants)
*
* @param {Object} options - configuration object
* @param {Function} options.fetchCart - function to refresh cart
* @param {Function} [options.openVariantDialog] - optional callback to open a modal when variants exist
* @param {string} [options.listSelector=".product-list"] - product list selector
*/
function initProductListInteractions({
  fetchCart,
  openVariantDialog = null,
  listSelector = ".product-list",
} = {}) {
  if (!fetchCart || typeof fetchCart !== "function") {
    console.error("initProductListInteractions: fetchCart function is required");
    return;
  }

  document.querySelectorAll(listSelector).forEach((list) => {
    list.addEventListener("click", async (e) => {
      const variantBtn = e.target.closest(".variant-btn");
      const addToCartBtn = e.target.closest("button[data-product-id]");

      if (!variantBtn && !addToCartBtn) return;
      e.preventDefault();
      e.stopPropagation();

      // --- VARIANT SELECTION ---
      if (variantBtn) {
        switchVariantImage({ variantBtn });
        return;
      }

      // --- ADD TO CART ---
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

        // Use the centralized addToCart function
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
    });
  });
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  // Load initial cart state
  fetchCart();

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