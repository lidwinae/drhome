<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Design', href: '/design' },
];

const designs = ref<Array<any>>([]);
const isLoading = ref(true);
const error = ref<string | null>(null);

const search = ref('');
const country = ref('');
const specialty = ref('');
const showSortDropdown = ref(false);
const sort = ref('az'); // 'az' = A-Z, 'za' = Z-A

function toggleSortDropdown() {
  showSortDropdown.value = !showSortDropdown.value;
}

function setSort(value: string) {
  sort.value = value;
  showSortDropdown.value = false;
  fetchDesigns();
}

async function fetchDesigns() {
  isLoading.value = true;
  try {
    const response = await axios.get('/api/designs', {
      params: {
        search: search.value,
        country: country.value,
        specialty: specialty.value,
        sort: sort.value,
      }
    });
    designs.value = response.data || [];
    error.value = null;
  } catch (err: any) {
    error.value = err?.response?.data?.message || 'Failed to load designs';
    designs.value = [];
  } finally {
    isLoading.value = false;
  }
}

function onSearch() {
  fetchDesigns();
}

function handleImageError(event: Event) {
  const target = event.target as HTMLImageElement;
  target.src = '/images/design.jpg'; // fallback image
}

onMounted(() => {
  fetchDesigns();
});
</script>

<template>
  <Head title="Design" />

  <AppLayout :breadcrumbs="breadcrumbs" class="bg-[#F6F6F6]">
    <!-- Video with Text Overlay -->
    <div class="relative flex justify-center items-center p-8 w-full">
      <video autoplay loop muted playsinline class="w-full max-w-[1000px] rounded-[20px] aspect-[2.35/1] object-cover shadow-[0_4px_20px_rgba(0,0,0,0.1)]" poster="/images/design.jpg">
        <source src="/videos/design.mp4" type="video/mp4">
        <img src="/images/design.jpg" alt="Background" class="w-full max-w-[1000px] rounded-[20px] aspect-[2.35/1] object-cover shadow-[0_4px_20px_rgba(0,0,0,0.1)]">
      </video>

      <div class="absolute top-[40%] left-1/3 -translate-x-1/2 -translate-y-1/2 text-center w-[80%] max-w-[500px] px-8 z-[2] font-['Archivo']">
        <h1 class="text-[#183D55] font-bold mb-1 text-shadow-[0_2px_4px_rgba(255,255,255,0.5)] leading-[1.2] text-[clamp(0.9rem,3.1vw,2.4rem)]">Temukan design terbaik</h1>
        <p class="text-[#183D55] font-bold mb-1 text-shadow-[0_2px_4px_rgba(255,255,255,0.5)] leading-[1.2] text-[clamp(0.9rem,3.1vw,2.4rem)]">dengan Dr.Home</p>
      </div>
    </div>

    <!-- Search Bar -->
<div class="flex justify-center px-8 pb-8 w-full">
  <div class="flex items-center bg-white rounded-[20px] h-12 px-4 shadow-sm w-full max-w-[1000px] relative">
    <button
      class="bg-transparent border-none cursor-pointer flex items-center p-2 mr-2"
      @click="toggleSortDropdown"
      aria-label="Sort"
    >
      <img src="/images/filter.svg" alt="Filter" class="w-[30px] h-[30px] block" />
    </button>
    <!-- Dropdown -->
    <div
      v-if="showSortDropdown"
      class="absolute left-0 top-12 bg-white border rounded shadow-md z-10 w-50"
    >
      <button
        class="block w-full text-left px-4 py-2 hover:bg-gray-100"
        @click="setSort('az')"
      >Filter nama dari A-Z</button>
      <button
        class="block w-full text-left px-4 py-2 hover:bg-gray-100"
        @click="setSort('za')"
      >Filter nama dari Z-A</button>
    </div>
    <input
      v-model="search"
      @keyup.enter="onSearch"
      type="text"
      placeholder="Search your favourite contractor..."
      class="flex-1 border-none outline-none py-3 px-4 text-base text-gray-700 bg-transparent"
    >
    <button @click="onSearch" class="bg-transparent border-none cursor-pointer flex items-center p-2 ml-2">
      <img src="/images/search.svg" alt="Search" class="w-[30px] h-[30px] block" />
    </button>
  </div>
</div>

    <!-- Designs Grid Section -->
    <div class="w-full max-w-[1100px] mx-auto px-8 pb-12">
      <h2 class="text-center mb-8 font-['Archivo'] font-medium text-[#AE7A42] text-[1.8rem]">Designs</h2>

      <div v-if="isLoading" class="flex flex-col items-center justify-center py-8 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#AE7A42] mb-4"></div>
        <p class="text-black mt-2">Loading designs...</p>
      </div>

      <div v-else-if="error" class="text-center py-8 text-base">
        <p>{{ error }}</p>
      </div>

      <div v-else-if="designs.length === 0" class="text-center py-8 font-light text-sm text-[#AE7A42]">
        <p>No designs available</p>
      </div>

      <div v-else class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <Link
          v-for="design in designs"
          :key="design.id"
          :href="route('designdetail', { id: design.id })"
          class="bg-white rounded-[20px] overflow-hidden shadow-[0_4px_12px_rgba(0,0,0,0.1)] transition-transform duration-300 ease-in-out hover:-translate-y-[5px]"
        >
          <img
            :src="design.photo_url || '/images/design.jpg'"
            :alt="design.name"
            class="w-full h-[260px] object-cover rounded-t-[20px]"
            @error="handleImageError"
          >
          <div class="p-4 text-center">
            <!-- Design Name with hover effect -->
            <h3 class="m-0 mb-3 text-[24px] font-medium text-gray-800 transition-colors duration-200 hover:text-[#AE7A42]">
              {{ design.name }}
            </h3>

            <!-- Country and Specialty with icons and backgrounds -->
            <div class="flex flex-wrap justify-center gap-2 mb-3">
              <!-- Country -->
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FAE5CC] text-[#714C25]">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ design.country }}
              </span>

              <!-- Specialty -->
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                {{ design.specialty }}
              </span>
            </div>
          </div>
        </Link>
      </div>
    </div>
  </AppLayout>
</template>