<script setup lang="ts">
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
</script>

<template>
    <div class="grid w-full max-w-sm items-center gap-1.5">
        <Label for="picture">Display Photo</Label>
        <Input id="picture" type="file" />
    </div>
</template>