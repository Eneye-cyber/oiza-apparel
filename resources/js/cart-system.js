// cart-system.js
export const CartSystem = (() => {
  // --- State & DOM cache ---
  let escapeKeyHandler = null;

  const domCache = {
    cartOverlay: null,
    cartContent: null,
    cartTitle: null,
    cartFooter: null,
    cartSubtotal: null,
    headerCartIndicator: null,
    quickViewModal: null,
    csrfToken: null,
  };

  // --- DOM Cache Initialization ---
  const initCache = () => {
    domCache.cartOverlay = document.getElementById("cart-overlay");
    domCache.cartContent = document.getElementById("cart-content");
    domCache.cartTitle = document.getElementById("cart-title");
    domCache.cartFooter = document.getElementById("cart-footer");
    domCache.cartSubtotal = document.getElementById("cart-subtotal");
    domCache.headerCartIndicator = document.getElementById("cart-count");
    domCache.quickViewModal = document.getElementById("p-quick-view-modal");
    domCache.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
  };

  // --- Basic Cart Actions ---
  const openCart = () => domCache.cartOverlay?.classList.add("is-visible");
  const closeCart = () => domCache.cartOverlay?.classList.remove("is-visible");

  const throttle = (func, limit) => {
    let inThrottle;
    return function (...args) {
      if (!inThrottle) {
        func.apply(this, args);
        inThrottle = true;
        setTimeout(() => (inThrottle = false), limit);
      }
    };
  };

  // --- Fetch Cart ---
  const fetchCart = async () => {
    try {
      const res = await fetch("/cart");
      if (!res.ok) throw new Error("Failed to fetch cart");
      const data = await res.json();
      renderCart(data);
    } catch (error) {
      console.error("Cart fetch error:", error);
      if (domCache.cartContent) {
        domCache.cartContent.innerHTML = `
          <div class="text-center py-8 text-red-600">
            Failed to load cart. Please try again.
          </div>`;
      }
    }
  };

  // --- Render Cart ---
  const renderCart = (data) => {
    if (
      !domCache.cartTitle ||
      !domCache.cartContent ||
      !domCache.cartFooter ||
      !domCache.cartSubtotal
    )
      return;

    domCache.cartTitle.textContent = `SHOPPING BAG (${data.count})`;

    if (data.items.length === 0) {
      domCache.cartContent.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12 text-center">
          <p class="text-lg text-gray-600 mb-4">Your cart is empty</p>
          <button class="btn bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark"
                  onclick="this.closest('#cart-overlay')?.classList.remove('is-visible')">
            Continue Shopping
          </button>
        </div>`;
      domCache.cartFooter.style.display = "none";
      return;
    }

    if (domCache.headerCartIndicator) {
      domCache.headerCartIndicator.classList.toggle("hidden", data.count === 0);
      domCache.headerCartIndicator.classList.toggle("flex", data.count > 0);
    }
 //    domCache.cartContent.innerHTML = `
//         <div class="flex-1 overflow-y-auto py-2 px-6 max-h-[calc(100svh-200px)]">
//             ${data.items.map(item => `
//                 <figure class="flex items-stretch gap-3 py-4 border-whitesmoke not-last-of-type:border-b">
//                     <img class="w-[30%] h-24 object-contain border border-primary" 
//                          src="${item.product.cover_media_url || '/placeholder.png'}"
//                          alt="${item.product.name}" loading="lazy" />

//                     <figcaption class="flex-1 flex flex-col">
//                         <div class="flex flex-between gap-2">
//                             <h4 class="text-sm font-semibold opacity-90 tracking-wide line-clamp-1">${item.product.name}</h4>
//                             <button onclick="removeCartItem(${item.id})" class="ml-auto inline-block hover:underline">
//                                 <span class="text-sm text-primary cursor-pointer">Remove</span>
//                             </button>
//                         </div>

//                         <p class="text-sm tracking-wide">${item.product.category?.name || 'Uncategorized'}</p>
//                         <div class="flex">
//                             <p class="text-sm opacity-70 tracking-wide pr-2">Size: ${item.product.size || '6 yards'}</p>
//                             <span class="text-sm opacity-70">/</span>
//                             <p class="text-sm opacity-70 tracking-wide pl-2">Color: ${item.product.main_color || 'Blue'}</p>
//                         </div>

//                         <div class="flex items-end mt-auto">
//                             <span class="text-sm font-semibold tracking-wider">₦${(item.price * item.quantity).toLocaleString()}</span>
//                             <input type="number" min="1" value="${item.quantity}" 
//                                 class="inline-block w-16 h-9 ml-auto bg-light-gray rounded-sm p-2 pr-3 border border-gray-300"
//                                 onchange="updateCartItem(${item.id}, this.value)" />
//                         </div>
//                     </figcaption>
//                 </figure>
//             `).join('')}
//         </div>
//     `;
    console.log(data)
    

    domCache.cartContent.innerHTML = `
      <div class="flex-1 overflow-y-auto py-2 px-6 max-h-[calc(100svh-200px)]">
        ${data.items
          .map(
            (item) => `
          <figure class="flex items-stretch gap-3 py-4 border-whitesmoke not-last-of-type:border-b">
            <img class="w-[30%] h-24 object-contain border border-primary"
                 src="${item.variant?.media_url || item.product.cover_media_url || "/placeholder.png"}"
                 alt="${item.product.name}" loading="lazy" />
            <figcaption class="flex-1 flex flex-col">
              <div class="flex justify-between gap-2">
                <h4 class="text-sm font-semibold">${item.product.name} ${item.variant?.name || ''}</h4>
                <button data-id="${item.id}" class="remove-item text-primary text-sm cursor-pointer">Remove</button>
              </div>
              <p class="text-sm text-gray-600">${item.product.category?.name || "Uncategorized"}</p>
              <div class="flex items-end mt-auto">
                <span class="text-sm font-semibold">₦${(
                  (item?.variant?.price || item.product?.discount_price || item.product.price) * item.quantity
                ).toLocaleString()}</span>
                <input type="number" min="1" value="${item.quantity}"
                       data-id="${item.id}" class="update-item w-16 ml-auto bg-light-gray rounded-sm border border-gray-300 p-1 text-center"/>
              </div>
            </figcaption>
          </figure>`
          )
          .join("")}
      </div>`;

    domCache.cartSubtotal.textContent = `₦${data.subtotal.toLocaleString()}`;
    domCache.cartFooter.style.display = "block";

    // Bind remove/update buttons dynamically
    domCache.cartContent.querySelectorAll(".remove-item").forEach((btn) => {
      btn.addEventListener("click", () => removeCartItem(btn.dataset.id));
    });

    domCache.cartContent.querySelectorAll(".update-item").forEach((input) => {
      input.addEventListener("change", () =>
        updateCartItem(input.dataset.id, input.value)
      );
    });
  };

  // --- Update Cart ---
  const updateCartItem = async (itemId, quantity) => {
    const validQuantity = Math.max(1, parseInt(quantity) || 1);
    try {
      await fetch(`/cart/update/${itemId}`, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": domCache.csrfToken,
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ quantity: validQuantity }),
      });
      fetchCart();
    } catch (error) {
      console.error("Update cart item error:", error);
    }
  };

  const removeCartItem = async (itemId) => {
    try {
      await fetch(`/cart/remove/${itemId}`, {
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": domCache.csrfToken },
      });
      fetchCart();
    } catch (error) {
      console.error("Remove cart item error:", error);
    }
  };

  // --- Add to Cart ---
  const addToCart = async ({ productId, variantId = null, quantity = 1 } = {}, csrfToken = null) => {
    try {
      const res = await fetch(`/cart/add/${productId}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken ?? domCache.csrfToken,
        },
        body: JSON.stringify({
          quantity: parseInt(quantity),
          variant_id: variantId,
        }),
      });

      if (!res.ok) throw new Error("Failed to add item to cart");

      await res.json();
      await fetchCart();
      console.log('open cart')
      openCart();
      closeDialog();
    } catch (error) {
      console.error("Add to cart error:", error);
      alert("Failed to add item to cart. Please try again.");
    }
  };

  // --- Quick View Modal ---
  const openDialog = async (productId) => {
    const modal = domCache.quickViewModal;
    if (!modal) return;

    modal.classList.add("is-visible");
    const content = modal.querySelector("#modal-content");
    content.innerHTML = `<div class="p-6 text-center">Loading...</div>`;

    if (escapeKeyHandler) document.removeEventListener("keydown", escapeKeyHandler);
    escapeKeyHandler = (e) => e.key === "Escape" && closeDialog();
    document.addEventListener("keydown", escapeKeyHandler);

    try {
      const res = await fetch(`/api/products/${productId}/quick-view`, {
        headers: { "Accept": "application/json", "X-CSRF-TOKEN": domCache.csrfToken },
      });
      if (!res.ok) throw new Error("Failed to fetch product");
      const data = await res.json();
      renderQuickView(data, content);
    } catch (err) {
      content.innerHTML = `<div class="p-6 text-center text-red-600">Failed to load product.</div>`;
    }
  };

  const closeDialog = () => {
    const modal = domCache.quickViewModal;
    if (modal) modal.classList.remove("is-visible");
    if (escapeKeyHandler) {
      document.removeEventListener("keydown", escapeKeyHandler);
      escapeKeyHandler = null;
    }
  };

  const renderQuickView = (data, content) => {
    const image = data.cover_media_url || "/placeholder.svg";
    content.innerHTML = `
      <div class="p-4">
        <h2 class="font-semibold text-xl mb-2">${data.name}</h2>
        <img src="${image}" alt="${data.name}" class="w-full h-64 object-cover rounded mb-4"/>
        <p class="text-gray-700 mb-4">${data.description || ""}</p>
        <button class="btn bg-primary text-white w-full py-2 add-to-cart" data-product-id="${data.id}">
          Add to Cart
        </button>
      </div>`;

    content.querySelector(".add-to-cart")?.addEventListener("click", () =>
      addToCart({ productId: data.id })
    );
  };

  // --- Product Page & List Interactions ---
  const initProductListInteractions = () => {
    const productPage = document.querySelector(".product-page");
    if (!productPage) return;

    productPage.addEventListener("click", (e) => {
      const btn = e.target.closest(".add-to-cart");
      const variantItem = e.target.closest(".option-item");
      const qtyBtn = e.target.closest(".qty-btn");
      const thumbnail = e.target.closest(".thumbnail");

      // Add to cart
      if (btn) {
        const productId = btn.dataset.productId;
        const qty = productPage.querySelector("#quantity")?.value || 1;
        addToCart({ productId, quantity: qty });
      }

      // Thumbnail swap
      if (thumbnail) {
        const mainImage = document.getElementById("main-image");
        if (mainImage && thumbnail.dataset.src) {
          mainImage.src = thumbnail.dataset.src;
          document.querySelectorAll(".thumbnail").forEach(t => t.classList.remove("active"));
          thumbnail.classList.add("active");
        }
      }

      // Variant selection
      if (variantItem) {
        variantItem.parentElement
          .querySelectorAll(".option-item")
          .forEach((el) => el.classList.remove("selected"));
        variantItem.classList.add("selected");
      }

      // Quantity controls
      if (qtyBtn) {
        const input = document.getElementById("quantity");
        let value = parseInt(input.value, 10);
        if (qtyBtn.classList.contains("qty-plus")) value++;
        if (qtyBtn.classList.contains("qty-minus")) value = Math.max(1, value - 1);
        input.value = value;
      }
    });
  };

  // --- Initialization ---
  const init = () => {
    initCache();
    if (domCache.cartOverlay)
      domCache.cartOverlay.addEventListener("click", (e) => e.target === domCache.cartOverlay && closeCart());
    if (domCache.quickViewModal)
      domCache.quickViewModal.addEventListener("click", (e) => e.target === domCache.quickViewModal && closeDialog());
    fetchCart();
    initProductListInteractions();
  };

  // --- Expose Public API ---
  return {
    init,
    fetchCart,
    openCart,
    closeCart,
    addToCart,
    openDialog,
    closeDialog,
    initProductListInteractions,
  };
})();
