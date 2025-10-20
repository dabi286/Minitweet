import { mount } from "@vue/test-utils"
import { describe, it, expect, vi } from "vitest"
import AuthPage from "./AuthPage.vue"

describe("AuthPage.vue", () => {
  it("renders login form by default", () => {
    const wrapper = mount(AuthPage, {
      global: { mocks: { $router: { push: vi.fn() } } },
    })
    expect(wrapper.text()).toContain("Welcome to MiniTweet")
  })

  it("switches to sign-up form when toggle button is clicked", async () => {
    const wrapper = mount(AuthPage, {
      global: { mocks: { $router: { push: vi.fn() } } },
    })

    const toggleButton = wrapper.findAll("button").find(b => b.text().includes("Sign Up"))
    await toggleButton.trigger("click")

    expect(wrapper.text()).toMatch(/create account/i)
  })

  it("has email and password fields", () => {
    const wrapper = mount(AuthPage, {
      global: { mocks: { $router: { push: vi.fn() } } },
    })
    expect(wrapper.find('input[type="email"]').exists()).toBe(true)
    expect(wrapper.find('input[type="password"]').exists()).toBe(true)
  })
})
