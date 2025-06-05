<template>
  <Head title="Profile" />
  <AppLayout class="bg-[#F6F6F6]">
    <!-- Avatar Upload Modal -->
    <Dialog :open="isAvatarModalOpen" @update:open="val => isAvatarModalOpen = val">
      <DialogContent class="sm:max-w-md">
        <DialogHeader>
          <DialogTitle class="text-[#AE7A42]">Ubah Foto Profil</DialogTitle>
          <DialogDescription>
            Unggah foto baru untuk profil Anda
          </DialogDescription>
        </DialogHeader>
        <div class="flex flex-col items-center gap-4 py-4">
          <!-- Avatar Preview -->
          <div class="relative">
            <Avatar class="w-32 h-32 border-4 border-white shadow-lg">
              <AvatarImage :src="avatarPreview || user.avatar" alt="Profile Preview" />
              <AvatarFallback>{{ userInitials }}</AvatarFallback>
            </Avatar>
            <label 
              for="avatar-upload"
              class="absolute bottom-0 right-0 bg-[#AE7A42] text-white p-2 rounded-full cursor-pointer hover:bg-[#95683C] transition-colors"
            >
              <Icon name="camera" class="w-4 h-4" />
              <input 
                id="avatar-upload"
                type="file" 
                ref="fileInput" 
                @change="handleFileChange"
                accept="image/*"
                class="hidden"
              >
            </label>
          </div>
          
          <!-- Upload Instructions -->
          <div class="text-center text-sm text-muted-foreground">
            <p>Format: JPG, PNG (maks. 16MB)</p>
            <p v-if="selectedFile" class="text-[#AE7A42]">{{ selectedFile.name }}</p>
          </div>
        </div>
        <DialogFooter>
          <Button variant="outline" @click="isAvatarModalOpen = false">Batal</Button>
          <Button 
            type="button" 
            @click="uploadAvatar"
            :disabled="!selectedFile || isUploading"
            class="bg-[#AE7A42] hover:bg-[#95683C] text-white"
          >
            <span v-if="isUploading" class="flex items-center gap-2">
              <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Mengunggah...
            </span>
            <span v-else>Simpan Perubahan</span>
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

    <!-- Background Upload Modal -->
    <Dialog :open="isBgModalOpen" @update:open="val => isBgModalOpen = val">
      <DialogContent class="sm:max-w-md">
        <DialogHeader>
          <DialogTitle class="text-[#AE7A42]">Ubah Background Profil</DialogTitle>
          <DialogDescription>
            Unggah background baru untuk profil Anda
          </DialogDescription>
        </DialogHeader>
        <div class="flex flex-col items-center gap-4 py-4">
          <!-- Background Preview -->
          <div class="relative w-full h-40 rounded-lg overflow-hidden border-2 border-[#AE7A42]">
            <img 
              :src="bgPreview || user.background || '/images/design.jpg'" 
              alt="Background Preview" 
              class="w-full h-full object-cover"
            >
            <label 
              for="bg-upload"
              class="absolute bottom-2 right-2 bg-[#AE7A42] text-white px-3 py-1 rounded-md cursor-pointer hover:bg-[#95683C] transition-colors text-sm"
            >
              <Icon name="image" class="w-4 h-4 mr-1 inline" />
              Pilih Gambar
              <input 
                id="bg-upload"
                type="file" 
                ref="bgFileInput" 
                @change="handleBgFileChange"
                accept="image/*"
                class="hidden"
              >
            </label>
          </div>
          
          <!-- Upload Instructions -->
          <div class="text-center text-sm text-muted-foreground">
            <p>Format: JPG, PNG (maks. 16MB)</p>
            <p v-if="selectedBgFile" class="text-[#AE7A42]">{{ selectedBgFile.name }}</p>
          </div>
        </div>
        <DialogFooter>
          <Button variant="outline" @click="isBgModalOpen = false">Batal</Button>
          <Button 
            type="button" 
            @click="uploadBackground"
            :disabled="!selectedBgFile || isBgUploading"
            class="bg-[#AE7A42] hover:bg-[#95683C] text-white"
          >
            <span v-if="isBgUploading" class="flex items-center gap-2">
              <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Mengunggah...
            </span>
            <span v-else>Simpan Perubahan</span>
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>

        <!-- Edit Profile Modal -->
    <Dialog :open="isEditModalOpen" @update:open="val => isEditModalOpen = val">
      <DialogContent class="sm:max-w-md">
        <DialogHeader>
          <DialogTitle class="text-[#AE7A42]">Edit Profil</DialogTitle>
        </DialogHeader>
        <form @submit.prevent="updateProfile">
          <div class="flex flex-col gap-4 py-4">
            <div>
              <label class="block mb-1 text-[#AE7A42] font-semibold">Nama</label>
              <input v-model="editForm.name" type="text" class="w-full border rounded px-3 py-2" required />
            </div>
            <div>
              <label class="block mb-1 text-[#AE7A42] font-semibold">Kota Asal</label>
              <input v-model="editForm.origin_city" type="text" class="w-full border rounded px-3 py-2" required />
            </div>
            <div>
              <label class="block mb-1 text-[#AE7A42] font-semibold">Negara</label>
              <input v-model="editForm.country" type="text" class="w-full border rounded px-3 py-2" required />
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" @click="isEditModalOpen = false" type="button">Batal</Button>
            <Button type="submit" class="bg-[#AE7A42] hover:bg-[#95683C] text-white">Simpan</Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>

    <!-- Edit About Modal -->
    <Dialog :open="isAboutModalOpen" @update:open="val => isAboutModalOpen = val">
      <DialogContent class="sm:max-w-md">
        <DialogHeader>
          <DialogTitle class="text-[#AE7A42]">Edit About</DialogTitle>
        </DialogHeader>
        <form @submit.prevent="updateAbout">
          <div class="flex flex-col gap-4 py-4">
            <textarea v-model="aboutForm.description" rows="4" class="w-full border rounded px-3 py-2" required />
          </div>
          <DialogFooter>
            <Button variant="outline" @click="isAboutModalOpen = false" type="button">Batal</Button>
            <Button type="submit" class="bg-[#AE7A42] hover:bg-[#95683C] text-white">Simpan</Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>

    <!-- Edit Specialty Modal -->
    <Dialog :open="isSpecialtyModalOpen" @update:open="val => isSpecialtyModalOpen = val">
      <DialogContent class="sm:max-w-md">
        <DialogHeader>
          <DialogTitle class="text-[#AE7A42]">Edit Specialty</DialogTitle>
        </DialogHeader>
        <form @submit.prevent="updateSpecialty">
          <div class="flex flex-col gap-4 py-4">
            <input v-model="specialtyForm.specialty" type="text" class="w-full border rounded px-3 py-2" required />
          </div>
          <DialogFooter>
            <Button variant="outline" @click="isSpecialtyModalOpen = false" type="button">Batal</Button>
            <Button type="submit" class="bg-[#AE7A42] hover:bg-[#95683C] text-white">Simpan</Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>

    <!-- Main Content -->
    <div class="min-h-screen flex flex-col bg-[#F6F6F6]">
      <div class="flex flex-1 flex-col lg:flex-row gap-6 px-4 sm:px-8 py-6 max-w-7xl w-full mx-auto">
        <!-- Main Content -->
        <div class="flex-1 flex flex-col gap-6 order-1 lg:order-1">
          <!-- Profile Card -->
          <Card class="relative overflow-hidden">
            <!-- Background Image Section -->
            <div 
              class="h-80 w-full bg-cover bg-center relative"
              :style="{ 
                backgroundImage: `url(${user.background || '/images/design.jpg'})`,
                backgroundPosition: 'center center'
              }"
            >
              <Button 
                class="absolute top-4 right-4 bg-[#AE7A42] hover:bg-[#95683C] text-white opacity-0 hover:opacity-100 transition-opacity"
                size="sm"
                @click="openBgModal"
              >
                <Icon name="camera" class="mr-1" />
                Ubah
              </Button>
            </div>
            <!-- Content Section with White Background -->
            <div class="bg-white px-6 pb-6 rounded-b-xl">
              <div class="flex flex-col sm:flex-row gap-4 -mt-12">
                <!-- Avatar Positioned to Left -->
                <div class="relative flex-shrink-0">
                  <div class="group relative w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg cursor-pointer">
                    <Avatar class="w-full h-full" @click="openAvatarModal">
                      <AvatarImage :src="user.avatar" alt="Profile" />
                      <AvatarFallback>{{ userInitials }}</AvatarFallback>
                    </Avatar>
                    <div 
                      class="absolute inset-0 bg-[#AE7A42]/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                      @click="openAvatarModal"
                    >
                      <Icon name="camera" class="text-white w-6 h-6" />
                    </div>
                  </div>
                </div>
                <!-- User Info on Right Side -->
                <div class="flex-1 lg:ml-2">
                  <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2">
                    <div>
                      <h2 class="text-2xl sm:text-3xl font-bold text-[#714C25] sm:mt-12 lg:mt-12">{{ user.name }}</h2>
                      <div class="font-semibold flex items-center gap-2" style="color: #714C25">
                        {{ user.origin_city }}, {{ user.country }} <span class="text-[15px] font-normal">{{ user.role }}</span>
                      </div>
                      <div class="text-[14px] mt-1" style="color: #714C25">Bergabung sejak {{ user.joined }}</div>
                      <!-- Tambahkan specialty di bawah sini -->
                      <div v-if="user.role === 'designer' || user.role === 'contractor'" class="flex items-center gap-2 mt-1">
                        <span class="font-semibold text-[#714C25]">Specialty:</span>
                        <span>{{ specialty }}</span>
                        <Button v-if="canEditAbout" size="sm" class="bg-white text-[#AE7A42] hover:bg-[#F6F6F6]" @click="openSpecialtyModal">
                          <PencilLine class="w-10 h-10" />
                        </Button>
                      </div>
                    </div>
                    <Button 
                      class="bg-[#AE7A42] hover:bg-[#95683C] text-white w-30 sm:w-auto mt-2 sm:mt-0"
                      size="sm"
                      @click="openEditModal"
                    >
                      Edit
                    </Button>
                  </div>
                </div>
              </div>
            </div>
          </Card>

          <!-- My Request Card -->
          <Link href="/myrequest" class="block">
            <Card class="bg-[#B8864B] text-white py-6 cursor-pointer hover:bg-[#a1743d] transition">
              <CardHeader class="flex flex-row items-center justify-between">
                <div>
                  <CardTitle style="color: white" class="text-[24px]">My Request</CardTitle>
                  <div class="text-[18px] font-normal text-white mt-1">Lihat Request Anda</div>
                </div>
                <span class="text-3xl font-bold">&gt;</span>
              </CardHeader>
            </Card>
          </Link>

          <Link href="/mypurchaseddesign" class="block">
            <Card class="bg-white text-[#AE7A42] py-6 cursor-pointer hover:bg-[#EBEBEB] transition mb-2">
              <CardHeader class="flex flex-row items-center justify-between">
                <div>
                  <CardTitle class="text-[24px]">My Purchased Design</CardTitle>
                  <div class="text-[18px] font-normal mt-1">Lihat Design Anda yang sudah Anda beli</div>
                </div>
                <span class="text-3xl font-bold">&gt;</span>
              </CardHeader>
            </Card>
          </Link>

          <!-- About & Portfolio (for designer/contractor) -->
          <template v-if="user.role === 'designer' || user.role === 'contractor'">
  <!-- About Section -->
  <div class="bg-[#FAAE5C] text-white rounded-3xl p-6 shadow-sm">
  <div class="flex justify-between items-center mb-4">
    <h3 class="text-2xl font-bold">About</h3>
    <Button v-if="canEditAbout" size="sm" class="bg-white text-[#AE7A42] hover:bg-[#F6F6F6]" @click="openAboutModal">
      Edit
    </Button>
  </div>
  <p class="mb-4">{{ description }}</p>
</div>

  <!-- Portfolio Card -->
<div class="rounded-3xl p-6 mb-4 shadow-sm" style="background-color: #AE7A42;">
  <div class="font-semibold text-2xl mb-4 text-white">Portfolio</div>
  <div v-if="portfolio && portfolio.url" class="flex flex-col gap-4">
    <template v-if="/\.(jpg|jpeg|png|gif)$/i.test(portfolio.url)">
      <img :src="portfolio.url" alt="Portfolio" class="max-w-xs rounded shadow mx-auto" />
    </template>
    <template v-else-if="/\.pdf$/i.test(portfolio.url)">
      <iframe
        :src="portfolio.url"
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
    <div>Created: {{ createdAt ? new Date(createdAt).toLocaleString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      }): '-' }}</div>
    <div>Updated: {{ updatedAt ? new Date(updatedAt).toLocaleString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      }) : '-' }}</div>
  </div>
</div>
</template>
        </div>
        
        <!-- Sidebar -->
        <div class="w-full lg:w-80 flex flex-col gap-6 order-2 lg:order-2">
          <Card class="py-6">
            <CardHeader>
              <CardTitle style="color: #714C25">Rekomendasi Untukmu</CardTitle>
            </CardHeader>
            <CardContent>
              <div v-for="designer in designers" :key="designer.id"
                class="flex items-center gap-3 mb-4 cursor-pointer hover:bg-gray-100 rounded px-2 py-1 transition"
                @click="goToDesigner(designer.id)">
                <Avatar class="w-12 h-12">
                  <AvatarImage :src="designer.avatar_url" alt="Designer" />
                  <AvatarFallback>{{ designerInitials(designer.name) }}</AvatarFallback>
                </Avatar>
                <div>
                  <div class="font-semibold" style="color: #714C25">{{ designer.name }}</div>
                  <div class="text-xs text-muted-foreground">Designer</div>
                </div>
              </div>
            </CardContent>
          </Card>
          <Button class="fixed bottom-8 right-8 shadow-lg rounded-full p-4 bg-[#AE7A42] hover:bg-[#95683C] text-white z-10" size="icon">
            <Icon name="chat" />
          </Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar';
import { 
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import Icon from '@/components/Icon.vue';
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';
import { usePage, Head, router as inertiaRouter } from '@inertiajs/vue3';
import { PencilLine } from 'lucide-vue-next';

// Modal state
const isAvatarModalOpen = ref(false);
const isUploading = ref(false);
const selectedFile = ref<File | null>(null);
const avatarPreview = ref<string | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);

function openAvatarModal() {
  isAvatarModalOpen.value = true;
  selectedFile.value = null;
  avatarPreview.value = null;
}

function handleFileChange(e: Event) {
  const files = (e.target as HTMLInputElement).files;
  if (files && files[0]) {
    selectedFile.value = files[0];
    avatarPreview.value = URL.createObjectURL(files[0]);
  }
}

async function uploadAvatar() {
  if (!selectedFile.value) return;
  isUploading.value = true;
  const formData = new FormData();
  formData.append('avatar', selectedFile.value);
  try {
    const res = await axios.post('/profile', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    user.value.avatar = res.data.avatar_url;
    isAvatarModalOpen.value = false;
    selectedFile.value = null;
    avatarPreview.value = null;
    alert('Foto profil berhasil diubah!');
  } catch (e) {
    alert('Gagal mengubah foto profil');
  } finally {
    isUploading.value = false;
  }
}

const isBgModalOpen = ref(false);
const isBgUploading = ref(false);
const selectedBgFile = ref<File | null>(null);
const bgPreview = ref<string | null>(null);
const bgFileInput = ref<HTMLInputElement | null>(null);

function openBgModal() {
  isBgModalOpen.value = true;
  selectedBgFile.value = null;
  bgPreview.value = null;
}

function handleBgFileChange(e: Event) {
  const files = (e.target as HTMLInputElement).files;
  if (files && files[0]) {
    selectedBgFile.value = files[0];
    bgPreview.value = URL.createObjectURL(files[0]);
  }
}

async function uploadBackground() {
  if (!selectedBgFile.value) return;
  isBgUploading.value = true;
  const formData = new FormData();
  formData.append('background', selectedBgFile.value);
  try {
    const res = await axios.post('/profile/background', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    user.value.background = res.data.background_url;
    isBgModalOpen.value = false;
    selectedBgFile.value = null;
    bgPreview.value = null;
    alert('Background berhasil diubah!');
  } catch (e) {
    alert('Gagal mengubah background');
  } finally {
    isBgUploading.value = false;
  }
}

const isEditModalOpen = ref(false);
const isAboutModalOpen = ref(false);
const isSpecialtyModalOpen = ref(false);
const designers = ref<any[]>([]);

// User data (from Inertia)
const page = usePage();
const designerId = page.props.designerId;
const user = ref({
  id: page.props.auth.user.id,
  name: page.props.auth.user.name,
  origin_city: page.props.auth.user.origin_city || '',
  country: page.props.auth.user.country || '',
  avatar: page.props.auth.user.avatar
    ? (page.props.auth.user.avatar.startsWith('http')
      ? page.props.auth.user.avatar
      : `/storage/${page.props.auth.user.avatar}`)
    : '/img/profile.jpg',
  background: page.props.auth.user.background
    ? (page.props.auth.user.background.startsWith('http')
      ? page.props.auth.user.background
      : `/storage/${page.props.auth.user.background}`)
    : null,
  joined: page.props.auth.user.created_at
    ? new Date(page.props.auth.user.created_at).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      })
    : 'N/A',
  role: page.props.auth.user.role || 'User',
});

// Data dari API designer
const designerData = ref<{ specialty?: string; description?: string; portfolio?: any } | null>(null);

// Helper computed agar selalu ambil dari designerData jika ada
const specialty = computed(() => designerData.value?.specialty ?? '-');
const description = computed(() => designerData.value?.description ?? '-');
const portfolio = computed(() => designerData.value?.portfolio ?? null);
const createdAt = computed(() => designerData.value?.created_at ?? null);
const updatedAt = computed(() => designerData.value?.updated_at ?? null);

const userInitials = computed(() => user.value.name.split(' ').map(n => n[0]).join(''));
const canEditAbout = computed(() => user.value.role === 'designer' || user.value.role === 'contractor');

async function fetchRekomendasiDesigners() {
  try {
    const resRekom = await axios.get('/api/designers');
    designers.value = resRekom.data.data
      .filter((d: any) => d.id !== user.value.id)
      .slice(0, 5);
  } catch (e) {
    designers.value = [];
  }
}

async function fetchDesignerData() {
  let endpoint = '';
  if (user.value.role === 'designer') {
    endpoint = `/api/designers/${user.value.id}`;
  } else if (user.value.role === 'contractor') {
    endpoint = `/api/contractors/${user.value.id}`;
  }

  if (endpoint) {
    try {
      const res = await axios.get(endpoint);
      // Normalisasi agar field sama
      const data = res.data.data;
      designerData.value = {
        specialty: data.specialty ?? '-',
        description: data.description ?? '-',
        portfolio: data.portfolio ?? null,
        created_at: data.created_at ?? null,
        updated_at: data.updated_at ?? null,
      };
      // Rekomendasi designer tetap sama
      const resRekom = await axios.get('/api/designers');
      designers.value = resRekom.data.data
        .filter((d: any) => d.id !== user.value.id)
        .slice(0, 5);
    } catch (e) {
      designerData.value = null;
    }
  }
}

// Edit Profile Modal
const editForm = ref({
  name: user.value.name,
  origin_city: user.value.origin_city,
  country: user.value.country,
});
function openEditModal() {
  editForm.value = {
    name: user.value.name,
    origin_city: user.value.origin_city,
    country: user.value.country,
  };
  isEditModalOpen.value = true;
}
async function updateProfile() {
  try {
    const res = await axios.post('/profile/update', editForm.value);
    user.value.name = res.data.name;
    user.value.origin_city = res.data.origin_city;
    user.value.country = res.data.country;
    isEditModalOpen.value = false;
    alert('Profil berhasil diperbarui!');
  } catch (e) {
    alert('Gagal memperbarui profil');
  }
}

// Edit About Modal
const aboutForm = ref({
  description: '',
});
function openAboutModal() {
  aboutForm.value.description = designerData.value?.description || '';
  isAboutModalOpen.value = true;
}
async function updateAbout() {
  try {
    const res = await axios.post('/profile/update-about', aboutForm.value);
    // Update designerData agar tampilan langsung berubah
    if (designerData.value) designerData.value.description = res.data.description;
    isAboutModalOpen.value = false;
    alert('About berhasil diperbarui!');
  } catch (e) {
    alert('Gagal memperbarui about');
  }
}

// Edit Specialty Modal
const specialtyForm = ref({
  specialty: '',
});
function openSpecialtyModal() {
  specialtyForm.value.specialty = designerData.value?.specialty || '';
  isSpecialtyModalOpen.value = true;
}
async function updateSpecialty() {
  try {
    const res = await axios.post('/profile/update-specialty', specialtyForm.value);
    if (designerData.value) designerData.value.specialty = res.data.specialty;
    isSpecialtyModalOpen.value = false;
    alert('Specialty berhasil diperbarui!');
  } catch (e) {
    alert('Gagal memperbarui specialty');
  }
}

function designerInitials(name: string) {
  return name.split(' ').map(n => n[0]).join('');
}

function goToDesigner(id: number) {
  inertiaRouter.visit(`/designers/${id}`);
}

onMounted(() => {
  fetchDesignerData();
  fetchRekomendasiDesigners();
});
watch(() => user.value.role, () => {
  fetchDesignerData();
  fetchRekomendasiDesigners();
});
</script>

<style scoped>
/* Custom hover effect for avatar */
.group:hover .absolute {
  background-color: rgba(174, 122, 66, 0.8) !important;
}

/* Custom modal styling */
:deep(.dialog-overlay) {
  background-color: rgba(174, 122, 66, 0.2);
}

:deep(.dialog-content) {
  border: 1px solid #AE7A42;
}

/* Ensure background covers the card header */
.card-header {
  background-size: cover;
  background-position: center top;
}
</style>