<script setup>
import { router, useForm } from "@inertiajs/vue3";
import Layout from "../../Layout/Layout.vue";
import dayjs from "dayjs";
import { route } from "ziggy-js";
import { computed, onMounted, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import Pagination from "../../Component/Pagination.vue";
import { Head } from "@inertiajs/vue3";

const showModal = ref(false); // ✅ kontrol modal
const showEditModal = ref(false);
const selectedPatient = ref(null);
const showDeleteModal = ref(false);
const selectedItem = ref(null);
const page = usePage();

const props = defineProps({
    polis: Array,
    filters: Object,
});

let search = ref(props.filters.search);
let startDate = ref(props.filters.startDate || '');
let endDate = ref(props.filters.endDate || '');


const handleSearch = () => {
    router.get(route("poli.index"), {
        search: search.value,
         startDate: startDate.value,
        endDate: endDate.value
    });
};

const handleReset = () => {
    search.value = ""
    startDate.value = ""
    endDate.value = ""
}

const flashMessage = computed(() => page.props.flash?.message);

// // ✅ Form untuk tambah pasien
// const form = useForm({
//     nama: "",
//     nik: "",
//     no_hp: "",
//     tanggal_lahir: "",
// });

// const storePatient = () => {
//     form.post(route("patient.store"), {
//         onSuccess: () => {
//             form.reset(); // reset form
//             showModal.value = false; // tutup modal
//         },
//     });
// };

// // ✅ Form edit pasien
// const editForm = useForm({
//     id: "",
//     nama: "",
//     nik: "",
//     no_hp: "",
//     tanggal_lahir: "",
// });

// // Buka modal edit dengan data pasien
// const openEditModal = (patient) => {
//     selectedPatient.value = patient;
//     editForm.id = patient.id;
//     editForm.nama = patient.nama;
//     editForm.nik = patient.nik;
//     editForm.no_hp = patient.no_hp;
//     editForm.tanggal_lahir = patient.tanggal_lahir;
//     showEditModal.value = true;
// };

// // Update pasien
// const updatePatient = () => {
//     editForm.put(route("patient.update", editForm.id), {
//         onSuccess: () => {
//             showEditModal.value = false;
//         },
//     });
// };

// const formDelete = useForm({});

// function openDeleteModal(item) {
//     selectedItem.value = item;
//     showDeleteModal.value = true;
// }

// function closeDeleteModal() {
//     showDeleteModal.value = false;
//     selectedItem.value = null;
// }

// function deleteItem() {
//     formDelete.delete(route("patient.destroy", selectedItem.value.id), {
//         preserveScroll: true,
//         onSuccess: () => {
//             closeDeleteModal();
//         },
//     });
// }

</script>

<template>
    <Layout>

        <Head title="Poli" />

        <div v-if="flashMessage" class="toast toast-top toast-end">
            <div class="alert alert-success">
                <span>{{ flashMessage }}</span>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
  <h1 class="text-2xl font-bold text-gray-700 flex items-center gap-2">
    <i class="fas fa-users text-blue-500"></i> Daftar Poli
  </h1>
  <button
    class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition"
    @click="showModal = true"
  >
    <i class="fas fa-plus"></i> Tambah Pasien
  </button>
</div>

<!-- Filter Section (lebih simple) -->
<div class="flex flex-wrap items-center gap-3 mb-5">
  <!-- Input Cari -->
  <div class="flex items-center gap-2 flex-1 min-w-[200px]">
    <i class="fas fa-search text-gray-400"></i>
    <input
      type="text"
      v-model="search"
      placeholder="Cari nama / NIK / No HP..."
      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
    />
  </div>

  <!-- Date Range -->
  <div class="flex items-center gap-2">
    <i class="fas fa-calendar text-gray-400"></i>
    <input
      type="date"
      v-model="startDate"
      class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
    />
    <span class="text-gray-500">s/d</span>
    <input
      type="date"
      v-model="endDate"
      class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
    />
  </div>

  <!-- Buttons -->
  <div class="flex items-center gap-2">
    <button
      @click="handleSearch"
      class="flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow transition"
    >
      <i class="fas fa-filter"></i> Filter
    </button>
    <button
      @click="handleReset"
      class="flex items-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow transition"
    >
      <i class="fas fa-undo"></i> Reset
    </button>
  </div>
</div>



        <div class="overflow-x-auto mt-5 bg-white rounded-lg shadow">
  <table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-primary text-white">
      <tr>
        <th class="px-4 py-3 text-left text-sm font-semibold">Nama</th>
        <th class="px-4 py-3 text-left text-sm font-semibold">Kode</th>
        <th class="px-4 py-3 text-center text-sm font-semibold">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
      <tr
        v-for="poli in polis.data"
        :key="poli.id"
        class="hover:bg-gray-50 transition"
      >
        <td class="px-4 py-3 text-sm text-gray-700 font-medium">{{ poli.nama_poli }}</td>
        <td class="px-4 py-3 text-sm text-gray-600">{{ poli.kode_poli }}</td>
        <td class="px-4 py-3 text-center flex justify-center gap-2">
          <button
            class="px-3 py-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded-md shadow text-sm transition"
            @click="openEditModal(poli)"
          >
            <i class="fa-solid fa-pen"></i>
          </button>
          <button
            @click="openDeleteModal(poli)"
            class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow text-sm transition"
          >
            <i class="fa-solid fa-trash"></i>
          </button>
        </td>
      </tr>
    </tbody>
  </table>

</div>
<!-- Pagination -->
<div class="flex justify-end p-4">
  <Pagination :elements="polis" />
</div>


       <!-- block all modal -->


    </Layout>
</template>
