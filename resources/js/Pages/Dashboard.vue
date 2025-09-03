<script setup>
import Layout from "../Layout/Layout.vue";
import { Head } from "@inertiajs/vue3";
import { Users, UserPlus, Activity } from "lucide-vue-next"; // icon lucide

defineProps({
  totalPatient: Number
});
</script>

<template>
  <Layout>
    <Head title="Dashboard" />

    <div class="p-6">
      <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

      <!-- Grid cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Pasien -->
        <div
          class="p-6 rounded-xl shadow-md bg-white flex items-center gap-4 hover:shadow-lg transition"
        >
          <div
            class="p-3 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center"
          >
            <Users class="w-6 h-6" />
          </div>
          <div>
            <p class="text-gray-600 text-sm">Total Pasien</p>
            <h2 class="text-2xl font-bold text-gray-800">
              {{ totalPatient }}
            </h2>
          </div>
        </div>

        <!-- Total Dokter -->
        <div
          class="p-6 rounded-xl shadow-md bg-white flex items-center gap-4 hover:shadow-lg transition"
        >
          <div
            class="p-3 bg-green-100 text-green-600 rounded-full flex items-center justify-center"
          >
            <UserPlus class="w-6 h-6" />
          </div>
          <div>
            <p class="text-gray-600 text-sm">Total Dokter</p>
            <h2 class="text-2xl font-bold text-gray-800">
              <!-- {{ totalDoctor }} -->
            </h2>
          </div>
        </div>

        <!-- Total Antrian -->
        <div
          class="p-6 rounded-xl shadow-md bg-white flex items-center gap-4 hover:shadow-lg transition"
        >
          <div
            class="p-3 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center"
          >
            <Activity class="w-6 h-6" />
          </div>
          <div>
            <p class="text-gray-600 text-sm">Total Antrian</p>
            <h2 class="text-2xl font-bold text-gray-800">
              <!-- {{ totalQueue }} -->
            </h2>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>
