<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
  <img 
    src="/images/home.svg" 
    class ="ml-12"
    :class="className" 
    v-bind="$attrs"
    alt="Home Logo"
  />
</template>
