<script setup>
import { Button } from "@/Components/ui/button";
import {
    Pagination,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
    PaginationEllipsis,
} from "@/Components/ui/pagination";
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    meta: {
        type: Object,
        required: true,
    },
});

const links = computed(() => props.meta.links);

const handleClickFirstPage = () => {
    if (props.meta.current_page > 1) {
        const firstPageLink = links.value.find((page) => page.label === '1');
        if (firstPageLink?.url) {
            router.get(firstPageLink.url, {}, { preserveScroll: true });
        }
    }
};

const handleClickLastPage = () => {
    if (props.meta.current_page < props.meta.last_page) {
        const lastPageLink = links.value.find((page) => page.label === props.meta.last_page.toString());
        if (lastPageLink?.url) {
            router.get(lastPageLink.url, {}, { preserveScroll: true });
        }
    }
};

const handleClickPrevPage = () => {
    if (props.meta.current_page > 1) {
        const prevPageLink = links.value.find((page) => page.label === '&laquo; Previous');
        if (prevPageLink?.url) {
            router.get(prevPageLink.url, {}, { preserveScroll: true });
        }
    }
};

const handleClickPage = (pageNumber) => {
    const pageLink = links.value.find((link) => link.label == pageNumber.toString());
    if (pageLink?.url) {
        router.get(pageLink.url, {}, { preserveScroll: true });
    }
};

const handleClickNextPage = () => {
    if (props.meta.current_page < props.meta.last_page) {
        const nextPageLink = links.value.find((page) => page.label === 'Next &raquo;');
        if (nextPageLink?.url) {
            router.get(nextPageLink.url, {}, { preserveScroll: true });
        }
    }
};

</script>

<template>
    <Pagination v-slot="{ page }" :items-per-page="meta.per_page" :total="meta.total" :sibling-count="1" show-edges
        :default-page="meta.current_page">
        <PaginationList v-slot="{ items }" class="flex items-center gap-1">
            <PaginationFirst :disabled="meta.current_page === 1" @click="handleClickFirstPage" />

            <PaginationPrev :disabled="meta.current_page === 1" @click="handleClickPrevPage" />

            <template v-for="(item, index) in items" :key="index">
                <PaginationListItem v-if="item.type === 'page'" :value="item.value" as-child>
                    <Button class="w-10 h-10 p-0" :variant="item.value === page ? 'default' : 'outline'"
                        @click="handleClickPage(item.value)">
                        {{ item.value }}
                    </Button>
                </PaginationListItem>
                <PaginationEllipsis v-else :key="item.type" />
            </template>

            <PaginationNext :disabled="meta.current_page === meta.last_page" @click="handleClickNextPage" />

            <PaginationLast :disabled="meta.current_page === meta.last_page" @click="handleClickLastPage" />
        </PaginationList>
    </Pagination>
</template>
