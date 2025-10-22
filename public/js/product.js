document.addEventListener("DOMContentLoaded", () => {
  const { variants, maxQuantity, productName } = window.ProductPage;

  const mainImage = document.getElementById("main-image");
  const qtyInput = document.getElementById("quantity");

  /** Change main image */
  function changeMainImage(imageUrl, variantId) {
    mainImage.src = imageUrl;
    mainImage.alt = productName;

    document.querySelectorAll(".thumbnail").forEach((thumb) => {
      if (thumb.dataset.image !== imageUrl) {
        return thumb.classList.remove("selected");
      }
      thumb.classList.add("selected");
    });


    if (variantId) {
      const variant = variants.find((v) => v.id == variantId);
      if (variant) updateAttributesUI(variant.attributes);
    }
  }

  /** Update attributes UI */
  function updateAttributesUI(attributes) {
    attributes.forEach((attr) => {
      const slug = attr.attribute_name.replace(/[^a-zA-Z0-9]/g, "-").toLowerCase();
      const input = document.getElementById(slug);
      if (!input) return;

      input.value = attr.attribute_value;

      document.querySelectorAll(`.${slug}-option`).forEach((el) => {
        const isColor = el.classList.contains("color-swatch");
        el.classList.remove("border-gold", "bg-black", "text-white");
        el.classList.add(isColor ? "border-transparent" : "bg-white");

        if (el.dataset.value === attr.attribute_value) {
          el.classList.add(isColor ? "border-gold" : "bg-black", "text-white");
          if (isColor) el.classList.remove("border-transparent");
          else el.classList.remove("bg-white");
        }
      });
    });
  }

  /** Select variant option */
  function selectVariantOption(type, value, button, isColor) {
    const input = document.getElementById(type);
    input.value = value;

    document.querySelectorAll(`.${type}-option`).forEach((el) => {
      if (isColor) {
        el.classList.remove("border-gold");
        el.classList.add("border-transparent");
      } else {
        el.classList.remove("bg-black", "text-white");
        el.classList.add("bg-white");
      }
    });

    if (isColor) {
      button.classList.add("border-gold");
      button.classList.remove("border-transparent");
    } else {
      button.classList.add("bg-black", "text-white");
      button.classList.remove("bg-white");
    }

    const selected = getSelectedVariant();
    if (selected?.image) {
      mainImage.src = `/storage/${selected.image}`;
      mainImage.alt = productName;
    }
  }


  /** Quantity controls */
  document.querySelectorAll(".qty-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      let quantity = parseInt(qtyInput.value) + parseInt(btn.dataset.change);
      if (quantity < 1) quantity = 1;
      if (quantity > maxQuantity) quantity = maxQuantity;
      qtyInput.value = quantity;
    });
  });

  /** Thumbnails */
  document.querySelectorAll(".thumbnail").forEach((thumb) => {
    thumb.addEventListener("click", () =>
      changeMainImage(thumb.dataset.image, thumb.dataset.variantId)
    );
  });


  /** Add to Cart */
  document.querySelector(".add-to-cart").addEventListener("click", async (e) => {
    const productId = e.target.dataset.productId;
    console.log("element", e.target)
    // const selectedVariant = getSelectedVariant();

    // if (!selectedVariant) {
    //   alert("Selected variant is not available.");
    //   return;
    // }

    await fetch(`/cart/add/${productId}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({
        // variant_id: selectedVariant.id,
        quantity: parseInt(qtyInput.value),
      }),
    });

    fetchCart();
    document.getElementById("cart-overlay").classList.add("is-visible");
  });

  const selectTrigger = document.getElementById('selectTrigger');
  const dropdownOptions = document.getElementById('dropdownOptions');
  const dropdownIcon = document.getElementById('dropdownIcon');
  const selectedImage = document.getElementById('selectedImage');
  const selectedName = document.getElementById('selectedName');
  const nativeSelect = document.getElementById('nativeSelect');

  let isOpen = false;

  // Toggle dropdown
  selectTrigger?.addEventListener('click', () => {
    isOpen = !isOpen;
    dropdownOptions.classList.toggle('hidden', !isOpen);
    dropdownIcon.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';

    if (isOpen) {
      document.addEventListener('click', closeDropdownOnOutsideClick);
    } else {
      document.removeEventListener('click', closeDropdownOnOutsideClick);
    }
  });

  // Handle option selection
  document.querySelectorAll('.option-item').forEach(option => {
    option.addEventListener('click', (e) => {
      const value = e.currentTarget.dataset.value;
      const imgSrc = e.currentTarget.querySelector('img').src;
      const name = e.currentTarget.querySelector('span').textContent;

      // Update display
      selectedImage.classList.remove('hidden');
      selectedImage.src = imgSrc;
      selectedName.textContent = name;
      nativeSelect.value = value;

      // Update main image
      changeMainImage(imgSrc, value);

      // Close dropdown
      isOpen = false;
      dropdownOptions.classList.add('hidden');
      dropdownIcon.style.transform = 'rotate(0deg)';

      // Remove outside click listener
      document.removeEventListener('click', closeDropdownOnOutsideClick);
    });
  });

  // Close dropdown when clicking outside
  function closeDropdownOnOutsideClick(e) {
    if (!selectTrigger.contains(e.target) && !dropdownOptions.contains(e.target)) {
      isOpen = false;
      dropdownOptions.classList.add('hidden');
      dropdownIcon.style.transform = 'rotate(0deg)';
      document.removeEventListener('click', closeDropdownOnOutsideClick);
    }
  }

  // Close dropdown on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isOpen) {
      isOpen = false;
      dropdownOptions.classList.add('hidden');
      dropdownIcon.style.transform = 'rotate(0deg)';
      document.removeEventListener('click', closeDropdownOnOutsideClick);
    }
  });
  // Keyboard navigation
  dropdownOptions.addEventListener('keydown', (e) => {
    const options = Array.from(document.querySelectorAll('.option-item'));
    const currentFocus = document.activeElement;
    let currentIndex = options.indexOf(currentFocus);

    switch (e.key) {
      case 'ArrowDown':
        e.preventDefault();
        const nextIndex = (currentIndex + 1) % options.length;
        options[nextIndex].focus();
        break;
      case 'ArrowUp':
        e.preventDefault();
        const prevIndex = (currentIndex - 1 + options.length) % options.length;
        options[prevIndex].focus();
        break;
      case 'Enter':
        if (currentFocus.classList.contains('option-item')) {
          currentFocus.click();
        }
        break;
    }
  });

  // Make options focusable
  document.querySelectorAll('.option-item').forEach(option => {
    option.setAttribute('tabindex', '0');
  });
});
