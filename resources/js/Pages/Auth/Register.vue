<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import AuthLayout from "../../Layout/AuthLayout.vue";
import { route } from "ziggy-js";

const form = useForm({
    name: "",
    email: "",
    password: "",
});

const register = () => {
    form.post(route("register.store"));
};
</script>

<template>
    <Head title="Register" />

    <AuthLayout>
        <div class="max-w-sm ml-56">
            <h4 class="text-2xl font-bold dark:text-white text-center">Register</h4>

            <form @submit.prevent="register" class="mt-10">
                <div class="mb-5">
                    <label class="block">Nama Pasien</label>
                        <input
                            type="text"
                            v-model="form.nama"
                            class="input input-bordered w-full"
                            placeholder="Nama"
                        />
                    <label class="block">Nama</label>
                    >
                    <p v-if="form.errors.name" class="text-red-600 italic">
                        {{ form.errors.name }}
                    </p>
                    <input
                            type="text"
                            v-model="form.nama"
                            class="input input-bordered w-full"
                            placeholder="Nama"
                        />

                </div>
                <div class="mb-5">
                    <label
                        for="email"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                        >Email</label
                    >
                    <p v-if="form.errors.email" class="text-red-600 italic">
                        {{ form.errors.email }}
                    </p>
                    <input
                        type="email"
                        v-model="form.email"
                        id="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    />
                </div>
                <div class="mb-5">
                    <label
                        for="password"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                        >Password</label
                    >
                    <p v-if="form.errors.password" class="text-red-600 italic">
                        {{ form.errors.password }}
                    </p>
                    <input
                        type="password"
                        v-model="form.password"
                        id="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    />
                </div>
                <div class="flex justify-end mb-5">
                    <Link
                        :href="route('login')"
                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                    >
                        Login
                    </Link>
                </div>

                <button
                    type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >
                    Submit
                </button>
            </form>

            <!-- ✅ Modal Tambah Pasien -->
        <!-- <div
            class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h3 class="font-bold text-lg mb-4">Tambah Pasien</h3>

                <form @submit.prevent="storePatient">
                    <div class="mb-3">
                        <label class="block">Nama Pasien</label>
                        <input
                            type="text"
                            v-model="form.nama"
                            class="input input-bordered w-full"
                            placeholder="Nama"
                        />
                        <p v-if="form.errors.nama" class="text-red-500 text-sm">
                            {{ form.errors.nama }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="block">NIK</label>
                        <input
                            type="number"
                            v-model="form.nik"
                            class="input input-bordered w-full"
                            placeholder="NIK"
                        />
                        <p v-if="form.errors.nik" class="text-red-500 text-sm">
                            {{ form.errors.nik }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="block">No HP</label>
                        <input
                            type="number"
                            v-model="form.no_hp"
                            class="input input-bordered w-full"
                            placeholder="No HP"
                        />
                        <p
                            v-if="form.errors.no_hp"
                            class="text-red-500 text-sm"
                        >
                            {{ form.errors.no_hp }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="block">Tanggal Lahir</label>
                        <input
                            type="date"
                            v-model="form.tanggal_lahir"
                            class="input input-bordered w-full"
                        />
                        <p
                            v-if="form.errors.tanggal_lahir"
                            class="text-red-500 text-sm"
                        >
                            {{ form.errors.tanggal_lahir }}
                        </p>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button
                            type="button"
                            class="btn"
                            @click="showModal = false"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="btn btn-success"
                            :disabled="form.processing"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div> -->
        </div>
    </AuthLayout>
</template>
