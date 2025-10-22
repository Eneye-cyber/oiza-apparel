/**
 * Product Page Script
 */

document.addEventListener("DOMContentLoaded", () => {
  const state = {
    ...window.ProductPage,
    isOpen: false,
  };

  // Cache DOM elements
  const dom = {
    mainImage: document.getElementById("main-image"),
    qtyInput: document.getElementById("quantity"),
    addToCart: document.querySelector(".add-to-cart"),
    dropdown: {
      trigger: document.getElementById("selectTrigger"),
      options: document.getElementById("dropdownOptions"),
      icon: document.getElementById("dropdownIcon"),
      selectedImage: document.getElementById("selectedImage"),
      selectedName: document.getElementById("selectedName"),
      nativeSelect: document.getElementById("nativeSelect"),
    },
    overlay: document.getElementById("cart-overlay"),
  };

  // ---------------------------
  // Utility Functions
  // ---------------------------

  const safeQueryAll = (selector) => Array.from(document.querySelectorAll(selector));

  const clamp = (num, min, max) => Math.min(Math.max(num, min), max);

  // ---------------------------
  // Image + Variant Handling
  // ---------------------------

  function changeMainImage(imageUrl, variantId) {
    if (!dom.mainImage) return;

    dom.mainImage.src = imageUrl;
    dom.mainImage.alt = state.productName;

    safeQueryAll(".thumbnail").forEach((thumb) => {
      thumb.classList.toggle("selected", thumb.dataset.image === imageUrl);
    });

    if (variantId) {
      const variant = state.variants.find((v) => v.id == variantId);
      if (variant) updateAttributesUI(variant.attributes);
    }
  }

  function updateAttributesUI(attributes) {
    attributes.forEach((attr) => {
      const slug = attr.attribute_name.replace(/[^a-zA-Z0-9]/g, "-").toLowerCase();
      const input = document.getElementById(slug);
      if (!input) return;

      input.value = attr.attribute_value;

      safeQueryAll(`.${slug}-option`).forEach((el) => {
        const isColor = el.classList.contains("color-swatch");
        el.classList.remove("border-gold", "bg-black", "text-white");
        el.classList.add(isColor ? "border-transparent" : "bg-white");

        if (el.dataset.value === attr.attribute_value) {
          el.classList.add(isColor ? "border-gold" : "bg-black", "text-white");
          el.classList.remove(isColor ? "border-transparent" : "bg-white");
        }
      });
    });
  }

  function getSelectedVariant() {
    const selectedValues = {};
    safeQueryAll("input[data-attribute]").forEach((input) => {
      selectedValues[input.dataset.attribute] = input.value;
    });

    return state.variants.find((variant) =>
      variant.attributes.every(
        (attr) => selectedValues[attr.attribute_name] === attr.attribute_value
      )
    );
  }

  // ---------------------------
  // Quantity Controls
  // ---------------------------

  function handleQuantityChange(delta) {
    if (!dom.qtyInput) return;
    const value = parseInt(dom.qtyInput.value, 10) || 1;
    dom.qtyInput.value = clamp(value + delta, 1, state.maxQuantity);
  }

  // ---------------------------
  // Dropdown Handling
  // ---------------------------

  function toggleDropdown(forceClose = false) {
    const { options, icon } = dom.dropdown;
    state.isOpen = forceClose ? false : !state.isOpen;
    options?.classList.toggle("hidden", !state.isOpen);
    if (icon) icon.style.transform = state.isOpen ? "rotate(180deg)" : "rotate(0deg)";
  }

  function handleDropdownSelection(option) {
    const { selectedImage, selectedName, nativeSelect } = dom.dropdown;
    const value = option.dataset.value;
    const imgSrc = option.querySelector("img")?.src || "";
    const name = option.querySelector("span")?.textContent || "";

    selectedImage?.classList.remove("hidden");
    if (selectedImage) selectedImage.src = imgSrc;
    if (selectedName) selectedName.textContent = name;
    if (nativeSelect) nativeSelect.value = value;

    changeMainImage(imgSrc, value);
    toggleDropdown(true);
  }

  // ---------------------------
  // Cart Handling
  // ---------------------------

  async function addToCart(productId) {
    try {
      const response = await fetch(`/cart/add/${productId}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
          quantity: parseInt(dom.qtyInput.value, 10),
        }),
      });

      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      await fetchCart?.();
      dom.overlay?.classList.add("is-visible");
    } catch (err) {
      console.error("Add to Cart failed:", err);
      alert("Error adding item to cart. Please try again.");
    }
  }

  // ---------------------------
  // Event Delegation
  // ---------------------------

  document.body.addEventListener("click", (e) => {
    const target = e.target;

    // Quantity buttons
    if (target.closest(".qty-btn")) {
      const delta = parseInt(target.closest(".qty-btn").dataset.change, 10);
      handleQuantityChange(delta);
    }

    // Thumbnails
    if (target.closest(".thumbnail")) {
      const thumb = target.closest(".thumbnail");
      changeMainImage(thumb.dataset.image, thumb.dataset.variantId);
    }

    // Dropdown trigger
    if (target.closest("#selectTrigger")) toggleDropdown();

    // Dropdown option
    if (target.closest(".option-item")) handleDropdownSelection(target.closest(".option-item"));

    // Add to Cart
    if (target.closest(".add-to-cart")) {
      const productId = target.closest(".add-to-cart").dataset.productId;
      addToCart(productId);
    }
  });

  // Close dropdown on outside click or ESC
  document.addEventListener("click", (e) => {
    const { trigger, options } = dom.dropdown;
    if (state.isOpen && !trigger?.contains(e.target) && !options?.contains(e.target)) {
      toggleDropdown(true);
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && state.isOpen) toggleDropdown(true);
  });

  // Keyboard navigation for dropdown
  dom.dropdown.options?.addEventListener("keydown", (e) => {
    const options = safeQueryAll(".option-item");
    const index = options.indexOf(document.activeElement);
    let nextIndex = index;

    switch (e.key) {
      case "ArrowDown":
        e.preventDefault();
        nextIndex = (index + 1) % options.length;
        break;
      case "ArrowUp":
        e.preventDefault();
        nextIndex = (index - 1 + options.length) % options.length;
        break;
      case "Enter":
        if (document.activeElement.classList.contains("option-item")) {
          handleDropdownSelection(document.activeElement);
        }
        break;
      default:
        return;
    }

    options[nextIndex]?.focus();
  });

  // Make options focusable
  safeQueryAll(".option-item").forEach((option) => option.setAttribute("tabindex", "0"));
});
