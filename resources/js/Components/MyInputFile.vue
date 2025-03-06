<script setup lang="ts">
import { ref } from 'vue';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';

defineProps<{ modelValue?: File | null }>();

const emit = defineEmits(['update:modelValue']);0

const fileInput = ref<HTMLInputElement | null>(null);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0] || null;
    emit('update:modelValue', file);
};
</script>

<template>
    <div class="grid w-full max-w-sm items-center gap-1.5">
        <Label for="file">Upload File</Label>
        <Input id="file" type="file" ref="fileInput" @change="handleFileChange" />
    </div>
</template>
