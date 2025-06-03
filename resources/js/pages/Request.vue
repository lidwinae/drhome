<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const statuses = [
    { id: 'request_made', label: 'Request Made' },
    { id: 'designer_accepted', label: 'Designer Accepted' },
    { id: 'designer_rejected', label: 'Designer Rejected' },
    { id: 'payment_made', label: 'Payment Made' },
    { id: 'design_delivered', label: 'Design Delivered' },
    { id: 'contractor_assigned', label: 'Contractor Assigned' },
    { id: 'payment_made_for_build', label: 'Build Payment Made' },
    { id: 'construction_started', label: 'Construction Started' },
    { id: 'construction_completed', label: 'Construction Completed' }
];

const currentStatus = ref('request_made');

onMounted(() => {
    const generalInfo = document.querySelector('.general-info') as HTMLElement;
    const otherSections = document.querySelectorAll(
        '.architectural-section, .layout-section, .ambient-section, .timeline-section'
    );

    if (!generalInfo) return;

    const updateWidth = () => {
        const width = generalInfo.offsetWidth + 'px';
        otherSections.forEach((section) => {
            (section as HTMLElement).style.width = width;
        });
    };

    const resizeObserver = new ResizeObserver(() => {
        updateWidth();
    });

    resizeObserver.observe(generalInfo);

    updateWidth();

    onBeforeUnmount(() => {
        resizeObserver.disconnect();
    });
});
</script>

<template>
    <Head title="Request" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <main class="bg-gray-50 w-full min-h-screen p-4 md:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- General Information Section -->
                <section class="flex flex-col lg:flex-row gap-6">
                    <div class="general-info bg-white rounded-2xl shadow-md p-6 lg:w-2/3">
                        <div class="space-y-6">
                            <h2 class="text-2xl font-bold text-gray-800 border-b pb-3">General Information</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">Province</label>
                                    <input type="text" placeholder="Enter Province" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                                
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" placeholder="Enter City" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                                
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">Land Size (m²)</label>
                                    <input type="text" placeholder="Enter Land Size" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                                
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">Land Shape</label>
                                    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                        <option value="">Select Land Shape</option>
                                        <option value="rectangle">Rectangle</option>
                                        <option value="square">Square</option>
                                        <option value="irregular">Irregular</option>
                                    </select>
                                </div>
                                
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">Preview Picture</label>
                                    <div class="flex items-center gap-3">
                                        <input type="file" class="hidden" id="preview-picture">
                                        <label for="preview-picture" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg cursor-pointer transition">
                                            Choose File
                                        </label>
                                        <span class="text-sm text-gray-500">No file chosen</span>
                                    </div>
                                </div>
                                
                                <div class="space-y-1">
                                    <label class="block text-sm font-medium text-gray-700">Budget (IDR)</label>
                                    <input type="text" placeholder="Enter Budget" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Timeline -->
                    <aside class="bg-white rounded-2xl shadow-md p-6 lg:w-1/3">
                        <h2 class="text-2xl font-bold text-gray-800 border-b pb-3 mb-6">Project Status</h2>
                        
                        <div class="space-y-4">
                            <div v-for="(status, index) in statuses" :key="status.id" class="flex items-start">
                                <div class="flex-shrink-0 relative">
                                    <!-- Status circle -->
                                    <div :class="{
                                        'h-6 w-6 rounded-full flex items-center justify-center': true,
                                        'bg-[#AE7A42] text-white': currentStatus === status.id,
                                        'bg-gray-200 text-gray-500': currentStatus !== status.id,
                                        'ring-2 ring-[#AE7A42] ring-offset-2': index === 0 && currentStatus === status.id
                                    }">
                                        <span v-if="currentStatus === status.id">✓</span>
                                    </div>
                                    
                                    <!-- Vertical line between statuses -->
                                    <div v-if="index < statuses.length - 1" 
                                        class="absolute left-1/2 top-6 w-0.5 h-10 -ml-px bg-gray-200"></div>
                                </div>
                                
                                <div class="ml-4">
                                    <p :class="{
                                        'text-sm font-medium': true,
                                        'text-[#AE7A42]': currentStatus === status.id,
                                        'text-gray-500': currentStatus !== status.id
                                    }">
                                        {{ status.label }}
                                    </p>
                                    <p v-if="currentStatus === status.id" class="text-xs text-gray-500 mt-1">
                                        Current status
                                    </p>
                                </div>
                            </div>
                        </div>
                    </aside>
                </section>

                <!-- Architectural Style Section -->
                <section class="architectural-section bg-white rounded-2xl shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 border-b pb-3 mb-6">Architectural Style</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Property Type</label>
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                <option value="">Select Property Type</option>
                                <option value="house">House</option>
                                <option value="apartment">Apartment</option>
                                <option value="villa">Villa</option>
                                <option value="office">Office</option>
                            </select>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Style</label>
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                <option value="">Select Style</option>
                                <option value="modern">Modern</option>
                                <option value="minimalist">Minimalist</option>
                                <option value="traditional">Traditional</option>
                                <option value="industrial">Industrial</option>
                            </select>
                        </div>
                        
                        <div class="space-y-1 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">References</label>
                            <div class="flex flex-wrap gap-3">
                                <div class="relative w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <button class="absolute top-1 right-1 bg-white rounded-full p-1 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <img src="https://via.placeholder.com/100" alt="Reference" class="w-full h-full object-cover rounded-lg">
                                </div>
                                <button class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center text-gray-400 hover:border-[#AE7A42] hover:text-[#AE7A42] transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span class="text-xs mt-1">Add Image</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Room Layout Section -->
                <section class="layout-section bg-white rounded-2xl shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 border-b pb-3 mb-6">Room Layout</h2>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Facility</label>
                                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                    <option value="">Select Facility</option>
                                    <option value="bedroom">Bedroom</option>
                                    <option value="bathroom">Bathroom</option>
                                    <option value="kitchen">Kitchen</option>
                                    <option value="living-room">Living Room</option>
                                </select>
                            </div>
                            
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden w-fit">
                                    <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200 transition">-</button>
                                    <span class="px-4 py-1 border-x border-gray-300">1</span>
                                    <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200 transition">+</button>
                                </div>
                            </div>
                            
                            <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition text-sm font-medium">
                                Add Facility
                            </button>
                        </div>
                        
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-3">Added Facilities</h3>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span>Bedroom (2)</span>
                                    <button class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <span>Bathroom (1)</span>
                                    <button class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Ambient Section -->
                <section class="ambient-section bg-white rounded-2xl shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 border-b pb-3 mb-6">Ambient</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Sun Orientation</label>
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                <option value="">Select Sun Orientation</option>
                                <option value="north">North</option>
                                <option value="south">South</option>
                                <option value="east">East</option>
                                <option value="west">West</option>
                            </select>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Wind Orientation</label>
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition">
                                <option value="">Select Wind Orientation</option>
                                <option value="north">North</option>
                                <option value="south">South</option>
                                <option value="east">East</option>
                                <option value="west">West</option>
                            </select>
                        </div>
                        
                        <div class="space-y-1 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">More Information</label>
                            <textarea placeholder="Enter any additional information about the ambient conditions..." 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition h-32"></textarea>
                        </div>
                    </div>
                </section>

                <!-- Timeline Section -->
                <section class="timeline-section bg-white rounded-2xl shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 border-b pb-3 mb-6">Timeline</h2>
                    
                    <div class="space-y-1 max-w-md">
                        <label class="block text-sm font-medium text-gray-700">Deadline</label>
                        <div class="relative">
                            <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#AE7A42] focus:border-[#AE7A42] outline-none transition pr-10">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Footer -->
                <div class="flex justify-end">
                    <button class="px-6 py-3 bg-[#AE7A42] hover:bg-[#8c5e30] text-white font-medium rounded-lg shadow transition">
                        Submit Request
                    </button>
                </div>
            </div>
        </main>
    </AppLayout>
</template>