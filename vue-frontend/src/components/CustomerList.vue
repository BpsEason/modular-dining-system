<template>
  <div class="customer-list bg-white p-6 rounded-lg">
    <h2 class="text-xl font-bold mb-4 text-dark">最新顧客</h2>
    <ul v-if="customers.length" class="space-y-2 max-h-64 overflow-y-auto">
      <li v-for="customer in customers" :key="customer.id" class="flex items-center p-2 rounded-md hover:bg-gray-100 transition duration-150">
        <span class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-800 font-bold rounded-full mr-3 text-sm">{{ customer.name.charAt(0) }}</span>
        <div class="flex-1">
          <div class="font-semibold text-gray-800">{{ customer.name }}</div>
          <div class="text-sm text-gray-500">{{ customer.email }}</div>
        </div>
      </li>
    </ul>
    <p v-else class="text-gray-500 italic">載入顧客中...</p>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  customers: {
    type: Array,
    required: true,
    default: () => [],
  },
});
</script>
