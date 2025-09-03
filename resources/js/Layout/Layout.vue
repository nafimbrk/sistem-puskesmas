<script setup>
import Navbar from "../Component/Navbar.vue";
import Sidebar from "../Component/Sidebar.vue";
</script>

<template>
    <div class="min-h-screen flex flex-col bg-base-200">
        <!-- ✅ Navbar -->
        <Navbar />

        <div class="flex flex-1">
            <!-- ✅ Sidebar -->
            <Sidebar />

            <!-- ✅ Main Content -->
            <main class="flex-1 p-6 bg-base-100 rounded-tl-2xl shadow-inner">
                <slot />
            </main>
        </div>
    </div>
</template>
