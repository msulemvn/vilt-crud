<script setup>
import { defineProps, computed } from "vue";
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from "@/Components/ui/table";
import { Button } from "@/Components/ui/button";

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
    actions: {
        type: Array,
        required: false,
        default: [],
    },
});

const users = computed(() => props.users);
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
</template>