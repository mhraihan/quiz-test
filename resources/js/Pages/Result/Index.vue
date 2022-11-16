<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import {
    mdiTableBorder,
} from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import CardBoxWidget from "@/Components/CardBoxWidget.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";
import ResultTable from "@/Pages/Result/ResultTable.vue";
import {ref} from "vue";


const props = defineProps({
    results: Object,
    total_exam: Number,
    total_questions: Number,
    score: Number,
});
const title = ref("Exam Results");



</script>

<template>
    <Head :title="title" />
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton  :icon="mdiTableBorder" :title="title" main />
            <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
                <CardBoxWidget
                    trend="Overview"
                    trend-type="info"
                    color="text-emerald-500"
                    :number="props.results.total"
                    label="Exam Taken"
                />
                <CardBoxWidget
                    trend="Overview"
                    trend-type="info"
                    color="text-blue-500"
                    :number="props.total_questions"
                    label="Answered Questions"
                />
                <CardBoxWidget
                    trend="Overview"
                    trend-type="info"
                    color="text-red-500"
                    :number="props.score"
                    label="Avg. Score"
                    suffix="%"
                />

            </div>
            <CardBox v-if="props.results.data.length > 0" class="mb-6" has-table>
                <ResultTable  :results="props.results"/>
            </CardBox>
            <CardBox v-else>
                <CardBoxComponentEmpty />
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
