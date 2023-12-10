<template>
  <div class="px-14 py-8 bg-[#fcfbf9]">
    <section class="flex justify-between items-center mb-6">
      <h2 class="text-[22px] font-semibold">Daftar Pengeluaran</h2>
      <div class="flex items-center gap-2">
        <h3>Sort By</h3>
        <select @change="changeSort"
          class="border-gray-260 rounded-md h-9 text-sm bg-[#fcfbf9]">
          <option value="date">Date</option>
          <option value="name">Name</option>
          <option value="amount">Amount</option>
        </select>
        <button @click="toggleSortOrder"
          class="focus:outline-none">
          <!-- Ikon untuk ascending -->
          <img v-if="state.isAscending"
            width="26"
            height="26"
            src="https://img.icons8.com/external-creatype-outline-colourcreatype/64/external-descending-miscellaneous-user-interface-v1-creatype-outline-colourcreatype.png"
            alt="external-descending-miscellaneous-user-interface-v1-creatype-outline-colourcreatype" />
          <!-- Ikon untuk descending -->
          <img v-else
            width="26"
            height="26"
            src="https://img.icons8.com/external-creatype-outline-colourcreatype/64/external-descending-miscellaneous-user-interface-v1-creatype-outline-colourcreatype-2.png"
            alt="external-descending-miscellaneous-user-interface-v1-creatype-outline-colourcreatype-2" />
        </button>
        <Link href="/expenses/create"
          class="bg-blue-500 hover:bg-blue-700 text-white text-[15px] ml-5 h-9 font-bold py-2 px-4 rounded">
        Tambah Pengeluaran
        </Link>
      </div>
    </section>

    <section class="space-y-4">
      <div v-for="expense in state.localExpenses"
        :key="expense.id"
        class="flex flex-col p-4 bg-[#fcfbf9] shadow rounded-lg">
        <div class="flex justify-between items-center">
          <div class="flex items-center">
            <!-- Icon sementara -->
            <span class="text-lg mr-4">🚕</span>
            <!-- Expense Name and Date -->
            <div class="w-48">
              <div class="font-medium mb-1">{{ expense.name }}</div>
              <div class="text-gray-600 text-sm">{{ formatDate(expense.date) }}</div>
            </div>
          </div>
          <!-- Expense Type and Amount -->
          <div class="flex flex-col items-center w-36">
            <div class="text-base font-medium text-center">{{ expense.type }}</div>
          </div>
          <div class="flex flex-col items-start w-44">
            <div class="text-lg font-medium text-start">{{ currency(expense.amount) }}</div>
          </div>
          <!-- Action Icons -->
          <div class="flex gap-4 items-center flex-wrap">
            <!-- Show Details Icon -->
            <div class="text-gray-600 cursor-pointer"
              @click="toggleDescription(expense)">
              <svg xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7" />
              </svg>
            </div>
            <!-- Edit Icon -->
            <Link :href="`/expenses/${expense.id}/edit`">
            <div class="text-gray-600 cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#000000"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"></path>
                <polygon points="18 2 22 6 12 16 8 16 8 12 18 2"></polygon>
              </svg>
            </div>
            </Link>
            <!-- Delete Icon -->
            <div class="text-gray-600 cursor-pointer"
              @click="deleteExpense(expense)">
              <svg xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#840808"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10"
                  y1="11"
                  x2="10"
                  y2="17"></line>
                <line x1="14"
                  y1="11"
                  x2="14"
                  y2="17"></line>
              </svg>
            </div>
          </div>
        </div>
        <!-- Expense Description -->
        <div v-if="expense.showDescription"
          class="mt-4 text-sm text-gray-800">
          {{ expense.description || '-' }}
        </div>
      </div>
    </section>

    <!-- Confirmation Modal -->
    <confirmation-modal v-if="state.showDeleteModal"
      :expenseIdToDelete="state.selectedExpense?.id"
      @confirm="confirmDelete"
      @close="closeModal" />
  </div>
</template>

<script setup>
import { reactive, onMounted, toRefs } from "vue";
import { Link, router } from '@inertiajs/vue3';
import ConfirmationModal from '../../Components/ConfirmationModal.vue';

const props = defineProps({
  expenses: Array
});

const state = reactive({
  localExpenses: [],
  showDeleteModal: false,
  selectedExpense: null,
  sortKey: 'date',
  isAscending: true
});

const initializeExpenses = () => {
  state.localExpenses = props.expenses.map(expense => ({
    ...expense,
    showDescription: false
  }));
};

const sortExpenses = () => {
  state.localExpenses.sort((a, b) => {
    const valueA = state.sortKey === 'amount' ? parseFloat(a[state.sortKey]) : a[state.sortKey].toLowerCase();
    const valueB = state.sortKey === 'amount' ? parseFloat(b[state.sortKey]) : b[state.sortKey].toLowerCase();

    if (valueA < valueB) return state.isAscending ? -1 : 1;
    if (valueA > valueB) return state.isAscending ? 1 : -1;
    return 0;
  });
};

const toggleSortOrder = () => {
  state.isAscending = !state.isAscending;
  sortExpenses(); //
};

const changeSort = (event) => {
  state.sortKey = event.target.value;
  sortExpenses();
};

const currency = (value) => {
  if (!value && value !== 0) return 'Rp0';
  return 'Rp. ' + parseInt(value, 10).toLocaleString('id-ID');
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric', month: 'long', day: 'numeric'
  });
};

const toggleDescription = (expense) => {
  expense.showDescription = !expense.showDescription;
};

const deleteExpense = (expense) => {
  state.selectedExpense = expense;
  state.showDeleteModal = true;
};

const confirmDelete = () => {
  if (state.selectedExpense) {
    router.delete(`/expenses/${state.selectedExpense.id}`, {
      onSuccess: () => router.visit('/expenses')
    });
  }
};

const closeModal = () => {
  state.showDeleteModal = false;
};

onMounted(initializeExpenses);
const exposedState = toRefs(state);
</script>
