<template>
  <div>
    <div class="mt-4 mb-2">
      <label
        v-if="csv === null"
        class="text-sm font-medium text-gray-900 block mb-2"
        :class="{ 'text-red-800': error }"
        for="upload-csv"
        >Upload CSV
      </label>
      <label
        v-else
        class="text-sm font-medium text-gray-900 block mb-2"
        :class="{ 'text-red-800': error }"
        >Scraping... <span class="text-lg font-bold">{{ progress }}%</span>
      </label>
      <input
        v-if="csv === null"
        class="
          block
          w-full
          cursor-pointer
          bg-gray-50
          border border-gray-300
          text-gray-900
          focus:outline-none focus:border-transparent
          text-sm
          rounded-lg
        "
        id="upload-csv"
        type="file"
        required
        accept=".csv"
        @change="upload"
      />
      <div v-else class="w-full h-6 bg-gray-200 rounded-full dark:bg-gray-700">
        <div
          class="h-6 bg-gray-600 rounded-full dark:bg-gray-300"
          :style="`width: ${progress}%`"
        ></div>
      </div>
    </div>
    <div class="mb-2">
      <p class="text-sm font-medium text-red-800" v-if="error">{{ error }}</p>
    </div>
    <div v-if="csv === null">
      <p class="text-sm font-medium mb-4">* The maximum size is 2MB.</p>
      <div>
        <a
          href="/results"
          class="
            inline-flex
            items-center
            px-4
            py-2
            bg-gray-800
            border border-transparent
            rounded-md
            font-semibold
            text-xs text-white
            uppercase
            tracking-widest
            hover:bg-gray-700
            active:bg-gray-900
            focus:outline-none focus:border-gray-900 focus:ring
            ring-gray-300
            disabled:opacity-25
            transition
            ease-in-out
            duration-150'
          "
          ><svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4 mr-2"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M11 17l-5-5m0 0l5-5m-5 5h12"
            /></svg
          >Back</a
        >
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      checkProgressInterval: null,
      csv: null,
      error: null,
    };
  },
  computed: {
    progress() {
      if (this.csv === null) {
        return 0;
      }
      return (
        Math.round(this.csv.total_scraped_keywords / this.csv.total_keywords) *
        100
      );
    },
  },
  methods: {
    upload(e) {
      let formData = new FormData();
      formData.append("csv", e.target.files[0]);
      axios
        .post("/results", formData)
        .then((res) => {
          if (res.status === 201) {
            this.csv = res.data.csv;
            this.checkProgressIntervally(2);
          }
        })
        .catch((e) => {
          if (e.response.status === 422) {
            if (e.response.data.errors.hasOwnProperty("csv")) {
              this.error = e.response.data.errors.csv[0];
              return;
            }

            this.error = e.response.data.errors.keywords[0];
          }
        });
    },
    checkProgressIntervally(seconds) {
      this.checkProgressInterval = setInterval(
        this.checkProgress,
        seconds * 1000
      );
    },
    checkProgress() {
      axios
        .get(`/csvs/${this.csv.id}`)
        .then((res) => {
          this.csv = res.data.csv;
          if (this.csv.is_scraped) {
            clearInterval(this.checkProgressInterval);
            window.location.href = "/results";
          }
        })
        .catch((e) => console.log(e));
    },
  },
  destroyed() {
    clearInterval(this.checkProgressInterval);
  },
};
</script>

<style>
</style>