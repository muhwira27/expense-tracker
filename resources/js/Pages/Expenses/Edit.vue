<template>
  <expense-form :is-edit-mode="true"
    :initial-data="expense" />
</template>

<script setup>
import ExpenseForm from '@/Components/ExpenseForm.vue';

const props = defineProps({
  expense: Object,
});
</script>
