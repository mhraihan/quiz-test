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
    Schools: Object,
    Query: Object,
    Role: {
        type: String,
        default: "School"
    }
});
const emit = defineEmits(['sort']);
const id = ref(null);
const url = computed(() => {
    return 'admin.schools.edit';
});
const view = computed(() => {
    return 'admin.schools.show';
});
const links = computed(() => props.Schools?.meta?.links);
const isModalDangerActive = ref(false);
const currentPage = ref(props.Schools?.meta?.current_page ?? 1);
const numPages = computed(() => props.Schools?.meta?.last_page);
const currentPageHuman = computed(() => currentPage.value);
const form = useForm({
    queries: props.Query,
    _method: 'delete'
});


const destroySchool = () => {
    form['id'] = id.value;
    form['queries'] = props.Query;
    useMainStore().destroy(form, 'admin.schools.destroy');
}

const filter = (name) => {
    console.log(props.Query, name)
    emit('sort', name);
}
console.log(props.Query,props.Query.direction === "ASC" ? 'asc' : 'desc');

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
            <th @click="filter('name')">
                <div class="flex items-center cursor-pointer">
                    School Name
                    <BaseIcon class="ml-1" v-if="props.Query.column === 'name' && props.Query.direction === 'ASC'" small :path="mdiSortAscending"/>
                    <BaseIcon class="ml-1" v-if="props.Query.column === 'name' && props.Query.direction === 'DESC' " small :path="mdiSortDescending"/>
                </div>
            </th>
            <th @click="filter('short_name')">
                <div class="flex items-center cursor-pointer">
                    Short Name
                    <BaseIcon class="ml-1" v-if="props.Query.column === 'short_name' && props.Query.direction === 'ASC'" small :path="mdiSortAscending"/>
                    <BaseIcon class="ml-1" v-if="props.Query.column === 'short_name' && props.Query.direction === 'DESC' " small :path="mdiSortDescending"/>
                </div>
            </th>
            <th/>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(school,key) in props.Schools.data" :key="school.id">
            <td data-label="id">
                {{ (currentPageHuman - 1) * 15 + key + 1 }}
            </td>

            <td data-label="Name">
                <div class="flex h-full items-center justify-start">
                    {{ school.name }}
                    <BaseIcon class="text-red-500" v-if="school.deleted_at" :path="mdiTrashCan" small/>
                </div>
            </td>
            <td data-label="Short Name">
                <div class="flex h-full items-center justify-start">
                    {{ school.short_name }}
                    <BaseIcon class="text-red-500" v-if="school.deleted_at" :path="mdiTrashCan" small/>
                </div>
            </td>

            <td class="before:hidden lg:w-1 whitespace-nowrap">
                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                    <BaseButton
                        color="info"
                        :icon="mdiPencil"
                        small
                        :data="{ prev_pages: props.Schools?.meta?.current_page,...props.Query}"
                        :routeParams="school.id"
                        :routeName="url"
                        title="Edit School"
                    />
                    <BaseButton
                        color="danger"
                        :icon="mdiTrashCan"
                        small
                        title="Delete School"
                        @click="isModalDangerActive = true;id=school.id"
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
