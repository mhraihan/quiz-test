<script setup>
import {computed, ref} from "vue";
import {mdiEye, mdiPencil, mdiTrashCan, mdiSortAscending, mdiSortDescending} from "@mdi/js";
import UserAvatar from "@/Components/UserAvatar.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import BaseLevel from "@/Components/BaseLevel.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseIcon from "@/Components/BaseIcon.vue";
import {Link, useForm} from '@inertiajs/inertia-vue3'

import {useMainStore} from "@/Stores/main";

const props = defineProps({
    Topics: Object,
    Query: Object,
    Role: {
        type: String,
        default: "School"
    }
});
const emit = defineEmits(['sort']);
const id = ref(null);
const url = computed(() => {
    return 'admin.topics.edit';
});
const view = computed(() => {
    return 'admin.topics.show';
});
const links = computed(() => props.Topics?.meta?.links);
const isModalDangerActive = ref(false);
const currentPage = ref(props.Topics?.meta?.current_page ?? 1);
const numPages = computed(() => props.Topics?.meta?.last_page);
const currentPageHuman = computed(() => currentPage.value);
const form = useForm({
    queries: props.Query,
    _method: 'delete'
});


const destroySchool = () => {
    form['id'] = id.value;
    form['queries'] = props.Query;
    useMainStore().destroy(form, 'admin.topics.destroy');
}

const filter = (name) => {
    emit('sort', name);
}

</script>

<template>

    <CardBoxModal
        v-model="isModalDangerActive"
        title="Please confirm"
        button="danger"
        has-cancel
        @confirm="destroySchool"
    >
        <p>Are you sure you want to Delete the {{ props.Role }}?</p>
    </CardBoxModal>


    <table>
        <thead>
        <tr>
            <th>#</th>
            <th @click="filter('title')">
                <div class="flex items-center cursor-pointer">
                    Topic Name
                    <BaseIcon class="ml-1" v-if="props.Query.column === 'title' && props.Query.direction === 'ASC'" small :path="mdiSortAscending"/>
                    <BaseIcon class="ml-1" v-if="props.Query.column === 'title' && props.Query.direction === 'DESC' " small :path="mdiSortDescending"/>
                </div>
            </th>
            <th>
                Number Of Questions
            </th>
            <th/>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(topic,key) in props.Topics.data" :key="topic.id">
            <td data-label="id">
                {{ (currentPageHuman - 1) * 15 + key + 1 }}
            </td>

            <td data-label="Name">
                <div class="flex h-full items-center justify-start">
                      <Link :href="route('admin.topics.show',topic.id)" v-html="topic.title"></Link>
                    <BaseIcon class="text-red-500" v-if="topic.deleted_at" :path="mdiTrashCan" small/>
                </div>
            </td>
            <td data-label="count">
                {{ topic.count }}
            </td>

            <td class="before:hidden lg:w-1 whitespace-nowrap">
                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                        <BaseButton
                        color="info"
                        :icon="mdiEye"
                        small
                        routeName="admin.topics.show"
                        :routeParams="topic.id"
                    />
                    <BaseButton
                        color="info"
                        :icon="mdiPencil"
                        small
                        :data="{ prev_pages: props.Topics?.meta?.current_page,...props.Query}"
                        :routeParams="topic.id"
                        :routeName="url"
                        title="Edit Topic"
                    />
                    <BaseButton
                        color="danger"
                        :icon="mdiTrashCan"
                        small
                        title="Delete School"
                        @click="isModalDangerActive = true;id=topic.id"
                    />
                </BaseButtons>
            </td>
        </tr>
        </tbody>
    </table>
    <div v-if="links?.length > 3" class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
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
