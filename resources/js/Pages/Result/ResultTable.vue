<script setup>
import {computed, ref} from "vue";
import {mdiEye} from "@mdi/js";
import BaseLevel from "@/Components/BaseLevel.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseButton from "@/Components/BaseButton.vue";
import {Link} from '@inertiajs/inertia-vue3'

const props = defineProps({
    results: Object,
});
const id = ref(null);

const links = computed(() => props.results.links);

const currentPage = ref(props.results.current_page);
const numPages = computed(() => props.results.last_page);
const currentPageHuman = computed(() => currentPage.value);

</script>

<template>

    <table>
        <thead>
        <tr>
            <th>QUIZ SIZE</th>
            <th>STATUS</th>
            <th>SCORE</th>
            <th>DATE</th>
            <th/>
        </tr>
        </thead>
        <tbody>
        <tr v-for="result in props.results.data" :key="result.id">

            <td class="px-6 " data-label="Quiz Size">
                <Link :href="route('results.show',result.id)"
                      class="px-6 py-1 font-extrabold text-white bg-blue-500 rounded-lg hover:bg-blue-600 hover:underline">
                    {{ result.total_questions }}
                </Link>
            </td>
            <td data-label="Quiz Status">
                {{ result.complete ? 'Completed' : 'Not Completed' }}
            </td>

            <td data-label="Progress">
                <div class="text-sm ">
                    <progress class="max-w-full mx-auto mr-1 " :id="'quiz-' + result.id " :value="result.score " max="100">
                        {{ result.score }}
                    </progress>
                    <span>{{ result.score }}%</span>
                </div>
            </td>
            <td data-label="Quiz Exam">
                {{ result.exam }}
            </td>
            <td class="before:hidden lg:w-1 whitespace-nowrap">
                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                    <BaseButton
                        color="info"
                        :icon="mdiEye"
                        small
                        routeName="results.show"
                        :routeParams="result.id"
                    />
                </BaseButtons>
            </td>
        </tr>
        </tbody>
    </table>
    <div v-if="links.length > 3" class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
        <BaseLevel>
            <BaseButtons>
                <BaseButton
                    v-for="(link, key) in links"
                    :key="`link-${key}`"
                    :disabled="link.active || !link.url"
                    :label="link.label"
                    :color=" link.active ? 'lightDark' : 'whiteDark'"
                    small
                    :href="link.url"
                />
            </BaseButtons>
            <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
        </BaseLevel>
    </div>
</template>
