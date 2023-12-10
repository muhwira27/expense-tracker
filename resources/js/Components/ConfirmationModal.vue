<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <h2 class="text-xl font-medium mb-6">Apakah Anda yakin ingin menghapus item ini?</h2>
      <div class="flex justify-end">
        <button class="bg-red-500 hover:bg-red-700 text-white font-medium py-2 px-4 rounded mr-2"
          @click="$emit('close')">Tidak</button>
        <button class="w-16 bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded"
          @click="confirm">Ya</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    confirm() {
      this.$emit('confirm');
    }
  }
};
</script>
