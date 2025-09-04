document.addEventListener("DOMContentLoaded", () => {
  const { variants, maxQuantity, productName } = window.ProductPage;

  const mainImage = document.getElementById("main-image");
  const qtyInput = document.getElementById("quantity");

  /** Change main image */
  function changeMainImage(imageUrl, variantId, button) {
    mainImage.src = imageUrl;
    mainImage.alt = productName;

    document.querySelectorAll(".thumbnail").forEach((thumb) => {
      thumb.classList.remove("border-2", "border-gold");
      thumb.classList.add("cursor-pointer");
    });

    button.classList.add("border-2", "border-gold");
    button.classList.remove("cursor-pointer");

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

  /** Get selected variant based on attributes */
  function getSelectedVariant() {
    const selectedAttributes = {};
    document.querySelectorAll('input[type="hidden"]').forEach((input) => {
      if (input.id) selectedAttributes[input.id] = input.value;
    });

    return variants.find((variant) =>
      Object.entries(selectedAttributes).every(([slug, value]) => {
        const attrName = variants
          .flatMap((v) => v.attributes)
          .find((attr) => attr.attribute_name.replace(/[^a-zA-Z0-9]/g, "-").toLowerCase() === slug)?.attribute_name;

        return (
          !attrName ||
          variant.attributes.some(
            (attr) => attr.attribute_name === attrName && attr.attribute_value === value
          )
        );
      })
    );
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
      changeMainImage(thumb.dataset.image, thumb.dataset.variantId, thumb)
    );
  });

  /** Attribute options */
  document.querySelectorAll("[class*='-option']").forEach((btn) => {
    btn.addEventListener("click", () => {
      const type = btn.className.match(/([a-z0-9-]+)-option/)[1];
      const value = btn.dataset.value;
      const isColor = btn.classList.contains("color-swatch");
      selectVariantOption(type, value, btn, isColor);
    });
  });

  /** Add to Cart */
  document.querySelector(".add-to-cart").addEventListener("click", async (e) => {
    const productId = e.target.dataset.productId;
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
});
