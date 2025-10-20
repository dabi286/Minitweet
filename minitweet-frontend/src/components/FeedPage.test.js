import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import FeedPage from './FeedPage.vue'

describe('FeedPage', () => {
  it('shows tweet input box', () => {
    const wrapper = mount(FeedPage)
    expect(wrapper.text()).toContain("What's happening")
  })
})
