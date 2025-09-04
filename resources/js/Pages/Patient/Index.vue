<script setup>
import { router, useForm } from "@inertiajs/vue3";
import Layout from "../../Layout/Layout.vue";
import dayjs from "dayjs";
import { route } from "ziggy-js";
import { computed, onMounted, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import Pagination from "../../Component/Pagination.vue";
import { Head } from "@inertiajs/vue3";
import { Users } from "lucide-vue-next";

const showModal = ref(false); // ✅ kontrol modal
const showEditModal = ref(false);
const selectedPatient = ref(null);
const showDeleteModal = ref(false);
const selectedItem = ref(null);
const page = usePage();

const props = defineProps({
    patients: Array,
    filters: Object,
});

let search = ref(props.filters.search);
let startDate = ref(props.filters.startDate || '');
let endDate = ref(props.filters.endDate || '');


const handleSearch = () => {
    router.get(route("patient.index"), {
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

// ✅ Form untuk tambah pasien
const form = useForm({
    nama: "",
    nik: "",
    no_hp: "",
    tanggal_lahir: "",
});

const storePatient = () => {
    form.post(route("patient.store"), {
        onSuccess: () => {
            form.reset(); // reset form
            showModal.value = false; // tutup modal
        },
    });
};

// ✅ Form edit pasien
const editForm = useForm({
    id: "",
    nama: "",
    nik: "",
    no_hp: "",
    tanggal_lahir: "",
});

// Buka modal edit dengan data pasien
const openEditModal = (patient) => {
    selectedPatient.value = patient;
    editForm.id = patient.id;
    editForm.nama = patient.nama;
    editForm.nik = patient.nik;
    editForm.no_hp = patient.no_hp;
    editForm.tanggal_lahir = patient.tanggal_lahir;
    showEditModal.value = true;
};

// Update pasien
const updatePatient = () => {
    editForm.put(route("patient.update", editForm.id), {
        onSuccess: () => {
            showEditModal.value = false;
        },
    });
};

const formDelete = useForm({});

function openDeleteModal(item) {
    selectedItem.value = item;
    showDeleteModal.value = true;
}

function closeDeleteModal() {
    showDeleteModal.value = false;
    selectedItem.value = null;
}

function deleteItem() {
    formDelete.delete(route("patient.destroy", selectedItem.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
        },
    });
}

function formatTanggal(data, format) {
    return dayjs(data).format(format);
}
</script>

<template>
    <Layout>

        <Head title="Patient" />

        <div v-if="flashMessage" class="toast toast-top toast-end">
            <div class="alert alert-success">
                <span>{{ flashMessage }}</span>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
  <h1 class="text-2xl font-bold text-gray-700 flex items-center gap-2">
    <Users class="w-6 h-6 text-blue-500" /> Daftar Pasien
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
        <th class="px-4 py-3 text-left text-sm font-semibold">NIK</th>
        <th class="px-4 py-3 text-left text-sm font-semibold">No HP</th>
        <th class="px-4 py-3 text-left text-sm font-semibold">Tgl Lahir</th>
        <th class="px-4 py-3 text-center text-sm font-semibold">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
      <tr
        v-for="patient in patients.data"
        :key="patient.id"
        class="hover:bg-gray-50 transition"
      >
        <td class="px-4 py-3 text-sm text-gray-700 font-medium">{{ patient.nama }}</td>
        <td class="px-4 py-3 text-sm text-gray-600">{{ patient.nik }}</td>
        <td class="px-4 py-3 text-sm text-gray-600">{{ patient.no_hp }}</td>
        <td class="px-4 py-3 text-sm text-gray-600">
          {{ formatTanggal(patient.tanggal_lahir, "DD-MM-YYYY") }}
        </td>
        <td class="px-4 py-3 text-center flex justify-center gap-2">
          <!-- Edit -->
                            <button
                                class="flex items-center gap-2 px-3 py-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded-md shadow text-sm transition"
                                @click="openEditModal(patient)"
                            >
                                <i class="fa-solid fa-pen"></i>
                                Edit
                            </button>

                            <!-- Delete -->
                            <button
                                @click="openDeleteModal(patient)"
                                class="flex items-center gap-2 px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow text-sm transition"
                            >
                                <i class="fa-solid fa-trash"></i>
                                Hapus
                            </button>
        </td>
      </tr>
    </tbody>
  </table>

</div>
<!-- Pagination -->
<div class="flex justify-end p-4">
  <Pagination :elements="patients" />
</div>


        <!-- ✅ Modal Tambah Pasien -->
<div
  v-if="showModal"
  class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50"
>
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6">
    <!-- Header -->
    <div class="flex justify-between items-center pb-3 mb-4">
      <h3 class="text-xl font-semibold text-gray-800">Tambah Pasien</h3>
      <button
        @click="showModal = false"
        class="text-gray-400 hover:text-gray-600 transition"
      >
        <i class="fa-solid fa-xmark text-lg"></i>
      </button>
    </div>

    <!-- Form -->
    <form @submit.prevent="storePatient" class="space-y-4">
      <!-- Nama -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Nama Pasien</label>
        <input
          type="text"
          v-model="form.nama"
          class="input input-bordered w-full mt-1"
          placeholder="Masukkan nama pasien"
        />
        <p v-if="form.errors.nama" class="text-red-500 text-xs mt-1">
          {{ form.errors.nama }}
        </p>
      </div>

      <!-- NIK -->
      <div>
        <label class="block text-sm font-medium text-gray-700">NIK</label>
        <input
          type="number"
          v-model="form.nik"
          class="input input-bordered w-full mt-1"
          placeholder="Masukkan NIK"
        />
        <p v-if="form.errors.nik" class="text-red-500 text-xs mt-1">
          {{ form.errors.nik }}
        </p>
      </div>

      <!-- No HP -->
      <div>
        <label class="block text-sm font-medium text-gray-700">No HP</label>
        <input
          type="number"
          v-model="form.no_hp"
          class="input input-bordered w-full mt-1"
          placeholder="Masukkan nomor HP"
        />
        <p v-if="form.errors.no_hp" class="text-red-500 text-xs mt-1">
          {{ form.errors.no_hp }}
        </p>
      </div>

      <!-- Tanggal Lahir -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
        <input
          type="date"
          v-model="form.tanggal_lahir"
          class="input input-bordered w-full mt-1"
        />
        <p v-if="form.errors.tanggal_lahir" class="text-red-500 text-xs mt-1">
          {{ form.errors.tanggal_lahir }}
        </p>
      </div>

      <!-- Actions -->
      <div class="flex justify-end gap-3 pt-3 mt-6">
        <button
          type="button"
          class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100 transition"
          @click="showModal = false"
        >
          Batal
        </button>
        <button
          type="submit"
          class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition disabled:opacity-50"
          :disabled="form.processing"
        >
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>


        <!-- ✅ Modal Edit Pasien -->
<div
  v-if="showEditModal"
  class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50 transition"
>
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-5">
      <h3 class="text-xl font-semibold text-gray-800">Edit Pasien</h3>
      <button
        @click="showEditModal = false"
        class="text-gray-400 hover:text-gray-600 transition"
      >
        <i class="fa-solid fa-xmark text-lg"></i>
      </button>
    </div>

    <!-- Form -->
    <form @submit.prevent="updatePatient" class="space-y-4">
      <!-- Nama -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Nama Pasien</label>
        <input
          type="text"
          v-model="editForm.nama"
          class="input input-bordered w-full mt-1"
          placeholder="Masukkan nama pasien"
        />
        <p v-if="editForm.errors.nama" class="text-red-500 text-xs mt-1">
          {{ editForm.errors.nama }}
        </p>
      </div>

      <!-- NIK -->
      <div>
        <label class="block text-sm font-medium text-gray-700">NIK</label>
        <input
          type="number"
          v-model="editForm.nik"
          class="input input-bordered w-full mt-1"
          placeholder="Masukkan NIK"
        />
        <p v-if="editForm.errors.nik" class="text-red-500 text-xs mt-1">
          {{ editForm.errors.nik }}
        </p>
      </div>

      <!-- No HP -->
      <div>
        <label class="block text-sm font-medium text-gray-700">No HP</label>
        <input
          type="number"
          v-model="editForm.no_hp"
          class="input input-bordered w-full mt-1"
          placeholder="Masukkan nomor HP"
        />
        <p v-if="editForm.errors.no_hp" class="text-red-500 text-xs mt-1">
          {{ editForm.errors.no_hp }}
        </p>
      </div>

      <!-- Tanggal Lahir -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
        <input
          type="date"
          v-model="editForm.tanggal_lahir"
          class="input input-bordered w-full mt-1"
        />
        <p v-if="editForm.errors.tanggal_lahir" class="text-red-500 text-xs mt-1">
          {{ editForm.errors.tanggal_lahir }}
        </p>
      </div>

      <!-- Actions -->
      <div class="flex justify-end gap-3 pt-2">
        <button
          type="button"
          class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100 transition"
          @click="showEditModal = false"
        >
          Batal
        </button>
        <button
          type="submit"
          class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition disabled:opacity-50"
          :disabled="editForm.processing"
        >
          Update
        </button>
      </div>
    </form>
  </div>
</div>


      <!-- ✅ Modal Hapus Pasien -->
<div
  v-if="showDeleteModal"
  class="fixed inset-0 flex items-start justify-center bg-black/50 backdrop-blur-sm z-50 transition pt-20"
>
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-4">
      <div class="flex items-center justify-center w-10 h-10 bg-red-100 text-red-600 rounded-full">
        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
      </div>
      <h2 class="text-xl font-semibold text-gray-800">Hapus Pasien</h2>
    </div>

    <!-- Body -->
    <p class="text-gray-600">
      Apakah Anda yakin ingin menghapus pasien
      <strong class="text-gray-900">{{ selectedItem?.nama }}</strong>?
    </p>

    <!-- Actions -->
    <div class="flex justify-end gap-3 mt-6">
      <button
        @click="closeDeleteModal"
        class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100 transition"
      >
        Batal
      </button>
      <button
        @click="deleteItem"
        class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition"
      >
        Hapus
      </button>
    </div>
  </div>
</div>


    </Layout>
</template>
