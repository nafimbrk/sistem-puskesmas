<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AuthLayout from "../../Layout/AuthLayout.vue";
import { route } from "ziggy-js";

const form = useForm({
    email: "",
    password: "",
});

const login = () => {
    form.post(route("login.store"));
};
</script>

<template>
    <Head title="Login" />

    <AuthLayout>
<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
            <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
                <!-- Judul -->
                <h4 class="text-3xl font-bold text-center text-primary mb-8">Login</h4>

                <!-- Alert Error -->
                <div
                    v-if="$page.props.flash.error"
                    class="flex items-center p-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-700 dark:text-red-400 mb-5"
                    role="alert"
                >
                    <span>{{ $page.props.flash.error }}</span>
                </div>

                <!-- Alert Success -->
                <div
                    v-if="$page.props.flash.success"
                    class="flex items-center p-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-700 dark:text-green-400 mb-5"
                    role="alert"
                >
                    <span>{{ $page.props.flash.success }}</span>
                </div>

                <!-- Form -->
                <form @submit.prevent="login" class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input
                            type="email"
                            v-model="form.email"
                            id="email"
                            class="input input-bordered w-full focus:ring-2 focus:ring-primary focus:border-primary rounded-lg px-4 py-2 transition"
                            placeholder="Masukkan email"
                        />
                        <p v-if="form.errors.email" class="text-red-600 italic text-sm mt-1">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <input
                            type="password"
                            v-model="form.password"
                            id="password"
                            class="input input-bordered w-full focus:ring-2 focus:ring-primary focus:border-primary rounded-lg px-4 py-2 transition"
                            placeholder="Masukkan password"
                        />
                        <p v-if="form.errors.password" class="text-red-600 italic text-sm mt-1">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Button Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-2.5 rounded-lg text-white bg-primary hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed transition font-semibold text-lg"
                    >
                        <span v-if="!form.processing">Login</span>
                        <span v-else>Loading...</span>
                    </button>
                </form>
            </div>
        </div>
    </AuthLayout>
</template>
