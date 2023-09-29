<script setup>
import {computed, ref} from "vue";
import {mdiEye, mdiPencil, mdiTrashCan} from "@mdi/js";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import BaseLevel from "@/Components/BaseLevel.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseButton from "@/Components/BaseButton.vue";
import {Link, useForm, usePage} from '@inertiajs/inertia-vue3'
import {notify} from "notiwind";

const props = defineProps({
    Questions: Object,
});
const id = ref(null);

const links = computed(() => props.Questions.links);

const isModalDangerActive = ref(false);
const currentPage = ref(props.Questions.current_page);
const numPages = computed(() => props.Questions.last_page);
const currentPageHuman = computed(() => currentPage.value);
const form = useForm({
    _method: 'delete'
});

const destroyQuestion = () => {
    form.post(route('admin.questions.destroy', id.value), {
            onSuccess: () => {
                notify({
                    group: "notification",
                    type: "success",
                    title: "Success",
                    text: usePage().props.flash?.success || 'Question Moved to Trash Successfully'
                }, 4000) // 4s
            },
            onError: () => {
                notify({
                    group: "notification",
                    type: "error",
                    title: "Error",
                    text: usePage().props.flash?.error || 'Something went wrong'
                }, 4000) // 4s
            }
        });
}
</script>

<template>

    <CardBoxModal
        v-model="isModalDangerActive"
        title="Please confirm"
        button="danger"
        has-cancel
        @confirm="destroyQuestion"
    >
        <p>Are you sure you want to Delete the question?</p>
    </CardBoxModal>


    <table>
        <thead>
        <tr>
            <th>Question Name</th>
            <th/>
        </tr>
        </thead>
        <tbody>
        <tr v-for="question in props.Questions.data" :key="question.id">

            <td data-label="Title">
                <Link :href="route('admin.questions.edit',question.id)"> {{ question.title }}</Link>
            </td>

            <td class="before:hidden lg:w-1 whitespace-nowrap">
                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                    <BaseButton
                        color="info"
                        :icon="mdiEye"
                        small
                        routeName="admin.questions.show"
                        :routeParams="question.id"
                    />
                        <BaseButton
                        color="info"
                         :icon="mdiPencil"
                        small
                        routeName="admin.questions.edit"
                        :routeParams="question.id"
                    />
                    <BaseButton
                        color="danger"
                        :icon="mdiTrashCan"
                        small
                        @click="isModalDangerActive = true;id=question.id"
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
