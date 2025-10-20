<template>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-purple-100 to-blue-100 font-[Poppins]">
        <div class="relative w-[950px] h-[520px] bg-white rounded-2xl shadow-2xl overflow-hidden flex transition-all duration-700 md:scale-[1.1] lg:scale-[1.25]">

            <!-- AUTH CONTAINER -->
            <div class="absolute inset-0 overflow-hidden">

                <!-- LOGIN FORM -->
                <div class="absolute left-0 top-0 w-1/2 h-full flex flex-col justify-center items-center px-12 bg-white transition-all duration-700 ease-in-out z-20">
                    <div class="text-center w-full max-w-sm">
                        <h2 class="text-[2.5rem] font-bold text-text-gray-800 mb-8 tracking-tight">
                            Welcome to MiniTweet
                        </h2>
                        <form class="space-y-6">
                            <div>
                                <input
                                    type="email"
                                    placeholder="Email address"
                                    class="w-full border-b border-gray-300 py-2 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-600 text-[15px]"
                                />
                            </div>
                            <div>
                                <input
                                    type="password"
                                    placeholder="Password"
                                    class="w-full border-b border-gray-300 py-2 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-600 text-[15px]"
                                />
                            </div>
                            <button
                                type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full py-3 mt-6 tracking-widest transition"
                            >
                                SIGN IN
                            </button>
                            <p class="text-xs text-center text-gray-500 mt-3 hover:text-blue-600 cursor-pointer">
                                Forgot your password?
                            </p>
                        </form>
                    </div>
                </div>

                <!-- REGISTER FORM -->
                <div class="absolute right-0 top-0 w-1/2 h-full flex flex-col justify-center items-center px-12 bg-white transition-all duration-700 ease-in-out z-20">
                    <div class="text-center w-full max-w-sm">
                        <h2 class="text-3xl font-extrabold text-gray-800 mb-5">
                            Create Account
                        </h2>

                        <form class="space-y-6">
                            <div class="flex gap-6">
                                <input
                                    type="text"
                                    placeholder="First name"
                                    class="w-1/2 border-b border-gray-300 py-2 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-600 text-[15px]"
                                />
                                <input
                                    type="text"
                                    placeholder="Last name"
                                    class="w-1/2 border-b border-gray-300 py-2 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-600 text-[15px]"
                                />
                            </div>

                            <input
                                type="email"
                                placeholder="Email address"
                                class="w-full border-b border-gray-300 py-2 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-600 text-[15px]"
                            />

                            <input
                                type="password"
                                placeholder="Password"
                                class="w-full border-b border-gray-300 py-2 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-600 text-[15px]"
                            />

                            <button
                                type="submit"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-full py-3 mt-6 tracking-widest transition"
                            >
                                SIGN UP
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- SLIDING PANEL -->
            <div class="absolute top-0 left-1/2 w-1/2 h-full bg-gradient-to-r from-purple-600 to-blue-600 text-white flex flex-col justify-center items-center text-center px-10 transition-transform duration-700 ease-in-out z-10">
                <div>
                    <h2 class="font-inter text-4xl font-extrabold mb-4">
                        Don't have an account?
                    </h2>
                    <p class="text-sm mb-6">Start your journey in one click</p>
                    <button class="px-6 py-2 border-2 border-white rounded-full font-semibold hover:bg-white hover:text-purple-600 transition">
                        Sign Up
                    </button>
                </div>

                <div>
                    <h2 class="text-4xl font-extrabold mb-4">
                        Already have an account?
                    </h2>
                    <p class="text-sm mb-6">Sign in with your email & password</p>
                    <button class="px-6 py-2 border-2 border-white rounded-full font-semibold hover:bg-white hover:text-blue-600 transition">
                        Sign In
                    </button>
                </div>
            </div>

        </div>
    </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
