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
import QuestionTable from "@/Shared/Question/QuestionTable.vue";
import {ref,watch} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {debounce} from "lodash";

const props = defineProps({
    Questions: Object,
    title: String
});
const search = ref(null);
watch(search, debounce((value) => {
    Inertia.get(route('admin.questions.index'),{search: value}, {
        preserveState: true,
        replace: true
    })
},300));

const reset = () => {
    search.value = null;
}
</script>

<template>
    <Head :title="props.title" />
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton  :icon="mdiTableBorder" :title="title" main />
            <div class="flex flex-wrap items-center justify-between mb-6 sm:flex-row flex-col" v-if="route().current('admin.questions.index')">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="relative text-gray-600">
                        <input v-model="search" type="search" name="search" placeholder="Search" class="bg-white h-10 px-5 pr-10 rounded-full text-sm focus:outline-none">
                        <button type="submit" class="absolute right-0 top-0 mt-3 mr-4 pointer-events-none">
                            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve" width="512px" height="512px">
                              <path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z"/>
                            </svg>
                        </button>
                    </div>
                    <button @click="reset()" class="ml-3 text-gray-500 hover:text-gray-700 focus:text-indigo-500 text-sm" type="button">Reset</button>
                </div>
                <BaseButton v-if="route().current('admin.questions.index')" color="info" label="Create a new Question" routeName="admin.questions.create" />
            </div>
            <CardBox v-if="props.Questions.data.length > 0" class="mb-6" has-table>
                <QuestionTable  :Questions="props.Questions"/>
            </CardBox>
            <CardBox v-else>
                <CardBoxComponentEmpty />
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
