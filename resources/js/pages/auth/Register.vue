<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import AuthSplitLayout from '@/layouts/auth/AuthSplitLayout.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthSplitLayout>
        <Head title="Register" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <!-- Title Section -->
                <div class="mb-2 text-center">
                    <h1 class="font-archivo text-[40px] font-bold text-neutral-900">Dr. Home</h1>
                    <h4 class="font-archivo text-[25px] font-medium text-neutral-600 mt-4 mb-4">Register</h4>
                </div>

                <div class="grid gap-2">
                    <Input class="rounded-full border-0 bg-[#F1ECEC] placeholder-[#888888] focus-visible:ring-1 focus-visible:ring-[#AE7A42]"
                    id="email" type="email" required :tabindex="1" autocomplete="email" v-model="form.email" placeholder="Email" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Input class="rounded-full border-0 bg-[#F1ECEC] placeholder-[#888888] focus-visible:ring-1 focus-visible:ring-[#AE7A42]"
                    id="name" type="text" required autofocus :tabindex="2" autocomplete="name" v-model="form.name" placeholder="Name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Input
                        class="rounded-full border-0 bg-[#F1ECEC] placeholder-[#888888] focus-visible:ring-1 focus-visible:ring-[#AE7A42]"
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2 mb-4">
                    <Input
                        class="rounded-full border-0 bg-[#F1ECEC] placeholder-[#888888] focus-visible:ring-1 focus-visible:ring-[#AE7A42]"
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Confirm password"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-4 w-[80%] mx-auto rounded-full bg-[#AE7A42] text-white hover:bg-[#9a6b3a]" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Register
                </Button>
            </div>

            <Button 
                    as-child 
                    variant="outline" 
                    class="mt-2 w-[80%] mx-auto rounded-full bg-[#FAAE5C] text-white hover:bg-[#e89d4a] no-underline" 
                    :tabindex="5"
                >
                <Link 
                    :href="route('login')" 
                    class="h-4 w-4 text-white hover:text-white no-underline hover:no-underline"
                >
                    Login
                </Link>
            </Button>
        </form>
    </AuthSplitLayout>
</template>
