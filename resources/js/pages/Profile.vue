<template>
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

    <!-- Main Content -->
    <div class="min-h-screen flex flex-col bg-[#F6F6F6]">
      <div class="flex flex-1 flex-col lg:flex-row gap-6 px-4 sm:px-8 py-6 max-w-7xl w-full mx-auto">
        <!-- Main Content -->
        <div class="flex-1 flex flex-col gap-6 order-1 lg:order-1">
          <!-- Profile Card -->
             <Card class="relative overflow-hidden">
    <!-- Background Image Section -->
    <div 
      class="h-48 w-full bg-cover bg-center relative"
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
      <h2 class="text-2xl sm:text-3xl font-bold text-[#714C25] lg:mt-12">{{ user.name }}</h2>
      <div class="font-semibold flex items-center gap-2" style="color: #714C25">
        {{ user.origin }} <span class="text-[15px] font-normal">{{ user.role }}</span>
      </div>
      <div class="text-[14px] mt-1" style="color: #714C25">Bergabung sejak {{ user.joined }}</div>
    </div>
    
    <Button 
      variant="destructive" 
      @click="signOut" 
      class="w-30 sm:w-auto mt-2 sm:mt-0"
      size="sm"
    >
      Sign Out
    </Button>
  </div>
</div>
      </div>
    </div>
  </Card>

          <!-- My Request Card -->
          <Card class="bg-[#B8864B] text-white py-6">
            <CardHeader>
              <CardTitle style="color: white">My Request</CardTitle>
            </CardHeader>
            <CardContent class="flex flex-col sm:flex-row items-center gap-4">
              <img :src="request.image" alt="Request" class="w-full sm:w-32 h-24 object-cover rounded-lg" />
              <div class="flex-1" style="color: white">
                <div>Nama : {{ request.name }}</div>
                <div>Type : {{ request.type }}</div>
                <div>ID : {{ request.id }}</div>
                <div>Request Date : {{ request.date }}</div>
              </div>
              <Button variant="secondary" class="bg-[#AE7A42] hover:bg-[#95683C] text-white w-full sm:w-auto mt-4 sm:mt-0" @click="cekRequest">Cek</Button>
            </CardContent>
          </Card>
        </div>
        
        <!-- Sidebar -->
        <div class="w-full lg:w-80 flex flex-col gap-6 order-2 lg:order-2">
          <Card class="py-6">
            <CardHeader>
              <CardTitle style="color: #714C25">Rekomendasi Designer</CardTitle>
            </CardHeader>
            <CardContent>
              <div v-for="designer in designers" :key="designer.id" class="flex items-center gap-3 mb-4">
                <Avatar class="w-12 h-12">
                  <AvatarImage :src="designer.avatar" alt="Designer" />
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
import { ref } from 'vue';
import axios from 'axios';
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

// Modal state
const isAvatarModalOpen = ref(false);
const isUploading = ref(false);
const selectedFile = ref<File | null>(null);
const avatarPreview = ref<string | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);

const isBgModalOpen = ref(false);
const isBgUploading = ref(false);
const selectedBgFile = ref<File | null>(null);
const bgPreview = ref<string | null>(null);
const bgFileInput = ref<HTMLInputElement | null>(null);

// User data
const page = usePage();
const user = ref({
  name: page.props.auth.user.name,
  avatar: page.props.auth.user.avatar
    ? page.props.auth.user.avatar.startsWith('http')
      ? page.props.auth.user.avatar
      : `/storage/${page.props.auth.user.avatar}`
    : '/img/profile.jpg',
  background: page.props.auth.user.background
    ? page.props.auth.user.background.startsWith('http')
      ? page.props.auth.user.background
      : `/storage/${page.props.auth.user.background}`
    : null,
  origin: `${page.props.auth.user.origin_city || 'Unknown City'}, ${page.props.auth.user.country || 'Indonesia'}`,
  joined: page.props.auth.user.created_at
    ? new Date(page.props.auth.user.created_at).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      })
    : 'N/A',
  role: page.props.auth.user.role || 'User',
});

const userInitials = user.value.name.split(' ').map(n => n[0]).join('');

// Sample data
const request = ref({
  name: 'Rumah Jepang',
  type: 'Rumah',
  id: 'SA2DFG25GGS9',
  date: '16 Januari 2025',
  image: '/img/rumah-jepang.jpg',
});

const designers = ref([
  { id: 1, name: 'Ali Rohmadanu', avatar: '/img/designer1.jpg' },
  { id: 2, name: 'Ali Rohmadanu', avatar: '/img/designer1.jpg' },
  { id: 3, name: 'Ali Rohmadanu', avatar: '/img/designer1.jpg' },
  { id: 4, name: 'Ali Rohmadanu', avatar: '/img/designer1.jpg' },
  { id: 5, name: 'Ali Rohmadanu', avatar: '/img/designer1.jpg' },
]);

// Avatar modal functions
const openAvatarModal = () => {
  isAvatarModalOpen.value = true;
  selectedFile.value = null;
  avatarPreview.value = null;
};

const handleFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const file = target.files?.[0];
  
  if (!file) return;

  // Validate file type and size
  if (!file.type.match('image.*')) {
    alert('Hanya file gambar yang diperbolehkan');
    return;
  }

  if (file.size > 16 * 1024 * 1024) {
    alert('Ukuran file maksimal 16MB');
    return;
  }

  selectedFile.value = file;
  
  // Create preview
  const reader = new FileReader();
  reader.onload = (e) => {
    avatarPreview.value = e.target?.result as string;
  };
  reader.readAsDataURL(file);
};

const uploadAvatar = async () => {
  if (!selectedFile.value) return;

  isUploading.value = true;
  
  const formData = new FormData();
  formData.append('avatar', selectedFile.value);

  try {
    const response = await axios.post('/profile', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });

    // Update the user's avatar with the new URL
    user.value.avatar = response.data.avatar_url;
    isAvatarModalOpen.value = false;
    
    // Show success message (you can use a toast library)
    alert('Foto profil berhasil diperbarui!');
  } catch (error) {
    console.error('Upload failed:', error);
    alert('Gagal mengunggah foto profil');
  } finally {
    isUploading.value = false;
  }
};

// Background modal functions
const openBgModal = () => {
  isBgModalOpen.value = true;
  selectedBgFile.value = null;
  bgPreview.value = null;
};

const handleBgFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const file = target.files?.[0];
  
  if (!file) return;

  // Validate file type and size
  if (!file.type.match('image.*')) {
    alert('Hanya file gambar yang diperbolehkan');
    return;
  }

  if (file.size > 16 * 1024 * 1024) {
    alert('Ukuran file maksimal 16MB');
    return;
  }

  selectedBgFile.value = file;
  
  // Create preview
  const reader = new FileReader();
  reader.onload = (e) => {
    bgPreview.value = e.target?.result as string;
  };
  reader.readAsDataURL(file);
};

const uploadBackground = async () => {
  if (!selectedBgFile.value) return;

  isBgUploading.value = true;
  
  const formData = new FormData();
  formData.append('background', selectedBgFile.value);

  try {
    const response = await axios.post('/profile/background', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });

    // Update the user's background with the new URL
    user.value.background = response.data.background_url;
    isBgModalOpen.value = false;
    
    // Show success message
    alert('Background profil berhasil diperbarui!');
  } catch (error) {
    console.error('Upload failed:', error);
    alert('Gagal mengunggah background profil');
  } finally {
    isBgUploading.value = false;
  }
};

// Other functions
function designerInitials(name: string) {
  return name.split(' ').map(n => n[0]).join('');
}

function signOut() {
  router.post('/logout');
}

function editProfile() {
  // Implement edit logic
  // Could open a modal or navigate to edit page
}

function cekRequest() {
  // Implement check request logic
}
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