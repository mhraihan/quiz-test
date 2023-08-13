<script setup>
import {onMounted} from "vue";
import SectionMain from "@/Components/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import Placeholder from "@/Components/Placeholder.vue";
import {Inertia} from "@inertiajs/inertia";
import OverviewSection from "@/Shared/Teacher/OverviewSection.vue";
import StudentOverviewSection from "@/Shared/Teacher/StudentOverviewSection.vue";

const props = defineProps({
    data: {
        type: Object,
        default: {
            loading: true,
        },
    },
    filters: Object,
});

onMounted(() => {
    Inertia.reload({
        only: ['data']
    });
});


</script>

<template>
    <LayoutAuthenticated>
        <SectionMain v-if="data?.loading">
            <Placeholder/>
        </SectionMain>
        <SectionMain v-else>
            <OverviewSection :students="props?.data?.totalStudent" :exam="props?.data?.examTakenCount"/>
            <StudentOverviewSection :students="props.data?.students" :Role="props.Role"/>
        </SectionMain>
    </LayoutAuthenticated>
</template>
