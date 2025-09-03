<script setup>
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { LayoutDashboard, Users, LogOut } from "lucide-vue-next";
</script>

<template>
  <aside
    class="bg-primary text-white w-64 min-h-screen shadow-lg flex flex-col justify-between"
  >
    <!-- Navigation -->
    <nav class="flex flex-col p-4 gap-2">
      <Link
        :href="route('dashboard')"
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/20 transition"
      >
        <LayoutDashboard class="w-5 h-5" />
        <span>Dashboard</span>
      </Link>

      <Link
        :href="route('patient.index')"
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/20 transition"
      >
        <Users class="w-5 h-5" />
        <span>Pasien</span>
      </Link>

      <Link
        :href="route('poli.index')"
        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/20 transition"
      >
        <Users class="w-5 h-5" />
        <span>Poli</span>
      </Link>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-white/20">
      <Link
        :href="route('logout')"
        method="post"
        as="button"
        class="flex items-center gap-3 w-full text-left px-3 py-2 rounded-lg hover:bg-red-600 transition"
      >
        <LogOut class="w-5 h-5" />
        <span>Logout</span>
      </Link>
    </div>
  </aside>
</template>
