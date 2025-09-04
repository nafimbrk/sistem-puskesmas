<script setup>
import { Link, router, useForm } from "@inertiajs/vue3";
import Layout from "../../Layout/Layout.vue";
import { route } from "ziggy-js";
import { computed, onMounted, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import Pagination from "../../Component/Pagination.vue";
import { Head } from "@inertiajs/vue3";
import { ListOrdered } from "lucide-vue-next";

const showModal = ref(false); // ✅ kontrol modal
const showEditModal = ref(false);
const selectedQueue = ref(null);
const showDeleteModal = ref(false);
const selectedItem = ref(null);
const page = usePage();


const props = defineProps({
    queues: Array,
    filters: Object,
    patients: Array,
    polis: Array,
});

let search = ref(props.filters.search || "");
let startDate = ref(props.filters.startDate || "");
let endDate = ref(props.filters.endDate || "");
let status = ref(props.filters.status || "semua");
let poli = ref(props.filters.poli || "semua");

const handleSearch = () => {
    router.get(route("queue.index"), {
        search: search.value,
        startDate: startDate.value,
        endDate: endDate.value,
        status: status.value,
        poli: poli.value,
    });
};

const handleReset = () => {
    search.value = "";
    startDate.value = "";
    endDate.value = "";
    status.value = "semua";
    poli.value = "semua";
};

const flashMessage = computed(() => page.props.flash?.message);

// ✅ Form untuk tambah pasien
const form = useForm({
    patient_id: "",
    poli_id: "",
});

const storeQueue = () => {
    form.post(route("queue.store"), {
        onSuccess: () => {
            form.reset(); // reset form
            showModal.value = false; // tutup modal
        },
    });
};

// ✅ Form edit pasien
const editForm = useForm({
    id: "",
    patient_id: "",
    poli_id: "",
});

// Buka modal edit dengan data pasien
const openEditModal = (queue) => {
    selectedQueue.value = queue;
    editForm.id = queue.id;
    editForm.patient_id = queue.patient_id;
    editForm.poli_id = queue.poli_id;
    showEditModal.value = true;
};

// Update pasien
const updateQueue = () => {
    editForm.put(route("queue.update", editForm.id), {
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

function deleteQueue() {
    formDelete.delete(route("queue.destroy", selectedItem.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
        },
    });
}

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
            <h1
                class="text-2xl font-bold text-gray-700 flex items-center gap-2"
            >
                <ListOrdered class="w-6 h-6 text-blue-500" />
                Daftar Antrian
            </h1>

            <button
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition"
                @click="showModal = true"
            >
                <i class="fas fa-plus"></i> Tambah Antrian
            </button>
        </div>

        <!-- Filter Section (lebih simple & rapi) -->
        <div class="flex flex-wrap items-center gap-3 mb-5">
            <!-- Input Cari -->
            <div class="flex items-center gap-2 flex-1">
                <i class="fas fa-search text-gray-400"></i>
                <input
                    type="text"
                    v-model="search"
                    placeholder="Cari nama / poli..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                />
            </div>

            <select
                v-model="poli"
                class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
            >
                <option value="semua">Semua</option>
                <option v-for="p in polis" :key="p.id" :value="p.nama_poli">
                    {{ p.nama_poli }}
                </option>
            </select>

            <!-- Status -->
            <select
                v-model="status"
                class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
            >
                <option value="semua">Semua</option>
                <option value="menunggu">Menunggu</option>
                <option value="dipanggil">Dipanggil</option>
                <option value="selesai">Selesai</option>
            </select>

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
                        <th class="px-4 py-3 text-left text-sm font-semibold">
                            Nama Pasien
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">
                            Nama Poli
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">
                            No Antrian
                        </th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">
                            Status
                        </th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="queue in queues.data"
                        :key="queue.id"
                        class="hover:bg-gray-50 transition"
                    >
                        <td class="px-4 py-3 text-sm text-gray-700 font-medium">
                            {{ queue.patient.nama }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ queue.poli.nama_poli }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ queue.nomor_antrian }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <Link
                                v-if="queue.status === 'menunggu'"
                                :href="route('queue.ubahStatus', queue.id)"
                                method="put"
                                as="button"
                                class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 transition"
                            >
                                {{ queue.status }}
                            </Link>

                            <Link
                                v-else-if="queue.status === 'dipanggil'"
                                :href="route('queue.ubahStatuss', queue.id)"
                                method="put"
                                as="button"
                                class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition"
                            >
                                {{ queue.status }}
                            </Link>

                            <span
                                v-else
                                class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700"
                            >
                                {{ queue.status }}
                            </span>
                        </td>
                        <td
                            class="px-4 py-3 text-center flex justify-center gap-2"
                        >
                            <!-- Edit -->
                            <button
                                class="flex items-center gap-2 px-3 py-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded-md shadow text-sm transition"
                                @click="openEditModal(queue)"
                            >
                                <i class="fa-solid fa-pen"></i>
                                Edit
                            </button>

                            <!-- Delete -->
                            <button
                                @click="openDeleteModal(queue)"
                                class="flex items-center gap-2 px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md shadow text-sm transition"
                            >
                                <i class="fa-solid fa-trash"></i>
                                Hapus
                            </button>

                            <!-- Prescription -->
                            <Link
                                :href="route('queue.createPrescription', queue.id)"
                                as="button"
                                class="flex items-center gap-2 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow text-sm transition"
                            >
                                <i class="fa-solid fa-prescription"></i>
                                Buat Resep
                            </Link>
                        </td>

                        <td class="px-4 py-3">
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li
                                    v-for="p in queue.prescriptions"
                                    :key="p.id"
                                    class="flex justify-between items-center"
                                >
                                    <span
                                        >{{ p.diagnosis }} ({{
                                            p.prescription
                                        }})</span
                                    >
                                    <div class="flex gap-2">
                                        <button
                                            @click="openEditPrescription(p)"
                                            class="text-yellow-500 hover:text-yellow-700"
                                        >
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button
                                            @click="deletePrescription(p.id)"
                                            class="text-red-500 hover:text-red-700"
                                        >
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="flex justify-end p-4">
            <Pagination :elements="queues" />
        </div>

        <!-- ✅ Modal Tambah Pasien -->
        <div
            v-if="showModal"
            class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50"
        >
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6">
                <!-- Header -->
                <div class="flex justify-between items-center pb-3 mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Tambah Antrian
                    </h3>
                    <button
                        @click="showModal = false"
                        class="text-gray-400 hover:text-gray-600 transition"
                    >
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Form -->
                <form @submit.prevent="storeQueue" class="space-y-4">
                    <!-- Pilih Pasien -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Nama Pasien</label
                        >
                        <select
                            v-model="form.patient_id"
                            class="input input-bordered w-full mt-1"
                        >
                            <option value="">-- Pilih Pasien --</option>
                            <option
                                v-for="patient in props.patients"
                                :key="patient.id"
                                :value="patient.id"
                            >
                                {{ patient.nama }}
                            </option>
                        </select>
                        <p
                            v-if="form.errors.patient_id"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.patient_id }}
                        </p>
                    </div>

                    <!-- Pilih Poli -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Nama Poli</label
                        >
                        <select
                            v-model="form.poli_id"
                            class="input input-bordered w-full mt-1"
                        >
                            <option value="">-- Pilih Poli --</option>
                            <option
                                v-for="poli in props.polis"
                                :key="poli.id"
                                :value="poli.id"
                            >
                                {{ poli.nama_poli }}
                            </option>
                        </select>
                        <p
                            v-if="form.errors.poli_id"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.poli_id }}
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
                    <h3 class="text-xl font-semibold text-gray-800">
                        Edit Antrian
                    </h3>
                    <button
                        @click="showEditModal = false"
                        class="text-gray-400 hover:text-gray-600 transition"
                    >
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Form Edit Queue -->
                <form @submit.prevent="updateQueue" class="space-y-4">
                    <!-- Pilih Pasien -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Nama Pasien</label
                        >
                        <select
                            v-model="editForm.patient_id"
                            class="input input-bordered w-full mt-1"
                        >
                            <option value="">-- Pilih Pasien --</option>
                            <option
                                v-for="patient in props.patients"
                                :key="patient.id"
                                :value="patient.id"
                            >
                                {{ patient.nama }}
                            </option>
                        </select>
                        <p
                            v-if="editForm.errors.patient_id"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ editForm.errors.patient_id }}
                        </p>
                    </div>

                    <!-- Pilih Poli -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Nama Poli</label
                        >
                        <select
                            v-model="editForm.poli_id"
                            class="input input-bordered w-full mt-1"
                        >
                            <option value="">-- Pilih Poli --</option>
                            <option
                                v-for="poli in props.polis"
                                :key="poli.id"
                                :value="poli.id"
                            >
                                {{ poli.nama_poli }}
                            </option>
                        </select>
                        <p
                            v-if="editForm.errors.poli_id"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ editForm.errors.poli_id }}
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
                    <div
                        class="flex items-center justify-center w-10 h-10 bg-red-100 text-red-600 rounded-full"
                    >
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">
                        Hapus Antrian
                    </h2>
                </div>

                <!-- Body -->
                <p class="text-gray-600">
                    Apakah Anda yakin ingin menghapus antrian
                    <strong class="text-gray-900">{{
                        selectedItem?.nomor_antrian
                    }}</strong
                    >?
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
                        @click="deleteQueue"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>



    </Layout>
</template>
