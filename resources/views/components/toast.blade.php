<div id="{{ $id }}"
  class="fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white max-w-sm z-50 
            {{ $type === 'success' ? 'bg-green-500' : 'bg-destructive' }} 
            animate-toast">
  <div class="flex items-center">
    <span class="text-xs font-semibold">{{ $message }}</span>
  </div>
</div>

{{-- <script>
  // Remove the toast from the DOM after the animation completes
  document.addEventListener('DOMContentLoaded', function() {
    const toast = document.getElementById('{{ $id }}');
    if (toast) {
      toast.addEventListener('animationend', function() {
        toast.remove();
      });
    }
  });
</script> --}}
