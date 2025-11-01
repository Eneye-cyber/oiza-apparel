/**
 * Product Page Script
 */
// import {CartSystem} from './cart-system'

(() => {
  
  document.addEventListener("DOMContentLoaded", () => {
    const state = {
      ...window.ProductPage,
      isOpen: false,
    };

  
    // Cache DOM elements
    const dom = {
      mainImage: document.getElementById("main-image"),
      qtyInput: document.getElementById("quantity"),
      addToCartProductPage: document.querySelector(".add-to-cart"),
      productForm: document.getElementById("product-page-form"),
      dropdown: {
        trigger: document.getElementById("selectTrigger"),
        options: document.getElementById("dropdownOptions"),
        icon: document.getElementById("dropdownIcon"),
        selectedImage: document.getElementById("selectedImage"),
        selectedName: document.getElementById("selectedName"),
      },
      overlay: document.getElementById("cart-overlay"),
      csrfToken: null
    };
    dom.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
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
  
      // if (variantId) {
      //   const variant = state.variants.find((v) => v.id == variantId);
      //   if (variant) updateAttributesUI(variant.attributes);
      // }
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
    // Input Error handling
    // ---------------------------
    function showInputError(selector) {
        if (!document.querySelector('.error-msg')) {
            const msg = document.createElement("div");
            msg.className = "error-msg text-red-500 text-xs mt-1";
            msg.innerText = "Please select an option.";
            document.querySelector(selector).appendChild(msg);
        }
    }

    function clearInputError(selector) {
        const errorMsg = document.querySelector(selector).querySelector('.error-msg');
        if (errorMsg) {
            errorMsg.remove();
        }
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
      const { selectedImage, selectedName } = dom.dropdown;
      const nativeSelect = document.getElementById("nativeSelect")
      
      const value = option.dataset.value;
      const imgSrc = option.querySelector("img")?.src || "";
      const name = option.querySelector("span")?.textContent || "";
  
      selectedImage?.classList.remove("hidden");
      if (selectedImage) selectedImage.src = imgSrc;
      if (selectedName) selectedName.textContent = name;

      if (nativeSelect) nativeSelect.value = value;
      clearInputError('#variantDropdown')
  
      changeMainImage(imgSrc, value);
      toggleDropdown(true);
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
      // if (target.closest(".add-to-cart")) {
      //   const productId = target.closest(".add-to-cart").dataset.productId;
      //   const nativeSelect = dom.dropdown.nativeSelect;
      //   console.log(nativeSelect)
      //   console.log(nativeSelect.value)
      //   const variantId = nativeSelect?.value ?? null
      //   if(!variantId)
      //     alert(`No variant selected`);

      //   alert(`Selected Variant ID: ${variantId || 'None'}`);

      //   // addToCartProductPage(productId);
      // }
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

    // form submission handling
    dom.productForm?.addEventListener("submit", (e) => {
      e.preventDefault();
      const submitter = e.submitter;

      const isAddToCart = submitter && submitter.name === 'add_to_cart';
      const isBuyNow = submitter && submitter.name === 'buy_now';

      const productId = e.target.querySelector('input[name="product_id"]').value;
      const quantity = parseInt(e.target.querySelector('input[name="quantity"]').value, 10);
      const variantId = e.target.querySelector('select[name="variant_id"]').value || null;
      console.log({productId, quantity, variantId});
      if(!variantId) {
        showInputError('#variantDropdown')
        return; 
      }
      clearInputError('#variantDropdown')

      if (isAddToCart) 
        return window.addToCart({productId, variantId, quantity}, dom.csrfToken);

      if (isBuyNow) {
        const response = window.buyNow({productId, variantId, quantity}, dom.csrfToken)
        
        if(response?.status === 'success' && response.data?.redirect_url)
          window.location.href = response.data.redirect_url;
      }
        // return window.location.href = `/checkout/buy-now/${productId}?variant_id=${variantId}&quantity=${quantity}`;


    })
  });

})();

