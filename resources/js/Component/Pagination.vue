<script setup>
import { Link } from "@inertiajs/vue3";
defineProps(["elements"]);
</script>

<template>
  <nav v-if="elements.links.length > 3" aria-label="Pagination">
    <ul class="flex justify-center gap-1 text-sm font-medium">
      <template v-for="(link, key) in elements.links" :key="key">
        <li>
          <!-- Disabled -->
          <div
            v-if="link.url === null"
            class="px-4 h-10 flex items-center justify-center text-gray-400 bg-gray-100 rounded-md cursor-not-allowed select-none"
          >
            <span v-html="formatLabel(link.label)" />
          </div>

          <!-- Active / Normal -->
          <Link
            v-else
            :href="link.url"
            class="px-4 h-10 flex items-center justify-center border rounded-md transition-colors duration-200"
            :class="{
              'bg-blue-500 text-white border-blue-500 hover:bg-blue-600':
                link.active,
              'bg-white text-gray-600 border-gray-300 hover:bg-gray-100':
                !link.active
            }"
          >
            <span v-html="formatLabel(link.label)" />
          </Link>
        </li>
      </template>
    </ul>
  </nav>
</template>


<script>
function formatLabel(label) {
  if (label.includes("Previous")) return "&laquo; Prev";
  if (label.includes("Next")) return "Next &raquo;";
  return label;
}

</script>
