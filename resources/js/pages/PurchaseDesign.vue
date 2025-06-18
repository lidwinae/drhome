<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import axios from 'axios';
import { type BreadcrumbItem } from '@/types';

// Get design ID from route params
const page = usePage();
const designId = page.props.designId ?? null;

const design = ref<any>(null);
const loading = ref(true);
const error = ref<string | null>(null);
const amount = ref('');

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Purchase Design',
        href: `/design/${designId}/purchase`,
    },
];

function handleImageError(event: Event) {
  const target = event.target as HTMLImageElement;
  target.src = '/images/design.jpg';
}

function formatCurrency(event: Event) {
  const input = event.target as HTMLInputElement;
  let value = input.value.replace(/[^0-9.]/g, '');

  // Ensure proper decimal format
  const parts = value.split('.');
  if (parts.length > 1) {
    value = parts[0] + '.' + parts[1].slice(0, 2);
  }

  amount.value = value;
}

const isFormValid = computed(() => {
  return amount.value !== '' && parseFloat(amount.value) > 0;
});

function submitPayment() {
  if (!isFormValid.value) return;

  if (!window.confirm('Apakah anda yakin ingin melakukan pembayaran?')) {
    return;
  }

  const payload = {
    design_id: design.value.id,
    amount: amount.value,
    design_path: design.value.file_path || design.value.file_url?.split('/storage/')[1]
  };

  console.log('Submitting payment with payload:', payload); // Debug log

  router.post(route('purchase.post'), payload, {
    onSuccess: () => {
      console.log('Payment successful'); // Debug log
    },
    onError: (errors) => {
      console.error('Payment failed:', errors); // Debug log
    }
  });
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
    console.log('Design data:', response.data); // Debug log
    design.value = response.data;
    loading.value = false;
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to load design details';
    loading.value = false;
  }
});
</script>

<template>
  <Head title="Purchase Design" />

  <AppLayout :breadcrumbs="breadcrumbs" class="bg-gray-50">
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
                </div>
              </div>
            </div>

            <!-- Payment Section -->
            <div class="bg-white rounded-3xl p-6 shadow-sm">
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1">
                  <h3 class="text-2xl font-bold mb-4 text-[#AE7A42]">Enter Payment</h3>
                  <p class="mb-4 text-black">{{ design.description || '-' }}</p>
                  <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (IDR)</label>
                    <input
                      type="number"
                      id="amount"
                      v-model="amount"
                      step="0.01"
                      min="0"
                      placeholder="0.00"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#AE7A42] focus:border-[#AE7A42] required"
                      @input="formatCurrency"
                    >
                  </div>
                </div>
                <div class="sm:self-end">
                    <button
                        @click="submitPayment"
                        :disabled="!isFormValid"
                        :class="{
                        'bg-[#AE7A42] hover:bg-[#8e6536]': isFormValid,
                        'bg-gray-400 cursor-not-allowed': !isFormValid
                        }"
                        class="w-full sm:w-auto inline-flex justify-center text-white font-bold py-3 px-6 rounded-full text-[16px] transition-colors duration-200"
                    >
                        Pay
                    </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
