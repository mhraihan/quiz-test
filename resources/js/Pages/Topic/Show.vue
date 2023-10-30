<style src="@vueform/multiselect/themes/default.css"></style>
<script setup>
import {Head} from "@inertiajs/inertia-vue3";
import {mdiArrowLeft} from "@mdi/js";
import BaseButton from "@/Components/BaseButton.vue";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";
import Overview from "@/Pages/Result/Overview.vue";
import QuestionTable from "@/Shared/Question/QuestionTable.vue";

const props = defineProps({
    Topic: Object,
    Questions: Object,
});

</script>

<template>
    <Head :title="props.Topic.title"/>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton link="admin.topics.index" :icon="mdiArrowLeft" title="Topic Details"
                                        main/>
            <div class="flex flex-wrap items-center justify-between mb-6 sm:flex-row flex-col">
                <BaseButton color="info" label="Create a new Topic" routeName="admin.topics.create"/>
                <BaseButton color="info" label="Edit Topic" routeName="admin.topics.edit"
                            :routeParams="props.Topic.id"/>
            </div>
            <CardBox class=" overflow-hidden sm:rounded-lg mb-4">
                <div class="border-t border-gray-300 ">
                    <dl>
                        <Overview label="Topic Name" class="uppercase" :value="props.Topic.title"/>
                        <Overview label="Number of Questions" class="uppercase" :value="props.Questions.total"/>
                    </dl>
                </div>
            </CardBox>
            <SectionMain class="!p-0 my-10">
                <CardBox v-if="props.Questions.data.length > 0" class="mb-6" has-table>
                    <QuestionTable :Questions="props.Questions"/>
                </CardBox>
                <CardBox v-else>
                    <CardBoxComponentEmpty/>
                </CardBox>
            </SectionMain>
        </SectionMain>
    </LayoutAuthenticated>
</template>
