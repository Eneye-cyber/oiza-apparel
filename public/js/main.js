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
  let res = await fetch("/cart");
  let data = await res.json();
  renderCart(data);
}

function renderCart(data) {
  const cartTitle = document.getElementById("cart-title");
  const cartContent = document.getElementById("cart-content");
  const cartFooter = document.getElementById("cart-footer");
  const cartSubtotal = document.getElementById("cart-subtotal");
  const headerCartIndicator = document.getElementById("cart-count");


  cartTitle.textContent = `SHOPPING BAG (${data.count})`;

  if (data.items.length === 0) {
    cartContent.innerHTML = `<p class="text-center text-base opacity-70 tracking-wide my-auto">Your cart is currently empty.</p>`;
    cartFooter.style.display = "none";
    return;
  }
  headerCartIndicator.classList.remove("hidden");
  headerCartIndicator.classList.add("flex");

  cartContent.innerHTML = `
          <div class="flex-1 overflow-y-auto py-2 px-6 max-h-[calc(100svh-200px)]">
            ${data.items.map(item => `
              <figure class="flex items-stretch gap-3 py-4 border-whitesmoke not-last-of-type:border-b">
                <img class="w-[30%] h-24 object-contain border border-primary" 
                     src="${item.product.cover_media ?? '/placeholder.png'}"
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
                    <p class="text-sm opacity-70 tracking-wide pl-2">Color: ${item.product.color ?? 'Blue'}</p>
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
  await fetch(`/cart/update/${itemId}`, {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ quantity })
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


// Load cart when drawer opens
document.addEventListener("DOMContentLoaded", fetchCart);

document.addEventListener("DOMContentLoaded", () => {
  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute('href')).scrollIntoView({
        behavior: 'smooth'
      });
    });
  });



  // Select all product-list containers
  document.querySelectorAll(".product-list").forEach((list) => {
    list.addEventListener("click", async (e) => {
      const button = e.target.closest("button[data-product-id]");
      if (!button) return;
      e.preventDefault(); // Prevent the parent <a> navigation
      e.stopPropagation();
      // Prevent duplicate clicks
      if (button.dataset.loading === "true") return;

      const productId = button.getAttribute("data-product-id");
      const qtyInput = document.querySelector(`#qty-input-${productId}`) || { value: 1 };

      try {
        // Loading state
        button.dataset.loading = "true";
        button.disabled = true;
        button.classList.add("opacity-70", "cursor-not-allowed");
        button.querySelector("span").textContent = "ADDING...";

        // Send request
        await fetch(`/cart/add/${productId}`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({
            quantity: parseInt(qtyInput.value) || 1,
          }),
        });

        // Refresh cart + show overlay
        await fetchCart();
        document.getElementById("cart-overlay").classList.add("is-visible");
      } catch (error) {
        console.error("Failed to add to cart:", error);
      } finally {
        // Reset button
        button.dataset.loading = "false";
        button.disabled = false;
        button.classList.remove("opacity-70", "cursor-not-allowed");
        button.querySelector("span").textContent = "ADD TO CART";
      }
    });
  });
});