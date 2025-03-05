<script setup>
import { computed } from "vue";
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from "@/Components/ui/table";
import { Button } from "@/Components/ui/button";
import { ScrollArea } from '@/Components/ui/scroll-area'

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
    actions: {
        type: Array,
        required: false,
        default: [],
    },
});

const data = computed(() => props.data);

</script>

<template>
    <ScrollArea class="h-[50vh] w-[100%]">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>ID</TableHead>
                    <TableHead>Name</TableHead>
                    <TableHead>Email</TableHead>
                    <TableHead class="flex justify-center">Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="(row, index) in data" :key="index">
                    <TableCell>{{ row.id }}</TableCell>
                    <TableCell>{{ row.name }}</TableCell>
                    <TableCell>{{ row.email }}</TableCell>
                    <TableCell class="flex justify-center">
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
    </ScrollArea>
</template>