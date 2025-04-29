<script setup lang="ts">
import LandingLayout from '@/layouts/LandingLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({
    layout: LandingLayout,
});
</script>

<template>
    <div>
        <!-- Hero Section -->
        <section class="relative bg-gray-50 py-20">
            <div class="container mx-auto px-4">
                <div class="flex flex-col items-center text-center">
                    <h1 class="mb-4 text-5xl font-bold text-[#1E3851]">Dr. Home</h1>
                    <p class="mb-8 text-2xl text-gray-600">Your dream home, in just one click.</p>
                    <Link href="/order" class="rounded-full bg-[#B07D48] px-8 py-3 text-lg text-white transition-colors hover:bg-[#95683C]">
                        Order Dream House
                    </Link>
                </div>
            </div>

            <!-- Decorative elements -->
            <div class="absolute top-1/2 left-1/2 -z-10 -translate-x-1/2 -translate-y-1/2 transform">
                <div class="h-96 w-96 rounded-full bg-[#B07D48]/5 blur-3xl"></div>
            </div>
        </section>

        <!-- How it Works Section -->
        <section class="bg-white py-20">
            <div class="container mx-auto px-4">
                <h2 class="mb-12 text-center text-3xl font-bold text-[#1E3851]">How Dr. Home Works?</h2>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <!-- Find Designer Card -->
                    <div class="rounded-lg bg-white p-6 shadow-lg transition-shadow hover:shadow-xl">
                        <h3 class="mb-4 text-xl font-bold text-[#1E3851]">Find Designer</h3>
                        <p class="text-gray-600">Find the designer that catches your eyes.</p>
                    </div>

                    <!-- Build Properties Card -->
                    <div class="rounded-lg bg-white p-6 shadow-lg transition-shadow hover:shadow-xl">
                        <h3 class="mb-4 text-xl font-bold text-[#1E3851]">Build Properties</h3>
                        <p class="text-gray-600">Discuss and find contractor to build your property.</p>
                    </div>

                    <!-- Pay Properties Card -->
                    <div class="rounded-lg bg-white p-6 shadow-lg transition-shadow hover:shadow-xl">
                        <h3 class="mb-4 text-xl font-bold text-[#1E3851]">Pay Properties</h3>
                        <p class="text-gray-600">Pay it once it all done.</p>
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <Link
                        href="/order"
                        class="inline-block rounded-full bg-[#B07D48] px-8 py-3 text-lg text-white transition-colors hover:bg-[#95683C]"
                    >
                        Order Dream House
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>
