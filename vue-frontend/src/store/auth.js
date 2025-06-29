import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  id: 'auth',
  state: () => ({
    token: null,
    tenantId: '1', // 預設租戶 ID
    user: null,
  }),
  actions: {
    async login(email, password) {
      try {
        const response = await axios.post('http://localhost/api/v1/login', { email, password });
        this.token = response.data.token;
        this.user = response.data.user;
        return true;
      } catch (error) {
        console.error('Login failed:', error);
        this.token = null;
        this.user = null;
        return false;
      }
    },
    logout() {
      this.token = null;
      this.user = null;
    },
    setTenantId(tenantId) {
      this.tenantId = tenantId;
    },
  },
  persist: true,
});
