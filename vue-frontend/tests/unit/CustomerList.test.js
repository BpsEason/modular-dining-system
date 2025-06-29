import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import { createTestingPinia } from '@pinia/testing';
import CustomerList from '../../src/components/CustomerList.vue';

describe('CustomerList.vue', () => {
  it('根據屬性渲染顧客列表', () => {
    const customers = [
      { id: 1, name: 'Alice', email: 'alice@example.com' },
      { id: 2, name: 'Bob', email: 'bob@example.com' },
    ];
    const wrapper = mount(CustomerList, {
      props: { customers },
      global: {
        plugins: [createTestingPinia({ createSpy: vi.fn })],
      },
    });

    const customerItems = wrapper.findAll('li');
    expect(customerItems).toHaveLength(2);
    expect(customerItems[0].text()).toContain('Alice');
    expect(customerItems[1].text()).toContain('Bob');
  });

  it('當顧客列表為空時顯示 "載入顧客中..." 訊息', () => {
    const wrapper = mount(CustomerList, {
      props: { customers: [] },
      global: {
        plugins: [createTestingPinia({ createSpy: vi.fn })],
      },
    });
    
    expect(wrapper.text()).toContain('載入顧客中...');
    expect(wrapper.find('ul').exists()).toBe(false);
  });
});
