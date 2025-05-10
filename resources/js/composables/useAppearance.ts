import { onMounted, ref } from 'vue';

type Appearance = 'light';

export function updateTheme() {
    if (typeof window === 'undefined') {
        return;
    }

    // Selalu set ke mode terang
    document.documentElement.classList.remove('dark');
    document.documentElement.classList.add('light');
}

const setCookie = (name: string, value: string, days = 365) => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;

    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

export function initializeTheme() {
    if (typeof window === 'undefined') {
        return;
    }

    // Selalu set ke mode terang
    updateTheme();
}

export function useAppearance() {
    const appearance = ref<Appearance>('light');

    onMounted(() => {
        // Selalu set ke mode terang
        appearance.value = 'light';
    });

    function updateAppearance() {
        appearance.value = 'light';

        // Simpan di localStorage untuk persistensi client-side
        localStorage.setItem('appearance', 'light');

        // Simpan di cookie untuk SSR
        setCookie('appearance', 'light');

        updateTheme();
    }

    return {
        appearance,
        updateAppearance,
    };
}