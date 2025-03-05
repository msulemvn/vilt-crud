<script setup>
import { defineProps, computed } from "vue";
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from "@/Components/ui/table";
import { Button } from "@/Components/ui/button";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    actions: {
        type: Array,
        required: false,
        default: [],
    },
});

const users = computed(() => props.users);
const page = usePage();
const meta = computed(() => page.props.users.meta);
const currentPage = computed(() => meta.value.current_page);
const totalPages = computed(() => meta.value.last_page);
const prevPageUrl = computed(() => meta.value.links.find(link => link.label === "&laquo; Previous")?.url);
const nextPageUrl = computed(() => meta.value.links.find(link => link.label === "Next &raquo;")?.url);
</script>

<template>
    <Table>
        <TableHeader>
            <TableRow>
                <TableHead>ID</TableHead>
                <TableHead>Name</TableHead>
                <TableHead>Email</TableHead>
                <TableHead>Actions</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="(row, index) in users" :key="index">
                <TableCell>{{ row.id }}</TableCell>
                <TableCell>{{ row.name }}</TableCell>
                <TableCell>{{ row.email }}</TableCell>
                <TableCell>
                    <Button variant="outline" @click="() => props.actions[0](row)">
                        <font-awesome-icon icon="pencil" class="pr-2" />Edit
                    </Button>
                    <Button variant="outline" @click="() => props.actions[1](row)" class="ml-1">
                        <font-awesome-icon icon="trash" class="pr-2" />Delete
                    </Button>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
    <div class="flex justify-between items-center py-4">
        <Button variant="outline" :disabled="!prevPageUrl"
            @click="$inertia.get(prevPageUrl, {}, { preserveScroll: true })">
            Previous
        </Button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <Button variant="outline" :disabled="!nextPageUrl"
            @click="$inertia.get(nextPageUrl, {}, { preserveScroll: true })">
            Next
        </Button>
    </div>
</template>