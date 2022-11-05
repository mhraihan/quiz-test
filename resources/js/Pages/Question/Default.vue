<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import {
    mdiTableBorder,
} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";
import QuestionTable from "@/Pages/Question/QuestionTable.vue";

const props = defineProps({
    Questions: Object,
    title: String
});


</script>

<template>
    <Head :title="props.title" />
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton  :icon="mdiTableBorder" :title="title" main>
                <BaseButton v-if="route().current('admin.questions.index')" color="info" label="Create a new Question" routeName="admin.questions.create" />
            </SectionTitleLineWithButton>
            <CardBox v-if="props.Questions.data.length > 0" class="mb-6" has-table>
                <QuestionTable  :Questions="props.Questions"/>
            </CardBox>
            <CardBox v-else>
                <CardBoxComponentEmpty />
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
