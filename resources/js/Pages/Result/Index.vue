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
import {ref, computed} from "vue";
import Placeholder from "@/Components/Placeholder.vue";


const props = defineProps({
    results: Object,
    exam: Object,
    loading: {
        type: Boolean,
        default: true,
    }
});
const title = ref("Exam Results");
const total_questions = computed(() => parseInt(props.exam.results_sum_total_questions));
const correct_answered = computed(() => parseInt(props.exam.results_sum_correct_answered));
const score = parseFloat(((correct_answered.value/total_questions.value) * 100).toFixed(2));

</script>

<template>
    <Head :title="title" />
    <LayoutAuthenticated>
         <SectionMain v-if="props?.loading">
            <Placeholder/>
        </SectionMain>
        <SectionMain v-else>
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
                    :number="total_questions"
                    label="Answered Questions"
                />
                <CardBoxWidget
                    trend="Overview"
                    trend-type="info"
                    color="text-red-500"
                    :number="score"
                    label="Avg. Score"
                    suffix="%"
                />

            </div>
            <CardBox v-if="props.results.data.length > 0" class="mb-6" has-table>
                <ResultTable  :results="props.results"/>
            </CardBox>
            <CardBox v-else>
                <CardBoxComponentEmpty text="No Results found!" routeName="quiz.index" label="New Quiz Test"/>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
