<script setup>
import Layout from "../Layout/Layout.vue";
import { Head } from "@inertiajs/vue3";
import { Users, Stethoscope, ListOrdered } from "lucide-vue-next"; // icon lucide

defineProps({
  totalPatient: Number,
  totalPoli: Number,
  totalQueue: Number,
  totalPrescription: Number
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

        <!-- Total Poli -->
<div
  class="p-6 rounded-xl shadow-md bg-white flex items-center gap-4 hover:shadow-lg transition"
>
  <!-- Icon -->
  <div
    class="p-3 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center"
  >
    <Stethoscope class="w-6 h-6" />
  </div>

  <!-- Content -->
  <div>
    <p class="text-gray-600 text-sm">Total Poli</p>
    <h2 class="text-2xl font-bold text-gray-800">
      {{ totalPoli }}
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
            <ListOrdered class="w-6 h-6" />
          </div>
          <div>
            <p class="text-gray-600 text-sm">Total Antrian</p>
            <h2 class="text-2xl font-bold text-gray-800">
              {{ totalQueue }}
            </h2>
          </div>
        </div>



        <div
          class="p-6 rounded-xl shadow-md bg-white flex items-center gap-4 hover:shadow-lg transition"
        >
          <div
            class="p-3 bg-red-100 text-red-600 rounded-full flex items-center justify-center"
          >
            <ListOrdered class="w-6 h-6" />
          </div>
          <div>
            <p class="text-gray-600 text-sm">Total Resep</p>
            <h2 class="text-2xl font-bold text-gray-800">
              {{ totalPrescription }}
            </h2>
          </div>
        </div>


      </div>
    </div>
  </Layout>
</template>
