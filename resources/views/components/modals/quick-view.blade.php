<div id="p-quick-view-modal" class="quick-view-modal" role="dialog" aria-labelledby="product-modal-title" aria-modal="true">
  <div class="relative w-full max-w-3xl max-h-[90vh] mx-4 bg-white rounded-lg shadow-2xl overflow-hidden">
    <!-- Close Button -->
    <button type="button"
      class="absolute top-4 right-4 z-10 bg-white/80 backdrop-blur-sm hover:bg-white rounded-full p-2"
      onclick="closeModal()">
      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>

    <!-- Modal Content -->
    <div id="modal-content" class="h-full max-h-[90vh] relative overflow-hidden">

 
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const quickViewButtons = document.querySelectorAll('.quick-view-btn');
    quickViewButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the parent <a> navigation
        e.stopPropagation(); // Stop event bubbling to parent elements
        const productId = this.getAttribute('data-product-slug');

        fetchProductData(productId);
      });
    });
  });


  function closeModal(productId) {
    const modal = document.getElementById(`product-modal`);
    modal.classList.add('hidden');
    delete window.productData[productId];
  }

  function setImage(productId, index, image) {
    const mainImage = document.getElementById(`main-image`);
    const thumbnails = document.querySelectorAll(`#modal-content .w-12.border-2`);
    mainImage.src = image;
    window.productData[productId].selectedImageIndex = index;
    thumbnails.forEach((thumb, i) => {
      thumb.classList.toggle('border-blue-600', i === index);
      thumb.classList.toggle('border-transparent', i !== index);
    });
  }

  function changeImage(productId, direction) {
    const data = window.productData[productId];
    const newIndex = (data.selectedImageIndex + direction + data.images.length) % data.images.length;
    setImage(productId, newIndex, data.images[newIndex]);
  }

  function setColor(productId, index, name, image) {
    const data = window.productData[productId];
    data.selectedColor = index;
    data.images = data.product.colors[index].images;
    data.selectedImageIndex = 0;
    setImage(productId, 0, image);
    document.getElementById(`color-name`).textContent = name;
    const buttons = document.querySelectorAll(`#modal-content .w-10.rounded-full`);
    buttons.forEach((btn, i) => {
      btn.classList.toggle('border-blue-600', i === index);
      btn.classList.toggle('ring-2', i === index);
      btn.classList.toggle('ring-blue-600/20', i === index);
      btn.classList.toggle('border-gray-300', i !== index);
    });
  }

  function setSize(productId, size) {
    window.productData[productId].selectedSize = size;
    const buttons = document.querySelectorAll(`#modal-content .grid-cols-4 button`);
    buttons.forEach(btn => {
      btn.classList.toggle('border-blue-600', btn.textContent.trim() === size);
      btn.classList.toggle('bg-blue-600', btn.textContent.trim() === size);
      btn.classList.toggle('text-white', btn.textContent.trim() === size);
      btn.classList.toggle('border-gray-300', btn.textContent.trim() !== size);
      btn.classList.toggle('bg-transparent', btn.textContent.trim() !== size);
      btn.classList.toggle('text-black', btn.textContent.trim() !== size);
    });
    document.getElementById(`add-to-cart`).disabled = false;
  }

  function updateQuantity(productId, change) {
    const data = window.productData[productId];
    data.quantity = Math.max(1, data.quantity + change);
    document.getElementById(`quantity`).textContent = data.quantity;
  }

  function toggleWishlist(productId) {
    const data = window.productData[productId];
    data.isWishlisted = !data.isWishlisted;
    const button = document.getElementById(`wishlist`);
    button.classList.toggle('text-red-600', data.isWishlisted);
    button.classList.toggle('border-red-600', data.isWishlisted);
    button.querySelector('svg').classList.toggle('fill-current', data.isWishlisted);
    // Optionally, make an AJAX call to update wishlist on server
  }

  function addToCart(productId) {
    const data = window.productData[productId];
    const button = document.getElementById(`add-to-cart`);
    button.disabled = true;
    button.innerHTML = `
            <svg class="h-4 w-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Adding...
        `;
    fetch('/api/cart', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          productId: productId,
          color: data.product.colors[data.selectedColor].name,
          size: data.selectedSize,
          quantity: data.quantity
        })
      })
      .then(response => response.json())
      .then(() => {
        button.innerHTML = 'Add to Cart';
        button.disabled = !data.selectedSize;
      })
      .catch(error => {
        console.error('Error adding to cart:', error);
        button.innerHTML = 'Add to Cart';
        button.disabled = !data.selectedSize;
      });
  }
</script>
