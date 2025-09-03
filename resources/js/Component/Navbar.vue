<script setup>
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
</script>

<template>
<nav class="navbar bg-base-100 shadow-md px-6 sticky top-0 z-50">
    <div class="flex-1">
        <Link :href="route('patient.index')" class="text-xl font-bold text-primary hover:text-secondary">
            Layanan Pasien
        </Link>
    </div>
    <div class="flex items-center gap-4">

        <!-- Avatar User -->
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full">
                    <img
                        alt="User Avatar"
                        src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"/>
                </div>
            </div>
            <ul
                tabindex="0"
                class="menu menu-sm dropdown-content bg-base-100 rounded-box mt-3 w-52 p-2 shadow">
                <li>
                    <a class="justify-between">
                        Profile
                        <span class="badge">New</span>
                    </a>
                </li>
                <li><a>Settings</a></li>
                <li><a>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
</template>
