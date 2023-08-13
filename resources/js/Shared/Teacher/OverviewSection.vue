<script setup>

import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import {mdiChartTimelineVariant} from "@mdi/js";
import CardBoxWidget from "@/Components/CardBoxWidget.vue";

const props = defineProps({
    students: {
        type: Number,
        default: 0,
    },
    exam: {
        type: Number,
        default: 0,
    },
});

</script>

<template>
    <SectionTitleLineWithButton
        :icon="mdiChartTimelineVariant"
        title="Teacher Overview"
        main
    ></SectionTitleLineWithButton>
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">

        <CardBoxWidget
            trend="Overview"
            trend-type="info"
            color="text-emerald-500"
            :number="props?.students"
            label="Number of Student"
        />
        <CardBoxWidget
            trend="Overview"
            trend-type="info"
            color="text-emerald-500"
            :number="props?.exam"
            label="Total Exam Taken"
        />
    </div>
</template>

