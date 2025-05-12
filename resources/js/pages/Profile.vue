<template >
  <AppLayout class=" bg-[#F6F6F6]">
    <div class="min-h-screen flex flex-col  bg-[#F6F6F6]">
      <div class="flex flex-1 gap-6 px-8 py-6 max-w-7xl w-full">
        <!-- Main Content -->
        <div class="flex-1 flex flex-col gap-6">
          <!-- Profile Card -->
          <Card class="relative overflow-visible">
            <div class="h-40 w-full rounded-t-xl bg-cover bg-center" :style="{ backgroundImage: `url(${profileBg})` }"></div>
            <div class="flex items-end gap-4 px-6 -mt-12">
              <Avatar class="w-24 h-24 border-4 border-white shadow-lg">
                <AvatarImage :src="user.avatar" alt="Profile" />
                <AvatarFallback>{{ userInitials }}</AvatarFallback>
              </Avatar>
              <div class="flex-1">
                <h2 class="text-3xl font-bold text-white drop-shadow-md">{{ user.name }}</h2>
                <div class="font-semibold flex items-center gap-2" style="color: #714C25">
                  {{ user.location }} <span class="text-xs font-normal">Client</span>
                </div>
                <div class="text-xs" style="color: #714C25">Joined since {{ user.joined }}</div>
              </div>
              <div class="flex flex-col gap-2">
                <Button variant="secondary" @click="saveProfile">Save</Button>
                <Button variant="destructive" @click="signOut">Sign Out</Button>
              </div>
            </div>
            <Button class="absolute top-4 right-4" size="icon" variant="ghost" @click="editProfile">
              <Icon name="edit" />
            </Button>
          </Card>

          <!-- My Request Card -->
          <Card class="bg-[#B8864B] text-white">
            <CardHeader>
              <CardTitle style="color: white">My Request</CardTitle>
            </CardHeader>
            <CardContent class="flex items-center gap-4">
              <img :src="request.image" alt="Request" class="w-32 h-24 object-cover rounded-lg" />
              <div class="flex-1" style="color: white">
                <div>Nama : {{ request.name }}</div>
                <div>Type : {{ request.type }}</div>
                <div>ID : {{ request.id }}</div>
                <div>Request Date : {{ request.date }}</div>
              </div>
              <Button variant="secondary" @click="cekRequest">Cek</Button>
            </CardContent>
          </Card>
        </div>
        <!-- Sidebar -->
        <div class="w-80 flex flex-col gap-6">
          <Card>
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
          <Button class="fixed bottom-8 right-8 shadow-lg rounded-full p-4" size="icon" variant="secondary">
            <Icon name="chat" />
          </Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import AppHeader from '@/components/AppHeader.vue';
import LandingFooter from '@/components/landing/LandingFooter.vue';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar';
import Icon from '@/components/Icon.vue';
import { ref } from 'vue';

const user = ref({
  name: 'Rom Doni',
  avatar: '/img/profile.jpg',
  location: 'Mataram, Nusa Tenggara Timur',
  joined: 'March, 2023',
});

const profileBg = '/img/profile-bg.jpg';
const userInitials = user.value.name.split(' ').map(n => n[0]).join('');

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

function designerInitials(name: string) {
  return name.split(' ').map(n => n[0]).join('');
}

function saveProfile() {
  // Implement save logic
}
function signOut() {
  // Implement sign out logic
}
function editProfile() {
  // Implement edit logic
}
function cekRequest() {
  // Implement check request logic
}
</script>

<style scoped>
.bg-profile {
  background: url('/img/profile-bg.jpg') center/cover no-repeat;
}
</style>
