@props(['breadcrumbs' => [], 'productName' => null])

<nav aria-label="breadcrumb">
  <ol itemscope itemtype="https://schema.org/BreadcrumbList"
      class="flex flex-wrap gap-2.5 md:gap-4 mb-4">

    <!-- Shop Root -->
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
      <a itemprop="item"
         href="/shop"
         class="inline-block pr-2.5 md:pr-4 border-r border-gold text-sm opacity-75 hover:opacity-100">
        <span itemprop="name">Shop</span>
      </a>
      <meta itemprop="position" content="1" />
    </li>

    <!-- Dynamic Breadcrumbs -->
    @foreach ($breadcrumbs as $index => $breadcrumb)
      <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item"
           href="{{ url('/shop/' . $breadcrumb['slug']) }}"
           class="inline-block pr-2.5 md:pr-4 border-r border-gold text-sm opacity-75 hover:opacity-100">
          <span itemprop="name">{{ $breadcrumb['name'] }}</span>
        </a>
        <meta itemprop="position" content="{{ $index + 2 }}" />
      </li>
    @endforeach

    <!-- Current Page -->
    @if ($productName)
      <span class="inline-block max-w-48 text-nowrap line-clamp-1 overflow-ellipsis pr-2.5 text-sm font-semibold">
        {{ $productName }}
      </span>
    @endif
  </ol>
</nav>
<!-- Breadcrumbs Component -->