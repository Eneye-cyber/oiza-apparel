<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="@yield('description', 'Discover high-quality fabrics and stylish apparels at Oiza Apparels.')">
  <meta name="keywords" content="@yield('keywords', 'Ankara, fabrics, lace, Mens fabrics, children wears')">
  <meta name="robots" content="index,follow">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Open Graph Tags -->
  <meta property="og:title" content="@yield('og_title', 'Oiza Apparels')">
  <meta property="og:description" content="@yield('og_description', 'Shop premium fabrics and ready-to-wear collections at Oiza Apparels.')">
  <meta property="og:image" content="@yield('og_image', asset('/favicon/android-chrome-192x192.png'))">
  <meta property="og:url" content="{{ request()->url() }}">
  <meta property="og:type" content="website">

  <!-- Twitter Card Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('og_title', 'Oiza Apparels')">
  <meta name="twitter:description" content="@yield('og_description', 'Shop premium fabrics and ready-to-wear collections at Oiza Apparels.')">
  <meta name="twitter:image" content="@yield('og_image', asset('/favicon/android-chrome-192x192.png'))">

  <!-- Canonical URL -->
  <link rel="canonical" href="{{ request()->url() }}">

  <!-- Preload Key Resources -->
  <link rel="preload" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" as="style">
  <link rel="preload" href="{{ Vite::asset('resources/css/app.css') }}" as="style">

  <title>@yield('title', 'Oiza Apparels')</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/favicon/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('/favicon/site.webmanifest') }}">

  <!-- Styles / Scripts -->
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @else
    <link rel="stylesheet" href="{{ asset('/fallback.css') }}">
  @endif
  <link rel="stylesheet" href="{{ asset('styles/aos.css') }}" />
  @yield('head')
</head>

<body class="bg-white text-black overflow-x-clip">
  <x-header />
  <x-cart-drawer />

  <main class="font-dm-sans min-h-80">
    @yield('content')
  </main>

  <footer>
    <div class="container grid gap-8 text-black py-8">
      <section class="max-sm:pb-6 py-14 bg-cream">
        <div class="grid sm:grid-cols-2 gap-6">
          <div class="px-4 sm:px-6 md:px-12">
            <h2>
              <a href="/" class="font-playfair-display text-5xl font-medium" aria-label="Oiza Apparels Home">
                OIZA
              </a>
            </h2>
            <div class="separator my-4 mr-4 !bg-primary opacity-100"></div>
            <p class="text-black hover:text-[#555]">Choose us for high-quality fabrics, secure checkout,
              flexible payment options, instant order confirmation, and prompt delivery to your door</p>
          </div>
          <div class="px-4 sm:px-0 grid lg:grid-cols-2 gap-y-6">
            <div>
              <h5 class="uppercase pb-2 font-semibold text-balance text-primary">Here to help</h5>
              <p class="mb-4 text-sm text-black hover:text-[#555]">
                Have a question? You may find an answer in our<span>&nbsp;</span>
                <a href="{{ route('contact') }}#faq" class="underline hover:text-[#555]">FAQs</a>.
                But you can also contact us:
              </p>
              <p class="text-13 mb-3">Customer Services</p>
              <div class="space-y-2">
                <div class="flex items-center gap-3 text-13">
                  <x-heroicon-o-clock class="size-3.5" aria-hidden="true" />
                  <p class="text-black hover:text-[#555]">Mon-Fri: 9:00 am - 6:00 pm</p>
                </div>
                <div class="flex items-center gap-3 text-13">
                  <x-heroicon-o-phone class="size-3.5" aria-hidden="true" />
                  <a href="tel:(+123)4567890" class="text-black hover:text-[#555] hover:underline">Call Us: ( +123 )
                    456-7890</a>
                </div>
                <div class="flex items-center gap-3 text-13">
                  <x-heroicon-o-envelope class="size-3.5" aria-hidden="true" />
                  <a href="mailto:alaoeneye@gmail.com" class="text-black hover:text-[#555] hover:underline">Send us an
                    email</a>
                </div>
              </div>
            </div>
            <div class="lg:px-6">
              <h5 class="uppercase pb-2 font-semibold text-balance text-primary">Follow Us</h5>
              <div class="space-y-2">
                <div class="flex items-center gap-3 text-13">
                  <x-ionicon-logo-facebook class="size-3.5" aria-hidden="true" />
                  <a href="https://www.facebook.com/oiza.awomokun" target="_blank" rel="noopener noreferrer"
                    class="text-black hover:text-[#555] hover:underline">Facebook</a>
                </div>
                <div class="flex items-center gap-3 text-13">
                  <x-ionicon-logo-instagram class="size-3.5" aria-hidden="true" />
                  <a href="https://www.instagram.com/oiza_apparel/" target="_blank" rel="noopener noreferrer"
                    class="text-black hover:text-[#555] hover:underline">Instagram</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section>
        <div class="flex items-center justify-center sm:justify-between flex-wrap gap-3">
          <dl class="w-auto flex items-center justify-between gap-3 sm:gap-8 text-black">
            <a href="{{ route('privacy') }}" class="text-black hover:text-[#555]">Privacy Policy</a>
            <a href="{{ route('terms') }}" class="text-black hover:text-[#555]">Terms &amp; Condition</a>
            <a href="{{ route('sitemap') }}" class="text-black hover:text-[#555]">Sitemap</a>
          </dl>
          <dl class="w-auto flex items-center justify-between gap-8">
            <img src="{{ asset('/img/Visamastercard.webp') }}" alt="Visa and Mastercard payment options"
              width="64" height="26" loading="lazy">
          </dl>
        </div>
      </section>
    </div>
  </footer>

  <!-- WhatsApp Chat Widget -->
  <div id="whatsapp-widget" class="fixed bottom-2.5 sm:bottom-5 right-2.5 sm:right-5 z-50">
    <!-- Toggle Button -->
    <button id="whatsapp-toggle"
      class="relative whatsapp-button animate-pulse flex items-center justify-center bg-[#25D366] text-white p-3 rounded-full shadow-lg hover:bg-[#128C7E] transition-colors"
      aria-label="Chat with us on WhatsApp">
      <x-ionicon-logo-whatsapp name="logo-whatsapp" class="size-6 sm:size-8" />
      <span
        class="absolute -top-0.5 -right-0.5 bg-red-600 text-white rounded-full py-0.5 px-2 text-xs flex-center font-semibold"
        id="chat-count">1</span>
    </button>

    <!-- Chat Modal -->
    <div id="whatsapp-chat"
      class="hidden fixed bottom-20 sm:bottom-24 right-2.5 sm:right-6 w-80 sm:w-96 h-96 bg-white rounded-t-2xl shadow-2xl flex-col overflow-hidden transition-all duration-300 ease-in-out transform translate-y-full opacity-0 z-40">
      <!-- Header -->
      <div class="bg-[#25D366] text-white p-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
            <x-ionicon-logo-whatsapp name="logo-whatsapp" class="text-[#25D366] text-xl" />
          </div>
          <div>
            <h3 class="font-semibold">Oiza apparels</h3>
            <p class="text-sm opacity-90">Typically replies within 10 minutes</p>
          </div>
        </div>
        <button id="whatsapp-close" class="pointer-cursor"><x-ionicon-close class="text-white size-6" /></button>
      </div>

      <!-- Chat Messages Area -->
      <div id="chat-messages" style="background-image: url('./img/whatsapp_bg.png');"
        class="flex-1 p-4 bg-gray-100 overflow-y-auto space-y-4">
        <!-- Messages will be added here via JS -->
      </div>

      <!-- Input Area -->
      <div class="bg-gray-100 p-4 border-t border-gray-200 flex items-center space-x-2">
        <input id="message-input" type="text" placeholder="Type a message..."
          class="flex-1 px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-[#25D366] focus:border-transparent" />
        <button id="send-message"
          class="bg-[#25D366] text-white p-3 rounded-full hover:bg-[#128C7E] transition-colors flex items-center justify-center"
          disabled>
          <x-ionicon-send class=" size-4 text-white text-xl" />
        </button>
      </div>
    </div>
  </div>

  <script>
    (() => {
      "use strict";

      /*** ==========
       *  CONFIG
       *  ========== */
      const WHATSAPP_NUMBER = "2348034602165";
      const WELCOME_DELAY_MS = 1000;
      const WHATSAPP_BASE_URL = "https://wa.me/";

      /*** ==========
       *  DOM CACHE
       *  ========== */
      const toggleBtn = document.getElementById("whatsapp-toggle");
      const chatModal = document.getElementById("whatsapp-chat");
      const closeBtn = document.getElementById("whatsapp-close");
      const messageInput = document.getElementById("message-input");
      const sendBtn = document.getElementById("send-message");
      const chatMessages = document.getElementById("chat-messages");
      const chatCount = document.getElementById("chat-count")

      if (!toggleBtn || !chatModal) {
        console.error("WhatsApp chat: required DOM elements not found.");
        return;
      }

      /*** ==========
       *  HELPERS
       *  ========== */

      const scrollToBottom = () => {
        chatMessages.scrollTop = chatMessages.scrollHeight;
      };

      const createMessageBubble = (text, sender = "bot") => {
        const container = document.createElement("div");
        container.className =
          sender === "user" ? "flex justify-end" : "flex justify-start";

        const bubble = document.createElement("div");
        bubble.className =
          sender === "user" ?
          "bg-gray-200 text-gray-800 max-w-[70%] p-3 rounded-2xl rounded-tl-sm" :
          "bg-[#25D366] text-white max-w-[70%] p-3 rounded-2xl rounded-tr-sm";

        const p = document.createElement("p");
        p.textContent = text;

        const time = document.createElement("p");
        time.className = `text-xs opacity-75 mt-1 ${
      sender === "user" ? "text-right" : ""
    }`;
        time.textContent =
          sender === "user" ?
          "Now" :
          new Date().toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit"
          });

        bubble.appendChild(p);
        bubble.appendChild(time);
        container.appendChild(bubble);

        chatMessages.appendChild(container);
        scrollToBottom();
      };

      const showTypingIndicator = () => {
        const typingDiv = document.createElement("div");
        typingDiv.id = "typing-indicator";
        typingDiv.className = "flex justify-start";
        typingDiv.setAttribute("aria-label", "Oiza is typing...");

        typingDiv.innerHTML = `
      <div class="bg-gray-200 rounded-2xl rounded-tr-sm p-3 max-w-[70%]">
        <div class="flex space-x-1">
          <div class="typing-dot bg-gray-500 rounded-full w-2 h-2 animate-bounce"></div>
          <div class="typing-dot bg-gray-500 rounded-full w-2 h-2 animate-bounce" style="animation-delay: 0.1s;"></div>
          <div class="typing-dot bg-gray-500 rounded-full w-2 h-2 animate-bounce" style="animation-delay: 0.2s;"></div>
        </div>
      </div>
    `;
        chatMessages.appendChild(typingDiv);
        scrollToBottom();
      };

      const removeTypingIndicator = () => {
        const typing = document.getElementById("typing-indicator");
        if (typing) typing.remove();
      };

      /*** ==========
       *  CHAT CONTROL
       *  ========== */

      const openChat = () => {
        chatModal.classList.remove("hidden");
        chatModal.classList.add("flex");
        chatModal.setAttribute("aria-hidden", "false");

        setTimeout(() => {
          chatModal.classList.remove("translate-y-full", "opacity-0");
          chatModal.classList.add("translate-y-0", "opacity-100");
          chatCount.remove()
        }, 100);

        showTypingIndicator();

        setTimeout(() => {
          removeTypingIndicator();
          createMessageBubble("Hi, welcome to Oiza's store! How can we help you today 😊", "bot");
        }, WELCOME_DELAY_MS);
      };

      const closeChat = () => {
        chatModal.classList.add("translate-y-full", "opacity-0");
        chatModal.setAttribute("aria-hidden", "true");

        setTimeout(() => {
          chatModal.classList.add("hidden");
          chatModal.classList.remove("flex");
          chatMessages.textContent = "";
          removeTypingIndicator();
        }, 300);
      };

      const toggleChat = () => {
        const isHidden = chatModal.classList.contains("hidden");
        isHidden ? openChat() : closeChat();
      };

      /*** ==========
       *  MESSAGE HANDLING
       *  ========== */

      const sendMessage = () => {
        const message = messageInput.value.trim();
        if (!message) return;

        createMessageBubble(message, "user");
        messageInput.value = "";
        sendBtn.disabled = true;

        const whatsappUrl = `${WHATSAPP_BASE_URL}${WHATSAPP_NUMBER}?text=${encodeURIComponent(
      message
    )}`;

        const newWindow = window.open(whatsappUrl, "_blank");
        if (!newWindow) {
          alert("Please allow pop-ups to open WhatsApp chat.");
        }

        setTimeout(closeChat, 1000);
      };

      /*** ==========
       *  EVENT LISTENERS
       *  ========== */

      toggleBtn.addEventListener("click", toggleChat);
      closeBtn?.addEventListener("click", closeChat);

      messageInput.addEventListener("input", () => {
        sendBtn.disabled = !messageInput.value.trim();
      });

      messageInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter" && !e.shiftKey) {
          e.preventDefault();
          sendMessage();
        }
      });

      sendBtn.addEventListener("click", sendMessage);

      document.addEventListener("click", (e) => {
        if (
          !chatModal.contains(e.target) &&
          !toggleBtn.contains(e.target) &&
          !chatModal.classList.contains("hidden")
        ) {
          closeChat();
        }
      });

      // Keyboard accessibility: press ESC to close chat
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && !chatModal.classList.contains("hidden")) {
          closeChat();
        }
      });
    })();
  </script>


  <style>
    /* Custom styles for typing dots animation */
    .typing-dot {
      animation: bounce 1.4s infinite ease-in-out both;
    }

    .typing-dot:nth-child(1) {
      animation-delay: -0.32s;
    }

    .typing-dot:nth-child(2) {
      animation-delay: -0.16s;
    }

    @keyframes bounce {

      0%,
      80%,
      100% {
        transform: scale(0);
      }

      40% {
        transform: scale(1);
      }
    }
  </style>

  <script src="{{ asset('js/main.js') }}"></script>

  <script src="{{ asset('js/aos.js') }}"></script>
  <script>
    AOS.init();
  </script>
  @yield('scripts')
</body>

</html>
