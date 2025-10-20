<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import {
  HomeIcon,
  SearchIcon,
  BellIcon,
  MailIcon,
  BookmarkIcon,
  Flame,
  UserIcon,
  MoreHorizontalIcon,
  MessageCircle,
  Repeat2,
  Heart,
  Share2
} from "lucide-vue-next";

dayjs.extend(relativeTime);

const tweetContent = ref("");
const tweets = ref([]);
const userName = ref("");
const userInitials = ref("?");
const showSupport = ref(false);

const menu = [
  { name: "Home", icon: HomeIcon },
  { name: "Explore", icon: SearchIcon },
  { name: "Notifications", icon: BellIcon },
  { name: "Messages", icon: MailIcon },
  { name: "Bookmarks", icon: BookmarkIcon },
  { name: "Top Articles", icon: Flame },
  { name: "Profile", icon: UserIcon },
  { name: "More", icon: MoreHorizontalIcon },
];

const trendingTopics = [
  { title: "New species discovered", tweets: "23.1K" },
  { title: "AI Revolution 2025", tweets: "88.4K" },
  { title: "Election updates", tweets: "54.7K" },
];

const whoToFollow = [
  { name: "jzlendrga", username: "jzlendrga@gmail.com" },
  { name: "davevisual", username: "davey@gmail.com" },
  { name: "Celina", username: "prettyceline@gmail.com" },
];

const toggleSupport = () => {
  showSupport.value = !showSupport.value;
};

const formatHashtags = (text) => {
  if (!text) return "";
  return text.replace(/#(\w+)/g, '<strong class="font-semibold text-blue-600">#$1</strong>');
};

const fetchUser = async () => {
  try {
    const token = localStorage.getItem("token");
    const response = await axios.get("http://127.0.0.1:8000/api/user", {
      headers: { Authorization: `Bearer ${token}` },
    });
    const user = response.data;
    const first = user.firstname || user.first_name || user.name?.split(" ")[0] || "";
    const last = user.surname || user.lastname || user.last_name || user.name?.split(" ")[1] || "";
    userName.value = `${first} ${last}`.trim();
    userInitials.value =
      (first?.charAt(0)?.toUpperCase() || "") + (last?.charAt(0)?.toUpperCase() || "");
    if (!userInitials.value.trim()) {
      userInitials.value = userName.value.charAt(0).toUpperCase() || "?";
    }
  } catch (error) {
    console.error("Error loading user:", error);
  }
};

const fetchTweets = async () => {
  try {
    const token = localStorage.getItem("token");
    const response = await axios.get("http://127.0.0.1:8000/api/tweets", {
      headers: { Authorization: `Bearer ${token}` },
    });
    tweets.value = response.data;
  } catch (error) {
    console.error("Error fetching tweets:", error);
  }
};

const postTweet = async () => {
  if (!tweetContent.value.trim()) return;
  try {
    const token = localStorage.getItem("token");
    await axios.post(
      "http://127.0.0.1:8000/api/tweets",
      { content: tweetContent.value },
      { headers: { Authorization: `Bearer ${token}` } }
    );
    await fetchTweets();
    tweetContent.value = "";
  } catch (error) {
    console.error("Error posting tweet:", error);
  }
};

const toggleLike = async (tweet) => {
  try {
    const token = localStorage.getItem("token");
    await axios.post(
      `http://127.0.0.1:8000/api/tweets/${tweet.id}/like`,
      {},
      { headers: { Authorization: `Bearer ${token}` } }
    );
    tweet.is_liked = !tweet.is_liked;
    tweet.likes_count += tweet.is_liked ? 1 : -1;
  } catch (error) {
    console.error("Error toggling like:", error);
  }
};

const logout = () => {
  localStorage.removeItem("token");
  window.location.href = ("/auth");
};

onMounted(() => {
  fetchUser();
  fetchTweets();
});
</script>


<template>
  <div class="min-h-screen w-full bg-gray-50 flex">
    <aside class="w-1/5 bg-white p-6 border-r border-gray-200 flex flex-col justify-between">
      <div>
        <!-- Profile Section (Top) -->
        <div class="flex flex-col items-left mt-8 mb-6">
          <div
            class="w-14 h-14 rounded-full flex items-center justify-center bg-pink-500 text-white font-bold text-lg mb-2">
            {{ userInitials }}
          </div>
          <p class="font-semibold text-gray-800 text-sm">{{ userName }}</p>
          <p class="text-xs text-gray-500 mb-3">@{{ userName.replace(/\s+/g, '').toLowerCase() }}</p>
          <div class="flex gap-3 text-sm text-gray-700">
            <p><span class="font-bold text-gray-900">7</span> Following</p>
            <p><span class="font-bold text-gray-900">2</span> Followers</p>
          </div>
        </div>


        <!-- Navigation Menu -->

        <nav class="space-y-6">
          <div v-for="item in menu" :key="item.name"
            class="flex items-center gap-3 text-gray-700 hover:text-pink-500 cursor-pointer">
            <component :is="item.icon" class="w-5 h-5" />
            <span class="text-lg font-medium">{{ item.name }}</span>
          </div>
        </nav>

        <!-- ✅ Settings and Support Dropdown BELOW '...More' -->
        <div class="mt-6 relative">
          <button @click="showSupport = !showSupport"
            class="w-full bg-gray-50 border border-gray-200 py-3 rounded-full font-semibold text-gray-700 hover:bg-gray-100 transition flex justify-between items-center px-4">
            <span>Settings and Support</span>
            <svg :class="{ 'rotate-180': showSupport }" xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4 text-gray-500 transition-transform duration-200" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
            <div v-if="showSupport"
              class="absolute left-0 w-full bg-white mt-2 rounded-xl border border-gray-200 shadow-md py-2 z-20">
              <button
                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">
                Help Center
              </button>
              <button @click="logout"
                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">
                Logout
              </button>
            </div>
          </transition>
        </div>
      </div>
    </aside>

    <!-- CENTER FEED -->
    <main class="flex-1 flex justify-center py-10">
      <div class="w-full max-w-2xl bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <!-- Tweet Box -->
        <div class="flex gap-4 mb-8 bg-gray-50 rounded-2xl p-5 border border-gray-200">
          <div class="flex items-center justify-center w-12 h-12 rounded-full bg-pink-500 text-white font-bold text-lg">
            {{ userInitials }}
          </div>
          <div class="flex-1">
            <textarea v-model="tweetContent" maxlength="280" placeholder="What's happening?" class="w-full resize-none rounded-2xl bg-gray-100 px-4 py-3 text-gray-800 placeholder-gray-400
                     focus:outline-none focus:bg-white transition-all duration-200" rows="3"></textarea>
            <div class="flex justify-between items-center mt-2">
              <p class="text-sm text-gray-500">{{ 280 - tweetContent.length }} characters remaining</p>
              <button @click="postTweet" class="tweet-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="icon">
                  <path
                    d="M23 3a10.9 10.9 0 01-3.14 1.53A4.48 4.48 0 0016.5 2a4.48 4.48 0 00-4.47 4.47c0 .35.04.69.1 1.02A12.94 12.94 0 013 3.1a4.48 4.48 0 001.39 5.97A4.41 4.41 0 012 8.1v.05a4.48 4.48 0 003.6 4.39 4.48 4.48 0 01-2.02.08 4.48 4.48 0 004.19 3.12A9 9 0 012 19.54 12.7 12.7 0 008.29 21c7.55 0 11.68-6.26 11.68-11.68v-.53A8.18 8.18 0 0023 3z" />
                </svg>
                Tweet
              </button>


            </div>
          </div>
        </div>

        <!-- Tweet List -->
        <div v-if="tweets.length" class="space-y-6">
          <div v-for="tweet in tweets" :key="tweet.id"
            class="flex gap-4 p-6 rounded-3xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition tweet-hover">
            <div
              class="flex items-center justify-center w-12 h-12 rounded-full bg-pink-500 text-white font-bold text-lg">
              {{ tweet.user.firstname.charAt(0) }}
            </div>
            <div class="flex-1">
              <div class="flex justify-between items-center">
                <div>
                  <p class="font-semibold text-gray-800">{{ tweet.user.firstname }}</p>
                  <p class="text-sm text-gray-400">{{ dayjs(tweet.created_at).fromNow() }}</p>
                </div>
              </div>
              <p class="mt-3 text-gray-700 leading-relaxed" v-html="formatHashtags(tweet.content)"></p>

              <!-- Tweet Actions (Comment, Repost, Like, Share) -->
              <div class="flex justify-between items-center mt-4 text-gray-500 text-sm">
                <div class="flex items-center gap-1 hover:text-pink-500 cursor-pointer transition">
                  <Heart :class="[
                    'w-4 h-4 cursor-pointer transition',
                    tweet.is_liked ? 'text-pink-500 fill-pink-500' : 'text-gray-500'
                  ]" @click="toggleLike(tweet)" />
                  <span>{{ tweet.likes_count }}</span>

                </div>
                <div class="flex items-center gap-1 hover:text-blue-500 cursor-pointer transition">
                  <MessageCircle class="w-4 h-4" />
                  <span>196</span>
                </div>
                <div class="flex items-center gap-1 hover:text-green-500 cursor-pointer transition">
                  <Repeat2 class="w-4 h-4" />
                  <span>594</span>
                </div>

                <div class="flex items-center gap-1 hover:text-gray-600 cursor-pointer transition">
                  <Share2 class="w-4 h-4" />
                </div>
              </div>

            </div>
          </div>
        </div>

        <p v-else class="text-center text-gray-500 mt-6">No tweets yet 😢</p>
      </div>
    </main>

    <!-- RIGHT SIDEBAR -->
    <aside class="w-1/4 bg-white p-6 border-l border-gray-200 space-y-6">
      <!-- Search Bar -->
      <div class="sticky top-0 bg-white pb-2 z-10">
        <div class="relative">
          <!-- Search Icon -->
          <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <!-- Input Field -->
          <input type="text" placeholder="Search MiniTweet" class="w-full pl-11 pr-4 py-2.5 bg-gray-100 text-sm text-gray-700 rounded-full 
             focus:outline-none focus:ring-2 focus:ring-pink-400 focus:bg-white transition-all duration-200
             placeholder-gray-400" />
        </div>
      </div>

      <!-- What's Happening -->
      <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200">
        <h3 class="text-lg font-bold mb-3">What's happening</h3>
        <ul class="space-y-3">
          <li v-for="topic in trendingTopics" :key="topic.title">
            <p class="text-sm font-semibold text-gray-800">{{ topic.title }}</p>
            <p class="text-xs text-gray-500">{{ topic.tweets }} Tweets</p>
          </li>
        </ul>
        <p class="text-sm text-blue-500 hover:text-blue-600 font-medium mt-2 cursor-pointer" @click="showMore()">
          Show more
        </p>
      </div>

      <!-- Who to follow -->
      <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200">
        <h3 class="text-lg font-bold mb-3">Who to follow</h3>

        <!-- Follow List -->
        <div v-for="user in whoToFollow" :key="user.name"
          class="flex items-center justify-between mb-3 hover:bg-gray-100 rounded-xl p-2 transition-colors duration-150">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gray-300"></div>
            <div>
              <p class="text-sm font-semibold">{{ user.name }}</p>
              <p class="text-xs text-gray-500">@{{ user.username }}</p>
            </div>
          </div>
          <button class="text-sm font-medium bg-black text-white px-4 py-1 rounded-full hover:bg-gray-800 transition">
            Follow
          </button>
        </div>

        <!-- Show More -->
        <p class="text-sm text-blue-500 hover:text-blue-600 font-medium mt-2 ml-2 cursor-pointer" @click="showMore()">
          Show more
        </p>
      </div>
    </aside>

  </div>
</template>
