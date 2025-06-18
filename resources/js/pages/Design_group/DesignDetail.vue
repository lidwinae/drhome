<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar';
import Icon from '@/components/Icon.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Link, usePage, router as inertiaRouter } from '@inertiajs/vue3';
import axios from 'axios';

// Ambil id dari route params atau props
const page = usePage();
const designId = page.props.designId ?? null;
const authUserId = page.props.auth?.user?.id ?? null;

const design = ref<any>(null);
const loading = ref(true);
const error = ref<string | null>(null);
const designers = ref<any[]>([]); // Untuk rekomendasi designer

function designerInitials(name: string) {
  return name ? name.split(' ').map(n => n[0]).join('').toUpperCase() : '';
}

function handleImageError(event: Event) {
  const target = event.target as HTMLImageElement;
  target.src = '/images/default_Avatar.png';
}

function goToDesigner(id: number) {
  inertiaRouter.visit(`/designers/${id}`);
}

const isPurchased = ref(false);

onMounted(async () => {
  try {
    const id = designId ?? (typeof window !== 'undefined' ? Number(new URLSearchParams(window.location.search).get('id')) : null);
    if (!id) {
      error.value = 'Design ID not found';
      loading.value = false;
      return;
    }

    // Ambil data design detail
    const response = await axios.get(`/api/designs/${id}`);
    design.value = response.data;

    // Cek apakah sudah purchased
    if (authUserId) {
      const purchasedRes = await axios.get(`/design/${id}/is-purchased`);
      isPurchased.value = purchasedRes.data.purchased;
    }

    // Ambil data rekomendasi designer
    try {
      const resRekom = await axios.get('/api/designers');
      if (resRekom.data && Array.isArray(resRekom.data.data)) {
        designers.value = resRekom.data.data.filter((d: any) => d.id !== authUserId).slice(0, 5);
      }
    } catch (rekomError) {
      console.error('Failed to load designer recommendations:', rekomError);
    }

    loading.value = false;
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to load design details';
    loading.value = false;
  }
});
</script>

<template>
  <Head title="Design Detail" />
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
            <!-- Design Card -->
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
  <template v-if="!isPurchased">
    <Link
      :href="route('purchase.design', { id: design.id })"
      class="inline-block bg-[#AE7A42] hover:bg-[#8e6536] text-white font-bold py-3 px-6 rounded-full text-[16px] transition-colors duration-200 whitespace-nowrap"
    >
      Purchase
    </Link>
  </template>
  <template v-else>
    <div class="flex gap-3">
      <span class="inline-flex items-center bg-green-100 text-green-700 px-6 py-3 rounded-full font-bold">
        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
        Purchased
      </span>
      <a
        :href="design.file_url"
        target="_blank"
        class="inline-flex items-center bg-[#714C25] hover:bg-[#5a3d1d] text-white font-bold py-3 px-6 rounded-full text-[16px] transition-colors duration-200"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        View File
      </a>
    </div>
  </template>
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

          <!-- Sidebar - Rekomendasi Designer -->
          <div class="w-full lg:w-80 flex flex-col gap-6">
            <Card class="py-6">
              <CardHeader>
                <CardTitle style="color: #714C25">Rekomendasi Untukmu</CardTitle>
              </CardHeader>
              <CardContent>
                <div
                  v-for="designer in designers"
                  :key="designer.id"
                  class="flex items-center gap-3 mb-4 cursor-pointer hover:bg-gray-100 rounded px-2 py-1 transition"
                  @click="goToDesigner(designer.id)"
                >
                  <Avatar class="w-12 h-12">
                    <AvatarImage :src="designer.avatar_url || '/images/default_Avatar.png'" alt="Designer" @error="handleImageError" />
                    <AvatarFallback>{{ designerInitials(designer.name) }}</AvatarFallback>
                  </Avatar>
                  <div>
                    <div class="font-semibold" style="color: #714C25">{{ designer.name }}</div>
                    <div class="text-xs text-muted-foreground">{{ designer.role || 'Designer' }}</div>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
