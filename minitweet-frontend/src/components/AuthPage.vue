<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();
const isSignUp = ref(false);

const loginEmail = ref("");
const loginPassword = ref("");
const registerFirstname = ref("");
const registerLastname = ref("");
const registerEmail = ref("");
const registerPassword = ref("");

const loginError = ref("");
const registerError = ref("");

const toggleForm = () => {
  isSignUp.value = !isSignUp.value;
  loginError.value = "";
  registerError.value = "";
};

const handleRegister = async () => {
  try {
    registerError.value = "";
    const response = await axios.post("http://localhost:8000/api/register", {
      firstname: registerFirstname.value,
      surname: registerLastname.value,
      email: registerEmail.value,
      password: registerPassword.value,
    });

    if (response.data?.token) {
      localStorage.setItem("token", response.data.token);
      registerError.value = "✅ Account created successfully!";
      isSignUp.value = true;

      registerFirstname.value = "";
      registerLastname.value = "";
      registerEmail.value = "";
      registerPassword.value = "";
    }

  } catch (error) {
    registerError.value =
      error.response?.data?.message ||
      "Registration failed. Please check your inputs.";
  }
};

// Handle Login
const handleLogin = async () => {
  try {
    loginError.value = "";
    const response = await axios.post("http://localhost:8000/api/login", {
      email: loginEmail.value,
      password: loginPassword.value,
    });

    if (response.data?.token) {
      localStorage.setItem("token", response.data.token);
      router.push("/feed");
    }
  } catch (error) {
    loginError.value =
      error.response?.data?.message || "Invalid email or password.";
  }
};
</script>


<template>
  <div
    class="flex items-center justify-center min-h-screen bg-gradient-to-br from-purple-100 to-blue-100 font-[Poppins]">
    <div
      class="relative w-[950px] h-[520px] bg-white rounded-2xl shadow-2xl overflow-hidden flex transition-all duration-700 md:scale-[1.1] lg:scale-[1.25]">
      <div class="absolute inset-0 overflow-hidden">
        <div class="absolute left-0 top-0 w-1/2 h-full flex flex-col justify-center items-center px-12 bg-white 
          transition-all duration-700 ease-in-out z-20" :class="isSignUp
          ? 'translate-x-[-100%] opacity-0 scale-95 pointer-events-none'
          : 'translate-x-0 opacity-100 scale-100 pointer-events-auto'">
          <div class="text-center w-full max-w-sm">
            <h2 class="text-[2.5rem] font-bold text-text-gray-800 mb-8 tracking-tight">
              Welcome to MiniTweet
            </h2>

            <form @submit.prevent="handleLogin" class="space-y-6">
              <div>
                <input type="email" v-model="loginEmail" required placeholder="Email address" class="w-full border-b border-gray-300 py-2 text-gray-800 placeholder-gray-400 
                 focus:outline-none focus:border-blue-600 text-[15px]" />
              </div>
              <div>
                <input type="password" v-model="loginPassword" required placeholder="Password" class="w-full border-b border-gray-300 py-2 text-gray-800 placeholder-gray-400 
                focus:outline-none focus:border-blue-600 text-[15px]" />
              </div>
              <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold 
                rounded-full py-3 mt-6 tracking-widest transition">
                SIGN IN
              </button>
              <p class="text-xs text-center text-gray-500 mt-3 hover:text-blue-600 cursor-pointer">
                Forgot your password?
              </p>
              <p v-if="loginError" class="text-red-500 text-sm mt-2 text-center">
                {{ loginError }}
              </p>
            </form>
          </div>
        </div>

        <div
          class="absolute right-0 top-0 w-1/2 h-full flex flex-col justify-center items-center px-12 bg-white transition-all duration-700 ease-in-out z-20"
          :class="isSignUp
            ? 'translate-x-0 opacity-100 scale-100 pointer-events-auto'
            : 'translate-x-full opacity-0 scale-95 pointer-events-none'">
          <div class="text-center w-full max-w-sm">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-5">
              Create Account
            </h2>
            <form @submit.prevent="handleRegister" class="space-y-6">
              <div class="flex gap-6">
                <input type="text" v-model="registerFirstname" required placeholder="First name" class="w-1/2 border-b border-gray-300 py-2 text-gray-800 placeholder-gray-400 
                focus:outline-none focus:border-blue-600 text-[15px]" />
                <input type="text" v-model="registerLastname" required placeholder="Last name" class="w-1/2 border-b border-gray-300 py-2 text-gray-800 placeholder-gray-400 
                focus:outline-none focus:border-blue-600 text-[15px]" />
              </div>
              <input type="email" v-model="registerEmail" required placeholder="Email address" class="w-full border-b border-gray-300 py-2 text-gray-800 placeholder-gray-400 
              focus:outline-none focus:border-blue-600 text-[15px]" />
              <input type="password" v-model="registerPassword" required placeholder="Password" class="w-full border-b border-gray-300 py-2 text-gray-800 placeholder-gray-400 
              focus:outline-none focus:border-blue-600 text-[15px]" />
              <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold 
              rounded-full py-3 mt-6 tracking-widest transition">
                SIGN UP
              </button>
              <p v-if="registerError" class="text-red-500 text-sm mt-2 text-center">
                {{ registerError }}
              </p>
            </form>
          </div>
        </div>
      </div>
      <!-- SLIDING PANEL -->
      <div
        class="absolute top-0 left-1/2 w-1/2 h-full bg-gradient-to-r from-purple-600 to-blue-600 text-white flex flex-col justify-center items-center text-center px-10 transition-transform duration-700 ease-in-out z-10"
        :class="isSignUp ? '-translate-x-full' : 'translate-x-0'">
        <transition name="fade" mode="out-in">
          <div v-if="!isSignUp" key="signup">
            <h2 class="font-inter text-4xl font-extrabold mb-4">
              Don't have an account?
            </h2>
            <p class="text-sm mb-6">Start your journey in one click</p>
            <button @click="toggleForm"
              class="px-6 py-2 border-2 border-white rounded-full font-semibold hover:bg-white hover:text-purple-600 transition">
              Sign Up
            </button>
          </div>
          <div v-else key="signin">
            <h2 class="text-4xl font-extrabold mb-4">
              Already have an account?
            </h2>
            <p class="text-sm mb-6">Sign in with your email & password</p>
            <button @click="toggleForm"
              class="px-6 py-2 border-2 border-white rounded-full font-semibold hover:bg-white hover:text-blue-600 transition">
              Sign In
            </button>
          </div>
        </transition>
      </div>
    </div>
  </div>
</template>
