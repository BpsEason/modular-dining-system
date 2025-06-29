<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-md animate-fade-in">
      <h2 class="text-3xl font-bold text-center mb-6 text-dark">SaaS 登入</h2>
      <form @submit.prevent="handleLogin">
        <div class="mb-4">
          <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
          <input
            type="email"
            id="email"
            v-model="email"
            placeholder="請輸入 Email"
            required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
        <div class="mb-6">
          <label for="password" class="block text-gray-700 font-bold mb-2">密碼</label>
          <input
            type="password"
            id="password"
            v-model="password"
            placeholder="請輸入密碼"
            required
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
        <button
          type="submit"
          :disabled="isLoggingIn"
          class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300 disabled:bg-gray-400"
        >
          <span v-if="!isLoggingIn">登入</span>
          <span v-else>登入中...</span>
        </button>
      </form>
      <div class="mt-6 text-center text-gray-500 text-sm">
        <p>測試帳號:</p>
        <p>Admin: `admin@example.com` / `password`</p>
        <p>Manager: `manager@example.com` / `password`</p>
        <p>Staff: `staff@example.com` / `password`</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import { useAuthStore } from '../store/auth';

const email = ref('');
const password = ref('');
const isLoggingIn = ref(false);
const authStore = useAuthStore();
const router = useRouter();
const toast = useToast();

const handleLogin = async () => {
  isLoggingIn.value = true;
  const success = await authStore.login(email.value, password.value);
  isLoggingIn.value = false;
  if (success) {
    toast.success('登入成功！');
    router.push('/');
  } else {
    toast.error('登入失敗，請檢查您的憑證。');
  }
};
</script>
