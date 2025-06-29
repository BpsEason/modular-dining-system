<template>
  <div v-if="isLoading" class="flex justify-center items-center h-screen">
    <p>Loading...</p>
  </div>
  <div v-else class="dashboard-container p-4 sm:p-6 md:p-8 font-sans max-w-7xl mx-auto animate-fade-in">
    <header class="flex justify-between items-center mb-6 sm:mb-8">
      <h1 class="text-2xl sm:text-3xl font-bold text-dark">餐廳後台管理儀表板</h1>
      <div class="flex items-center space-x-4">
        <span class="text-gray-700 hidden sm:block">Hello, {{ authStore.user?.name || 'Guest' }}! (Tenant: {{ authStore.tenantId }})</span>
        <button @click="handleLogout" class="px-4 py-2 bg-primary text-white font-semibold rounded-lg shadow-md hover:bg-red-600 transition duration-300">
          登出
        </button>
      </div>
    </header>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
      <div class="card border-primary">
        <CustomerList :customers="customers" />
      </div>
      <div class="card border-secondary">
        <RecommendationCard :recommendations="recommendations" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'vue-toastification';
import { useRouter } from 'vue-router';
import { customerApi, recommendationApi } from '../api';
import { useAuthStore } from '../store/auth';
import CustomerList from '../components/CustomerList.vue';
import RecommendationCard from '../components/RecommendationCard.vue';

const toast = useToast();
const router = useRouter();
const authStore = useAuthStore();
const customers = ref([]);
const recommendations = ref([]);
const isLoading = ref(true);

const fetchDashboardData = async () => {
  isLoading.value = true;
  try {
    const customerResponse = await customerApi.getCustomers();
    customers.value = customerResponse.data.data;
  } catch (error) {
    console.error('Failed to fetch customers:', error);
    toast.error('無法載入顧客列表，請稍後再試。');
  }

  try {
    const recoResponse = await recommendationApi.getRecommendations(101);
    recommendations.value = recoResponse.data.items;
  } catch (error) {
    console.error('Failed to fetch recommendations:', error);
    toast.error('無法載入推薦內容，請稍後再試。');
  }
  isLoading.value = false;
};

const handleLogout = () => {
  authStore.logout();
  router.push('/login');
  toast.success('您已成功登出。');
};

onMounted(() => {
  if (authStore.token) {
    fetchDashboardData();
  }
});
</script>
