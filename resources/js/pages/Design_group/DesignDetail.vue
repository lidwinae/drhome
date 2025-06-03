<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar';
import Icon from '@/components/Icon.vue';
import { ref, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import axios from 'axios';

// Ambil id dari route params atau props
const page = usePage();
const designId = page.props.designId ?? null;

const design = ref<any>(null);
const loading = ref(true);
const error = ref<string | null>(null);

const recommendationsData = ref([
  { name: 'Ali Rohmadanu', role: 'Contractor', imageUrl: 'https://cdn.builder.io/api/v1/image/assets/TEMP/a985a5b23772e5cb50f72e965d8d1e9b463ff3c2?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34' },
  { name: 'Ali Rohmadanu', role: 'Contractor', imageUrl: 'https://cdn.builder.io/api/v1/image/assets/TEMP/a985a5b23772e5cb50f72e965d8d1e9b463ff3c2?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34' },
  { name: 'Ali Rohmadanu', role: 'Contractor', imageUrl: 'https://cdn.builder.io/api/v1/image/assets/TEMP/a985a5b23772e5cb50f72e965d8d1e9b463ff3c2?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34' },
  { name: 'Ali Rohmadanu', role: 'Contractor', imageUrl: 'https://cdn.builder.io/api/v1/image/assets/TEMP/a985a5b23772e5cb50f72e965d8d1e9b463ff3c2?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34' },
  { name: 'Ali Rohmadanu', role: 'Contractor', imageUrl: 'https://cdn.builder.io/api/v1/image/assets/TEMP/a985a5b23772e5cb50f72e965d8d1e9b463ff3c2?placeholderIfAbsent=true&apiKey=99ac6e2e518047159e4604b0a27afb34' },
]);

function designInitials(name: string) {
  return name.split(' ').map(n => n[0]).join('');
}

function handleImageError(event: Event) {
  const target = event.target as HTMLImageElement;
  target.src = '/images/design.jpg';
}

onMounted(async () => {
  try {
    const id = designId ?? (typeof window !== 'undefined' ? Number(new URLSearchParams(window.location.search).get('id')) : null);
    if (!id) {
      error.value = 'Design ID not found';
      loading.value = false;
      return;
    }
    const response = await axios.get(`/api/designs/${id}`);
    design.value = response.data;
    loading.value = false;
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to load design details';
    loading.value = false;
  }
});
</script>

<template>
  <AppLayout class="bg-gray-50">
    <div class="min-h-screen bg-gray-50">
      <!-- Loading State -->
      <div v-if="loading" class="flex flex-col items-center justify-center min-h-screen">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#AE7A42]"></div>
        <div class="text-[#AE7A42] text-[24px] mt-4">Loading...</div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="flex items-center justify-center min-h-screen">
        <div class="text-red-500 text-xl">{{ error }}</div>
      </div>

      <!-- Content -->
      <div v-else class="container mx-auto px-4 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
          <!-- Main Content -->
          <div class="flex-1 flex flex-col gap-6">
            <!-- Profile Card -->
            <div class="bg-white rounded-3xl shadow-sm overflow-hidden">
              <!-- Cover Image -->
              <div class="relative h-64 md:h-80 lg:h-96 w-full">
                <img
                  :src="design.photo_url || '/images/design.jpg'"
                  alt="Design Cover"
                  class="w-full h-full object-cover rounded-t-3xl"
                  @error="handleImageError"
                />
              </div>

              <!-- Profile Content -->
              <div class="relative px-6 pb-6">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pt-6">
                  <div class="flex-1">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-[#AE7A42] font-archivo mb-4">
                      {{ design.name }}
                    </h2>
                    <div class="flex flex-wrap gap-2 mb-4">
                      <span class="bg-[#FAE5CC] text-[#714C25] px-3 py-1 rounded-lg text-xs">
                        {{ design.country }}
                      </span>
                      <span class="bg-[#FAE5CC] text-[#714C25] px-3 py-1 rounded-lg text-xs">
                        {{ design.specialty }}
                      </span>
                    </div>
                  </div>
                  <div class="md:self-end">
                    <Link 
                      :href="route('designer.request', { id: design.id })" 
                      class="inline-block bg-[#AE7A42] hover:bg-[#8e6536] text-white font-bold py-3 px-6 rounded-full text-[16px] transition-colors duration-200 whitespace-nowrap"
                    >
                      Request
                    </Link>
                  </div>
                </div>
              </div>
            </div>

            <!-- About Section -->
            <div class="bg-[#FAAE5C] text-white rounded-3xl p-6 shadow-sm">
              <h3 class="text-2xl font-bold mb-4">About</h3>
              <p class="mb-4">{{ design.description || '-' }}</p>
            </div>

            <!-- Preview Section (Video) -->
            <div class="bg-[#DEDEDE] text-black rounded-3xl p-6 shadow-sm mb-6">
              <h3 class="text-2xl font-bold mb-4">Preview</h3>
              <div v-if="design.preview_url">
                <video
                  :src="design.preview_url"
                  controls
                  class="w-full rounded-xl bg-black"
                  style="max-height:400px"
                >
                  Your browser does not support the video tag.
                </video>
              </div>
              <div v-else>
                <p class="mb-4">-</p>
              </div>
            </div>
          </div>

          <!-- Sidebar - Rekomendasi -->
          <div class="w-full lg:w-80 flex flex-col gap-6">
            <div class="bg-white rounded-3xl shadow-sm p-6">
              <h3 class="text-xl font-bold text-[#714C25] mb-4">Rekomendasi Untukmu</h3>
              <div class="space-y-4">
                <div 
                  v-for="designer in recommendationsData" 
                  :key="designer.name" 
                  class="flex items-center gap-3"
                >
                  <Avatar class="w-12 h-12">
                    <AvatarImage :src="designer.imageUrl" alt="Designer" />
                    <AvatarFallback>{{ designInitials(designer.name) }}</AvatarFallback>
                  </Avatar>
                  <div>
                    <div class="font-semibold text-[#714C25]">{{ designer.name }}</div>
                    <div class="text-xs text-gray-500">{{ designer.role }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>