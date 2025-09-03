<div id="product-modal" class="fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm hidden" role="dialog" aria-labelledby="product-modal-title" aria-modal="true">
    <div class="relative w-full max-w-4xl max-h-[90vh] mx-4 bg-white rounded-lg shadow-2xl overflow-hidden">
        <!-- Close Button -->
        <button type="button"
            class="absolute top-4 right-4 z-10 bg-white/80 backdrop-blur-sm hover:bg-white rounded-full p-2"
            onclick="closeModal()">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Modal Content -->
        <div id="modal-content" class="h-full max-h-[90vh]">
            <!-- Loading State -->
            <div id="loading-state" class="flex items-center justify-center h-96">
                <div class="text-center space-y-4">
                    <svg class="h-8 w-8 animate-spin mx-auto text-blue-600" fill="none" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <p class="text-gray-500">Loading product details...</p>
                </div>
            </div>
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

    function fetchProductData(productId) {
        const modal = document.getElementById(`product-modal`);
        const content = document.getElementById(`modal-content`);
        const loadingState = document.getElementById(`loading-state`);

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        fetch(`/products/${productId}/quick-view`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch product');
                return response.json();
            })
            .then(data => {
              console.log(data)
                // Success State
                // Pick main product image, fallback to first variant image
                const mainImage = data.cover_media || data.variants?.[0]?.image || '/placeholder.svg';

                // Collect unique colors and sizes from variant attributes
                const colors = [];
                const sizes = [];
                data.variants.forEach(variant => {
                  variant.attributes?.forEach(attr => {
                    if (attr.attribute_name.toLowerCase() === 'color' && !colors.find(c => c.name === attr.attribute_value)) {
                      colors.push({ name: attr.attribute_value, images: [variant.image] });
                    }
                    if (attr.attribute_name.toLowerCase() === 'size' && !sizes.includes(attr.attribute_value)) {
                      sizes.push(attr.attribute_value);
                    }
                  });
                });

                content.innerHTML = `
                  <div class="grid grid-cols-1 lg:grid-cols-2 h-full max-h-[90vh]">
                    <!-- Image Section -->
                    <div class="relative bg-gray-100">
                      <div class="relative aspect-square lg:aspect-auto lg:h-full">
                        <img
                          id="main-image"
                          src="${mainImage}"
                          alt="${data.name}"
                          class="w-full h-full object-cover"
                        />
                      </div>
                    </div>

                    <!-- Product Details Section -->
                    <div class="p-6 lg:p-8 overflow-y-auto">
                      <div class="space-y-6">
                        <div>
                          <h1 class="text-2xl lg:text-3xl font-bold mb-2">${data.name}</h1>
                        </div>

                        <div class="flex items-center gap-3">
                          <span class="text-3xl font-bold text-blue-600">
                            $${data.discount_price || data.price}
                          </span>
                          ${data.discount_price ? `
                            <span class="text-lg text-gray-500 line-through">$${data.price}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                              ${Math.round(((data.price - data.discount_price) / data.price) * 100)}% OFF
                            </span>
                          ` : ''}
                        </div>

                        <p class="text-gray-500 leading-relaxed">${data.description || ''}</p>

                        ${colors.length > 0 ? `
                          <div>
                            <h3 class="font-semibold mb-3">Color</h3>
                            <div class="flex gap-2">
                              ${colors.map((color, index) => `
                                <button
                                  class="w-10 h-10 rounded-full border-2 transition-all ${index === 0 ? 'border-blue-600 ring-2 ring-blue-600/20' : 'border-gray-300 hover:border-blue-600/50'}"
                                  style="background-color: ${color.name.toLowerCase()};"
                                  onclick="setColor(${productId}, ${index}, '${color.name}', '${color.images[0]}')"
                                  title="${color.name}"
                                ></button>
                              `).join('')}
                            </div>
                          </div>
                        ` : ''}

                        ${sizes.length > 0 ? `
                          <div>
                            <h3 class="font-semibold mb-3">Size</h3>
                            <div class="grid grid-cols-4 gap-2">
                              ${sizes.map(size => `
                                <button
                                  class="py-2 px-3 border rounded-md text-sm font-medium transition-colors border-gray-300 hover:border-blue-600"
                                  onclick="setSize(${productId}, '${size}')"
                                >
                                  ${size}
                                </button>
                              `).join('')}
                            </div>
                          </div>
                        ` : ''}

                        <div>
                          <h3 class="font-semibold mb-3">Quantity</h3>
                          <div class="flex items-center gap-3">
                            <button class="h-10 w-10 border border-gray-300 hover:bg-gray-100 rounded-md" onclick="updateQuantity(${productId}, -1)">-</button>
                            <span id="quantity" class="w-12 text-center font-medium">1</span>
                            <button class="h-10 w-10 border border-gray-300 hover:bg-gray-100 rounded-md" onclick="updateQuantity(${productId}, 1)">+</button>
                          </div>
                        </div>

                        <div class="flex gap-3 pt-4">
                          <button id="add-to-cart" class="flex-1 h-12 px-6 text-lg bg-blue-600 text-white hover:bg-blue-700 rounded-md" onclick="addToCart(${productId})">
                            Add to Cart
                          </button>
                          <button id="wishlist" class="h-12 w-12 border border-gray-300 hover:bg-gray-100 rounded-md" onclick="toggleWishlist(${productId})">
                            <svg class="h-5 w-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                `;

                window.productData = window.productData || {};
                window.productData[productId] = {
                  product: data,
                  selectedImageIndex: 0,
                  selectedColor: 0,
                  selectedSize: null,
                  quantity: 1,
                  isWishlisted: false,
                  images: colors[0]?.images || [mainImage]
                };
            })
            .catch(error => {
                // Error State
                content.innerHTML = `
                <div class="flex items-center justify-center h-96">
                    <div class="text-center space-y-4 max-w-md px-6">
                        <svg class="h-12 w-12 mx-auto text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold">Unable to load product</h3>
                        <p class="text-gray-500 text-sm">${error.message}</p>
                        <button
                            class="h-10 px-4 border border-gray-300 hover:bg-gray-100 rounded-md"
                            onclick="closeModal('${productId}')"
                        >
                            Close
                        </button>
                    </div>
                </div>
            `;
            });
    }

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
