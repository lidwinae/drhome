<script setup lang="ts">
import LandingLayout from '@/layouts/LandingLayout.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({
    layout: LandingLayout,
});
</script>

<template>
    <div>
        <div class="flex justify-center px-4 my-5 md:my-10 w-full">
            <section class="relative w-full max-w-[1000px] rounded-3xl md:rounded-4xl overflow-hidden"
                style="aspect-ratio: 2.5/1">
                
                <!-- Background Image -->
                <img src="/images/hero-bg-section.png" alt="Hero Background" 
                    class="absolute inset-0 w-full h-full object-cover" />
                
                <!-- Content -->
                <div class="relative h-full flex items-center justify-center">
                    <div class="flex flex-col items-center text-center py-8 md:py-20 px-4 w-full">
                        <h1 class="mb-2 md:mb-4 text-3xl md:text-5xl font-bold text-[#183D55]">Dr. Home</h1>
                        <p class="mb-4 md:mb-8 text-lg md:text-2xl text-[#183D55] px-2">Your dream home, in just one click.</p>
                        <Link href="/build" 
                            class="rounded-full bg-[#B07D48] px-6 py-2 md:px-8 md:py-3 text-base md:text-lg text-white transition-colors hover:bg-[#95683C]">
                            Order Dream House
                        </Link>
                    </div>
                </div>
            </section>
        </div>

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
                        href="/build"
                        class="inline-block rounded-full bg-[#B07D48] px-8 py-3 text-lg text-white transition-colors hover:bg-[#95683C]"
                    >
                        Order Dream House
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>