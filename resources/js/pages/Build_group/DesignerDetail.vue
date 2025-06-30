<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar';
import Icon from '@/components/Icon.vue';
import { ref, onMounted } from 'vue';
import { usePage, router as inertiaRouter, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const designerId = page.props.designerId;
const authUserId = page.props.auth?.user?.id ?? null;

const isLoading = ref(true);
const error = ref<string | null>(null);
const designer = ref<any>(null);
const designers = ref<any[]>([]);

onMounted(async () => {
  if (!designerId) {
    error.value = 'ID designer tidak ditemukan di props.';
    isLoading.value = false;
    return;
  }
  isLoading.value = true;
  try {
    // Ambil data designer detail
    const response = await axios.get(`/api/designers/${designerId}`);
    designer.value = response.data.data;
    error.value = null;

    // Ambil data rekomendasi designer (kecuali yang sedang dibuka)
    const resRekom = await axios.get('/api/designers');
    designers.value = resRekom.data.data.filter((d: any) => d.id !== authUserId);
  } catch (err: any) {
    error.value = err?.response?.data?.message || 'Gagal memuat data designer';
    designer.value = null;
  } finally {
    isLoading.value = false;
  }
});

function handleImageError(event: Event) {
  const target = event.target as HTMLImageElement;
  target.src = '/images/default_Avatar.png';
}

function designerInitials(name: string) {
  return name ? name.split(' ').map(n => n[0]).join('').toUpperCase() : '';
}

function goToDesigner(id: number) {
  inertiaRouter.visit(`/designers/${id}`);
}

const isOwnProfile = computed(() => {
  return authUserId && designer.value && authUserId === designer.value.id;
});
</script>

<template>
  <Head title="Detail Designer" />
  <AppLayout class="bg-[#F6F6F6]">
    <div class="min-h-screen flex flex-col bg-[#F6F6F6]">
      <div class="max-w-6xl w-full mx-auto py-8 px-4">
        <div v-if="isLoading" class="flex flex-col items-center justify-center py-16">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-[#AE7A42] mb-4"></div>
          <p class="text-black mt-2">Loading designer...</p>
        </div>
        <div v-else-if="error" class="text-center py-16 text-red-500">
          <p>{{ error }}</p>
        </div>
        <div v-else-if="designer" class="flex flex-col lg:flex-row gap-8">
          <!-- Main Content -->
          <div class="flex-1 flex flex-col gap-8 order-2 lg:order-1">
            <!-- Profile Card -->
            <Card class="relative overflow-hidden">
              <!-- Background Image Section -->
              <div 
                class="h-80 w-full bg-cover bg-center relative"
                :style="{ 
                  backgroundImage: `url(${designer.background_url || '/images/design.jpg'})`,
                  backgroundPosition: 'center center'
                }"
              ></div>
              <!-- Content Section with White Background -->
              <div class="bg-white px-6 pb-6 rounded-b-xl">
                <div class="flex flex-col sm:flex-row gap-4 -mt-12">
                  <!-- Avatar Positioned to Left -->
                  <div class="relative flex-shrink-0">
                    <div class="group relative w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg cursor-pointer">
                      <Avatar class="w-full h-full">
                        <AvatarImage :src="designer.avatar_url || '/images/default_Avatar.png'" alt="Profile" @error="handleImageError" />
                        <AvatarFallback>{{ designerInitials(designer.name) }}</AvatarFallback>
                      </Avatar>
                    </div>
                  </div>
                  <!-- User Info on Right Side -->
                  <div class="flex-1 sm:mt-12 lg:ml-2">
                    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2">
                      <div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-[#714C25]">{{ designer.name }}</h2>
                        <div class="font-semibold flex items-center gap-2" style="color: #714C25">
                          {{ designer.origin_city }}, {{ designer.country }} <span class="text-[15px] font-normal">{{ designer.role }}</span>
                        </div>
                        <div class="text-[14px] mt-1" style="color: #714C25">
                          Bergabung sejak {{ designer.created_at ? new Date(designer.created_at).toLocaleString('id-ID', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                          }): '-' }}
                        </div>
                      </div>
                    </div>
                    
                      <div class="flex justify-between items-center mt-4">
    <div class="flex flex-wrap gap-2">
      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FAE5CC] text-[#714C25]">
        <Icon name="flag" class="w-3 h-3 mr-1" /> {{ designer.country }}
      </span>
      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
        <Icon name="star" class="w-3 h-3 mr-1" /> {{ designer.specialty || '-' }}
      </span>
    </div>
    <template v-if="!isOwnProfile">
      <Link
        :href="`/designers/${designer.id}/request`"
        class="ml-4 px-5 py-2 rounded-full bg-[#AE7A42] text-white font-medium text-[18px] shadow hover:bg-[#95683C] transition"
        style="white-space: nowrap;"
      >
        Request
      </Link>
    </template>
    <template v-else>
      <Link
        :href="`/profile`"
        class="ml-4 px-5 py-2 rounded-full bg-[#AE7A42] text-white font-medium text-[18px] shadow hover:bg-[#95683C] transition"
        style="white-space: nowrap;"
      >
        Go to your Profile
      </Link>
    </template>
  </div>

                  </div>
                </div>
              </div>
            </Card>

            <!-- About Section -->
            <div class="bg-[#FAAE5C] text-white rounded-3xl p-6 shadow-sm">
              <h3 class="text-2xl font-bold mb-4">About</h3>
              <p class="mb-4">{{ designer.description || '-' }}</p>
            </div>

            <!-- Portfolio Card -->
            <div class="rounded-3xl p-6 mb-4 shadow-sm" style="background-color: #AE7A42;">
              <div class="font-semibold text-2xl mb-4 text-white">Portfolio</div>
              <div v-if="designer.portfolio && designer.portfolio.url" class="flex flex-col gap-4">
                <template v-if="/\.(jpg|jpeg|png|gif)$/i.test(designer.portfolio.url)">
                  <img :src="designer.portfolio.url" alt="Portfolio" class="max-w-xs rounded shadow mx-auto" />
                </template>
                <template v-else-if="/\.pdf$/i.test(designer.portfolio.url)">
                  <iframe
                    :src="designer.portfolio.url"
                    class="w-full rounded shadow"
                    style="min-height: 600px; background: white;"
                    frameborder="0"
                    allowfullscreen
                  ></iframe>
                </template>
                <template v-else>
                  <div class="text-white">File tidak dapat dipreview.</div>
                </template>
              </div>
              <div v-else class="text-white opacity-70">Tidak ada portfolio</div>
              <div class="mt-4 text-xs text-white opacity-80">
                <div>Created: {{ designer?.created_at ? new Date(designer.created_at).toLocaleString('id-ID', {
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric',
                }): '-' }}</div>
                <div>Updated: {{ designer?.updated_at ? new Date(designer.updated_at).toLocaleString('id-ID', {
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric',
                }): '-' }}</div>
              </div>
            </div>

            <!-- Sidebar (mobile order) -->
            <div class="block lg:hidden">
              <div class="w-full flex flex-col gap-6 mt-8">
                <Card class="py-6">
                  <CardHeader>
                    <CardTitle style="color: #714C25">Rekomendasi Untukmu</CardTitle>
                  </CardHeader>
                  <CardContent>
                    <div
                      v-for="d in designers.slice(0, 5)"
                      :key="d.id"
                      class="flex items-center gap-3 mb-4 cursor-pointer hover:bg-gray-100 rounded px-2 py-1 transition"
                      @click="goToDesigner(d.id)"
                    >
                      <Avatar class="w-12 h-12">
                        <AvatarImage :src="d.avatar_url || '/images/default_Avatar.png'" alt="Designer" />
                        <AvatarFallback>{{ designerInitials(d.name) }}</AvatarFallback>
                      </Avatar>
                      <div>
                        <div class="font-semibold" style="color: #714C25">{{ d.name }}</div>
                        <div class="text-xs text-muted-foreground">Designer</div>
                      </div>
                    </div>
                  </CardContent>
                </Card>
                
              </div>
            </div>
          </div>

          <!-- Sidebar (desktop order) -->
          <div class="hidden lg:flex w-full lg:w-80 flex-col gap-6 order-1 lg:order-2">
            <Card class="py-6">
              <CardHeader>
                <CardTitle style="color: #714C25">Rekomendasi Untukmu</CardTitle>
              </CardHeader>
              <CardContent>
                <div
                  v-for="d in designers.slice(0, 5)"
                  :key="d.id"
                  class="flex items-center gap-3 mb-4 cursor-pointer hover:bg-gray-100 rounded px-2 py-1 transition"
                  @click="goToDesigner(d.id)"
                >
                  <Avatar class="w-12 h-12">
                    <AvatarImage :src="d.avatar_url || '/images/default_Avatar.png'" alt="Designer" />
                    <AvatarFallback>{{ designerInitials(d.name) }}</AvatarFallback>
                  </Avatar>
                  <div>
                    <div class="font-semibold" style="color: #714C25">{{ d.name }}</div>
                    <div class="text-xs text-muted-foreground">Designer</div>
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