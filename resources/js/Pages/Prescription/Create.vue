<script setup>
import { useForm, Head } from "@inertiajs/vue3";
import SignaturePad from "signature_pad";
import { ref, onMounted, watch } from "vue";
import { route } from "ziggy-js";
import Layout from "../../Layout/Layout.vue";

const props = defineProps({
    queueId: Number, // dikirim dari controller
});

// form inertia
const form = useForm({
    diagnosis: "",
    prescription: "",
    canvas_image: null,
    queue_id: props.queueId,
});

const canvasRef = ref(null);
let signaturePad = null;

// warna & ukuran pena
const penColor = ref("black");
const penSize = ref(2);

onMounted(() => {
    if (canvasRef.value) {
        const canvas = canvasRef.value;
        const ratio = Math.max(window.devicePixelRatio || 1, 1);

        // sesuaikan ukuran canvas dengan CSS (w-full h-80)
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);

        signaturePad = new SignaturePad(canvas, {
            backgroundColor: "white",
            penColor: penColor.value,
            minWidth: penSize.value,
            maxWidth: penSize.value + 1,
        });
    }
});

const resizeCanvas = () => {
    const canvas = canvasRef.value;
    if (!canvas) return;

    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);

    if (signaturePad) signaturePad.clear(); // reset biar gak ketarik
};

onMounted(() => {
    resizeCanvas();
    signaturePad = new SignaturePad(canvasRef.value, {
        backgroundColor: "white",
        penColor: penColor.value,
        minWidth: penSize.value,
        maxWidth: penSize.value + 1,
    });
    window.addEventListener("resize", resizeCanvas);
});



// update warna & ukuran pena
watch([penColor, penSize], () => {
    if (signaturePad) {
        signaturePad.penColor = penColor.value;
        signaturePad.minWidth = parseInt(penSize.value);
        signaturePad.maxWidth = parseInt(penSize.value) + 1;
    }
});

const clearCanvas = () => {
    if (signaturePad) signaturePad.clear();
};

const savePrescription = () => {
    if (signaturePad && !signaturePad.isEmpty()) {
        form.canvas_image = signaturePad.toDataURL("image/png");
    } else {
        form.canvas_image = null;
    }

    form.post(route("prescription.store"), {
        onSuccess: () => {
            // redirect balik ke halaman queue
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Tambah Prescription" />
    <Layout>
        <div class="max-w-3xl p-6 bg-white rounded-xl shadow">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                Buat Resep
            </h2>

            <form @submit.prevent="savePrescription" class="space-y-6">
                <!-- Diagnosis -->
                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >Diagnosis</label
                    >
                    <input
                        v-model="form.diagnosis"
                        type="text"
                        class="input input-bordered w-full mt-1"
                        placeholder="Masukan diagnosis"
                    />
                    <p
                        v-if="form.errors.diagnosis"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ form.errors.diagnosis }}
                    </p>
                </div>

                <!-- Prescription (teks) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >Resep (teks)</label
                    >
                    <textarea
                        v-model="form.prescription"
                        class="input input-bordered w-full mt-1 pt-2"
                        placeholder="Masukan resep"
                        rows="4"
                    ></textarea>
                    <p
                        v-if="form.errors.prescription"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ form.errors.prescription }}
                    </p>
                </div>

                <!-- Canvas Tulisan Dokter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >Tulisan Dokter</label
                    >
                    <canvas
                        ref="canvasRef"
                        class="border w-full h-80 rounded-md bg-white shadow"
                    ></canvas>

                    <!-- Tools -->
                    <div class="flex gap-2 mt-2 items-center">
                        <select v-model="penColor" class="border rounded px-2 py-1">
                            <option value="black">Hitam</option>
                            <option value="blue">Biru</option>
                            <option value="red">Merah</option>
                        </select>
                        <select v-model="penSize" class="border rounded px-2 py-1">
                            <option value="2">Tipis</option>
                            <option value="4">Sedang</option>
                            <option value="6">Tebal</option>
                        </select>
                        <button
                            type="button"
                            class="px-3 py-1 bg-gray-300 rounded"
                            @click="clearCanvas"
                        >
                            Hapus
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-3">
                    <button
                        type="button"
                        class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100 transition"
                        @click="$inertia.visit(route('queue.index'))"
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
    </Layout>
</template>
