<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center bg-white shadow p-4">
      <h1 class="text-2xl font-extrabold text-gray-800">MiniTweet</h1>
      <div class="flex items-center gap-3">
        <div
          class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold"
          :style="{ backgroundColor: avatarColor }"
        >
          {{ userInitials }}
        </div>
        <p class="font-semibold text-gray-800">{{ userName }}</p>
        <button
          @click="logout"
          class="text-sm font-medium text-gray-700 border px-3 py-1 rounded-md hover:bg-gray-100 transition"
        >
          Logout
        </button>
      </div>
    </div>

    <!-- Feed Section -->
    <div class="flex-grow flex justify-center py-6">
      <div class="w-full max-w-xl bg-white shadow-md rounded-lg p-6">
        <h2 class="text-center text-2xl font-bold text-gray-900 mb-4">
          MiniTweet Feed
        </h2>

        <!-- Tweet Input -->
        <textarea
          v-model="tweetContent"
          class="w-full border border-gray-300 rounded-md p-3 focus:ring focus:ring-pink-200 focus:outline-none resize-none"
          placeholder="What's happening?"
          maxlength="280"
        ></textarea>
        <div class="text-sm text-gray-500 text-right mb-2">
          {{ 280 - tweetContent.length }} characters remaining
        </div>
        <button
          @click="postTweet"
          :disabled="!tweetContent.trim()"
          class="w-full bg-pink-500 text-white py-2 rounded-md font-semibold hover:bg-pink-600 transition disabled:opacity-50"
        >
          Tweet
        </button>

        <!-- Tweets Feed -->
        <div v-if="tweets.length" class="mt-6 space-y-4">
          <div
            v-for="tweet in tweets"
            :key="tweet.id"
            class="border border-gray-200 rounded-lg p-4 flex flex-col shadow-sm"
          >
            <!-- User info -->
            <div class="flex items-center mb-2">
              <div
                class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold mr-3"
                :style="{ backgroundColor: stringToColor(tweet.user.firstname) }"
              >
                {{ tweet.user.firstname.charAt(0) }}
              </div>
              <p class="font-semibold text-gray-800">
                {{ tweet.user.firstname }} {{ tweet.user.surname }}
              </p>
            </div>

            <!-- Tweet content -->
            <p
              class="mt-1 text-gray-700 break-words"
              v-html="formatHashtags(tweet.content)"
            ></p>

            <!-- Time -->
            <p class="text-xs text-gray-400 mt-1">
              {{ dayjs(tweet.created_at).fromNow() }}
            </p>

            <!-- Like button -->
            <div class="flex items-center gap-2 mt-2">
              <button
                @click="toggleLike(tweet)"
                class="flex items-center gap-1 focus:outline-none"
              >
                <span
                  :class="tweet.is_liked ? 'text-pink-500' : 'text-gray-400'"
                >
                  {{ tweet.is_liked ? '❤️' : '🤍' }}
                </span>
              </button>
              <span class="text-sm text-gray-600">
                {{ tweet.likes_count }}
              </span>
            </div>
          </div>
        </div>

        <p
          v-else
          class="text-center text-gray-500 mt-6 text-sm flex justify-center items-center"
        >
          No tweets yet 😔
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);

export default {
  data() {
    return {
      tweets: [],
      tweetContent: "",
      userName: "",
      userInitials: "",
      avatarColor: "#ec4899",
      dayjs,
    };
  },
  created() {
    this.fetchTweets();
    this.fetchUser();
  },
  methods: {
    async fetchTweets() {
      try {
        const response = await axios.get("http://127.0.0.1:8000/api/tweets");
        this.tweets = response.data;
      } catch (error) {
        console.error("Error fetching tweets:", error);
      }
    },
    async fetchUser() {
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get("http://127.0.0.1:8000/api/user", {
          headers: { Authorization: `Bearer ${token}` },
        });
        const user = response.data;
        this.userName = `${user.firstname} ${user.surname}`;
        this.userInitials =
          user.firstname.charAt(0).toUpperCase() +
          (user.surname ? user.surname.charAt(0).toUpperCase() : "");
        this.avatarColor = this.stringToColor(user.firstname);
      } catch (error) {
        console.error("Error loading user:", error);
      }
    },
    async postTweet() {
      try {
        const token = localStorage.getItem("token");
        await axios.post(
          "http://127.0.0.1:8000/api/tweets",
          { content: this.tweetContent },
          {
            headers: { Authorization: `Bearer ${token}` },
          }
        );
        this.tweetContent = "";
        await this.fetchTweets();
      } catch (error) {
        console.error("Error posting tweet:", error);
      }
    },
    async toggleLike(tweet) {
      try {
        const token = localStorage.getItem("token");
        const response = await axios.post(
          `http://127.0.0.1:8000/api/tweets/${tweet.id}/like`,
          {},
          {
            headers: { Authorization: `Bearer ${token}` },
          }
        );

        if (response.data.message === "Tweet liked") {
          tweet.is_liked = true;
          tweet.likes_count++;
        } else if (response.data.message === "Tweet unliked") {
          tweet.is_liked = false;
          tweet.likes_count--;
        }
      } catch (error) {
        console.error("Error toggling like:", error);
      }
    },
    stringToColor(str) {
      let hash = 0;
      for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
      }
      const c = (hash & 0x00ffffff).toString(16).toUpperCase();
      return "#" + "00000".substring(0, 6 - c.length) + c;
    },
    formatHashtags(text) {
      if (!text) return "";
      // Bold and color hashtags automatically
      return text.replace(
        /#(\w+)/g,
        '<strong class="font-semibold text-pink-600">#$1</strong>'
      );
    },
    logout() {
      localStorage.removeItem("token");
      window.location.href = "/";
    },
  },
};
</script>
