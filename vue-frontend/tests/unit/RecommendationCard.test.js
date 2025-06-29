import { describe, it, expect, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import { createTestingPinia } from '@pinia/testing';
import RecommendationCard from '../../src/components/RecommendationCard.vue';

describe('RecommendationCard.vue', () => {
  it('根據屬性渲染推薦項目', () => {
    const recommendations = [
      { id: 1, name: 'Burger', score: 0.95 },
      { id: 2, name: 'Fries', score: 0.88 },
    ];
    const wrapper = mount(RecommendationCard, {
      props: { recommendations },
      global: {
        plugins: [createTestingPinia({ createSpy: vi.fn })],
      },
    });

    const recommendationItems = wrapper.findAll('li');
    expect(recommendationItems).toHaveLength(2);
    expect(recommendationItems[0].text()).toContain('Burger');
    expect(recommendationItems[1].text()).toContain('Fries');
  });

  it('當推薦列表為空時顯示 "載入推薦內容中..." 訊息', () => {
    const wrapper = mount(RecommendationCard, {
      props: { recommendations: [] },
      global: {
        plugins: [createTestingPinia({ createSpy: vi.fn })],
      },
    });

    expect(wrapper.text()).toContain('載入推薦內容中...');
    expect(wrapper.find('ul').exists()).toBe(false);
  });
});
