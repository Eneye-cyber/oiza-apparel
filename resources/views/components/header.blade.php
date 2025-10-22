<header class="w-full md:pt-4 py-2.5 font-dm-sans bg-white relative">
  <div class="container max-md:px-3 mx-auto">
    <div class="hidden md:flex justify-between items-center pb-2 border-b text-xs border-b-[#2222221a]" data-aos="fade-down">
      <div class="flex items-center gap-2">
        <x-heroicon-o-phone class="size-5 text-primary" aria-hidden="true" />
        <a href="tel:(+123)4567890" class="font-dm-sans text-black opacity-75 hover:opacity-100">( +123 ) 456
            7890</a>
      </div>
      <div class="flex divide-x divide-black/75">
        <a href="{{ route('order') }}" class="text-black opacity-75 hover:opacity-100 pr-1.5">Order tracking</a>
        <a href="{{ route('contact') }}" class="text-black opacity-75 hover:opacity-100 pl-1.5">Customer Support</a>
      </div>
    </div>

    <section class="pt-2">
      <div class="flex justify-between items-center">
        <h2 data-aos="fade-down" data-aos-delay="100">
          <a href="/" class="font-playfair-display text-3xl font-medium text-primary drop-shadow-xs drop-shadow-primary/20"
              aria-label="Oiza Apparels Home">
              OIZA
          </a>
        </h2>

        

        <!-- Main Navigation -->
        <nav class="hidden md:flex items-center uppercase font-medium space-x-2" aria-label="Main navigation"
            id="main-nav">
            <a href="{{ Route::has('shop') ? route('shop') : '#' }}"
                class="p-5 inline-block hover:text-[#555] transition-colors duration-200"
                 data-aos="fade-down" data-aos-delay="100">New Arrivals</a>
            @foreach ($categories as $category)
                <div class="group" aria-haspopup="true" aria-expanded="false">
                    <div class="p-5 cursor-pointer flex items-center gap-1.5 hover:text-[#555] transition-colors duration-200"
                        role="button" aria-label="{{ $category['name'] }} menu"  data-aos="fade-down" data-aos-delay="100">
                        <a  href="{{ route('shop.category', ['slug' => $category['slug']]) }}">{{ $category['name'] }}</a>
                        <x-heroicon-c-chevron-down
                            class="h-4 w-auto transition-transform duration-200 group-hover:rotate-180"
                            aria-hidden="true" />
                    </div>
                    @if (!empty($category['subcategories']))
                        <div
                            class="absolute min-w-full w-screen left-0 bg-white shadow-lg py-6 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="container grid grid-cols-12 h-fit gap-6">
                              <div class="col-span-3 w-full max-h-72 grid lg:grid-cols-2 grid-rows-2 gap-4 relative">
                                <div class="object-contain overflow-clip">
                                  <img src="{{ $category['image'] ?? asset('img/african_print.webp') }}" loading="lazy" alt="{{ $category['name'] }}"
                                      class="h-full w-full object-cover rounded-lg ">
                                </div>
                                
                                <div class="hidden lg:block object-contain overflow-clip">
                                  <img src="{{ $category['image'] ?? asset('img/kid_ai.jpg') }}" loading="lazy" alt="{{ $category['name'] }}"
                                      class="h-full w-full object-cover rounded-lg ">
                                </div>


                                <div class="hidden lg:block object-contain overflow-clip">
                                  <img src="{{ $category['image'] ?? asset('img/shirt.jpg') }}" loading="lazy" alt="{{ $category['name'] }}"
                                      class="h-full w-full object-cover rounded-lg ">
                                </div>

                                <div class="object-contain overflow-clip">
                                  <img src="{{ $category['image'] ?? asset('img/fabrics.jpg') }}" loading="lazy" alt="{{ $category['name'] }}"
                                      class="h-full w-full object-cover rounded-lg ">
                                </div>
                              </div>

                              <ul class="col-span-9 grid grid-cols-5 h-fit border-l pl-6">
                                @include('components.submenu', [
                                    'subcategories' => $category['subcategories'],
                                    'parentSlug' => $category['slug'],
                                ])
  
                              </ul>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>

        <!-- Mobile Navigation -->
        <nav class="md:hidden hidden flex-col space-y-2 mt-4 bg-white p-4 shadow-lg absolute w-full left-0 top-12 z-50"
            id="mobile-nav" aria-label="Mobile navigation">
            <a href="{{ Route::has('shop') ? route('shop') : '#' }}"
                class="p-3 block hover:text-[#555] transition-colors duration-200">Shop</a>
            @foreach ($categories as $category)
                <div class="relative" aria-haspopup="true" aria-expanded="false">
                    <button
                        class="p-3 w-full text-left flex items-center justify-between hover:text-[#555] transition-colors duration-200"
                        aria-label="{{ $category['name'] }} menu" data-submenu-toggle>
                        <span>{{ $category['name'] }}</span>
                        <x-heroicon-c-chevron-down class="h-4 w-auto transition-transform duration-200"
                            aria-hidden="true" />
                    </button>
                    @if (!empty($category['subcategories']))
                        <ul class="hidden pl-4 space-y-2" data-submenu>
                            @include('components.submenu', [
                                'subcategories' => $category['subcategories'],
                                'parentSlug' => $category['slug'],
                            ])
                        </ul>
                    @endif
                </div>
            @endforeach
        </nav>

        <div class="w-auto flex flex-center gap-3">
            <button class="inline-block cursor-pointer relative" onclick="openCart()" aria-label="View shopping cart"  data-aos="fade-down" data-aos-delay="100">
                <x-heroicon-o-shopping-bag class="size-5 text-primary" aria-hidden="true" />
                <span
                    class="absolute -top-1.5 -right-1.5 bg-primary rounded-full h-3 w-3  flex-center hidden animate-pulse"
                    id="cart-count"></span>
            </button>
            <!-- Mobile Menu Toggle -->
            <button class="md:hidden text-black focus:outline-none" id="mobile-menu-toggle"
                aria-label="Toggle navigation menu" aria-expanded="false">
                <x-heroicon-o-bars-3-bottom-right class="size-6" aria-hidden="true" />
            </button>
        </div>
        
      </div>
    </section>
  </div>
</header>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
        const mobileNav = document.getElementById('mobile-nav');
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', !isExpanded);
        mobileNav.classList.toggle('hidden');
    });

    // Mobile submenu toggle
    document.querySelectorAll('[data-submenu-toggle]').forEach(button => {
        button.addEventListener('click', function() {
            const submenu = this.nextElementSibling;
            const chevron = this.querySelector('svg');
            submenu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        });
    });
</script>
