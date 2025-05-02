<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthSplitLayout>
        <Head title="Login" />
        
        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-4">
            <div class="grid gap-4">
                <!-- Title Section -->
                <div class="mb-2 text-center">
                    <h1 class="font-archivo text-[50px] font-bold text-neutral-900">Dr. Home</h1>
                    <p class="font-archivo text-[30px] font-medium text-neutral-600 mt-4 mb-4">Login</p>
                </div>

                <!-- Email Input -->
                <div class="grid gap-2 mt-4">
                    <Input
                        id="email"
                        type="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="Email"
                        class="rounded-full border-0 bg-[#F1ECEC] placeholder-[#888888] focus-visible:ring-1 focus-visible:ring-[#AE7A42]"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <!-- Password Input -->
                <div class="grid gap-2 mt-2">
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        v-model="form.password"
                        placeholder="Password"
                        class="rounded-full border-0 bg-[#F1ECEC] placeholder-[#888888] focus-visible:ring-1 focus-visible:ring-[#AE7A42]"
                    />
                    <InputError :message="form.errors.password" />
                    <div class="h-6"></div>
                </div>

                <!-- Login Button -->
                <Button 
                    type="submit" 
                    class="mt-2 w-[80%] mx-auto rounded-full bg-[#AE7A42] text-white hover:bg-[#9a6b3a]" 
                    :tabindex="4" 
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Log in
                </Button>
            </div>

            <!-- Register Button -->
            <div class="text-center text-sm text-muted-foreground">
                <Button 
                    as-child 
                    variant="outline" 
                    class="mt-2 w-[80%] mx-auto rounded-full bg-[#FAAE5C] text-white hover:bg-[#e89d4a] no-underline" 
                    :tabindex="5"
                >
                <Link 
                    :href="route('register')" 
                    class="h-4 w-4 text-white hover:text-white no-underline hover:no-underline"
                >
                    Register
                </Link>
                </Button>
            </div>
        </form>
    </AuthSplitLayout>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&display=swap');

.font-archivo {
    font-family: 'Archivo', sans-serif;
    color: #AE7A42;
}

/* Untuk ShadCN Input yang mungkin di-override */
input::placeholder {
    color: #888888 !important;
    opacity: 1 !important;
}
</style>