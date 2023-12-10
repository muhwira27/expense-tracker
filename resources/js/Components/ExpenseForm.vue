<template>
  <!-- Form Template -->
  <div class="py-8 px-16">
    <h2 class="text-xl font-semibold mb-6">{{ isEditMode ? 'Edit Pengeluaran' : 'Tambah Pengeluaran' }}</h2>
    <form @submit.prevent="submit">
      <!-- Form Fields -->
      <div class="mb-4">
        <label for="name"
          class="block text-sm font-medium text-gray-700">Nama Pengeluaran</label>
        <input type="text"
          id="name"
          v-model="form.name"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          required />
      </div>
      <div class="mb-4">
        <label for="type"
          class="block text-sm font-medium text-gray-700">Tipe Pengeluaran</label>
        <select id="type"
          v-model="form.type"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
          <option value="Makanan dan Minuman">Makanan dan Minuman</option>
          <option value="Transportasi">Transportasi</option>
          <option value="Hiburan">Hiburan</option>
          <option value="Pembayaran Bulanan">Pembayaran Bulanan</option>
        </select>
      </div>
      <div class="mb-4">
        <label for="amount"
          class="block text-sm font-medium text-gray-700">Jumlah Pengeluaran</label>
        <div class="relative flex w-full mt-1">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm">
              Rp.
            </span>
          </div>
          <input type="text"
            id="amount"
            v-model="formattedAmount"
            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm"
            required />
        </div>
      </div>
      <div class="mb-4">
        <label for="date"
          class="block text-sm font-medium text-gray-700">Tanggal</label>
        <input type="date"
          id="date"
          v-model="form.date"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          required />
      </div>
      <div class="mb-4">
        <label for="description"
          class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
        <textarea id="description"
          v-model="form.description"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
      </div>
      <div class="flex justify-end gap-4">
        <!-- Cancel and Save Buttons -->
        <Link :href="`/expenses`"
          class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-100">
        Batal
        </Link>
        <button type="submit"
          class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
          Simpan
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  isEditMode: Boolean,
  initialData: Object,
});

const form = reactive(
  props.initialData || {
    name: "",
    type: "",
    amount: null,
    description: "",
    date: "",
  }
);

const formattedAmount = computed({
  get() {
    if (form.amount) {
      const numericValue = parseInt(form.amount, 10);
      return numericValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    return '';
  },
  set(value) {
    form.amount = value.replace(/[^\d]/g, '');
  }
});

const submit = () => {
  const url = props.isEditMode ? `/expenses/${form.id}/edit` : "/expenses";
  Inertia.post(url, form);
};
</script>
