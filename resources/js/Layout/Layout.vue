<script setup>
import Navbar from "../Component/Navbar.vue";
import Sidebar from "../Component/Sidebar.vue";
</script>

<template>
  <div class="h-screen flex flex-col bg-base-200 overflow-hidden">
    <Navbar />
    <div class="flex flex-1 overflow-hidden">
      <Sidebar />
      <main class="flex-1 p-6 bg-base-100 rounded-tl-2xl shadow-inner overflow-y-auto">
        <slot />
      </main>
    </div>
  </div>
</template>
