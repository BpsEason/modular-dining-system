<template>
  <div class="recommendation-card bg-white p-6 rounded-lg">
    <h2 class="text-xl font-bold mb-4 text-dark">個人化推薦</h2>
    <ul v-if="recommendations.length" class="space-y-2 max-h-64 overflow-y-auto">
      <li v-for="item in recommendations" :key="item.id" class="flex items-center p-2 rounded-md hover:bg-gray-100 transition duration-150">
        <img :src="`https://picsum.photos/40/40?random=${item.id}`" class="rounded-md mr-3" alt="推薦餐點圖片" />
        <div class="flex-1">
          <div class="font-semibold text-gray-800">{{ item.name }}</div>
          <div class="text-sm text-gray-500">推薦指數: {{ item.score.toFixed(2) }}</div>
        </div>
      </li>
    </ul>
    <p v-else class="text-gray-500 italic">載入推薦內容中...</p>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  recommendations: {
    type: Array,
    required: true,
    default: () => [],
  },
});
</script>
