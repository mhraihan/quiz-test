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
import {isAdmin, isTeacher, UserEnum} from "@/config";

const props = defineProps({
    Users: Object,
    Query: {
        type: Object,
        required: true
    },
    Role: {
        type: String,
        default: "Student"
    }
});
const emit = defineEmits(['sort']);
const id = ref(null);
const url = computed(() => {
    if (props.Role === 'User') return 'admin.users.edit';
    if (props.Role === 'Teacher') return 'admin.teachers.edit';
    if(isTeacher()) return 'teacher.student.profile';
    return 'admin.students.edit';
});
const view = computed(() => {
    if (props.Role === 'User') return 'admin.users.show';
    if (props.Role === 'Teacher') return 'teacher.student.profile';
     if(isTeacher()) return 'teacher.student.profile';
    return 'admin.students.show';
});
const links = computed(() => props.Users.meta.links);
const isModalDangerActive = ref(false);
const currentPage = ref(props.Users.meta.current_page);
const numPages = computed(() => props.Users.meta.last_page);
const currentPageHuman = computed(() => currentPage.value);
const form = useForm({
    queries: props.Query,
    _method: 'delete'
});


const destroyQuestion = () => {
    form['id'] = id.value;
    form['queries'] = props.Query;
    useMainStore().destroy(form, 'admin.users.destroy');
}

const filter = (name) => {
    console.log(props.Query, name)
    emit('sort', name);
}
console.log(props.Query,props.Query.direction === "ASC" ? 'asc' : 'desc');

</script>

<template>

    <CardBoxModal
        v-if="isAdmin()"
        v-model="isModalDangerActive"
        title="Please confirm"
        button="danger"
        has-cancel
        @confirm="destroyQuestion"
    >
        <p>Are you sure you want to Delete the {{ props.Role }}?</p>
    </CardBoxModal>


    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Avatar</th>
            <th @click="filter('first_name')">
                <div class="flex items-center cursor-pointer">
                    First Name
                    <BaseIcon class="ml-1" v-if="props.Query.column === 'first_name' && props.Query.direction === 'ASC'" small :path="mdiSortAscending"/>
                    <BaseIcon class="ml-1" v-if="props.Query.column === 'first_name' && props.Query.direction === 'DESC' " small :path="mdiSortDescending"/>
                </div>
            </th>
            <th @click="filter('last_name')">
                <div class="flex items-center cursor-pointer">
                    Last Name
                    <BaseIcon class="ml-1" v-if="props.Query.column === 'last_name' && props.Query.direction === 'ASC'" small :path="mdiSortAscending"/>
                    <BaseIcon class="ml-1" v-if="props.Query.column === 'last_name' && props.Query.direction === 'DESC' " small :path="mdiSortDescending"/>
                </div>
            </th>
            <th @click="filter('email')">
                <div class="flex items-center cursor-pointer">
                    Email
                    <BaseIcon class="ml-1" v-if="props.Query.column === 'email' && props.Query.direction === 'ASC'" small :path="mdiSortAscending"/>
                    <BaseIcon class="ml-1" v-if="props.Query.column === 'email' && props.Query.direction === 'DESC' " small :path="mdiSortDescending"/>
                </div>
            </th>
            <th/>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(user,key) in props.Users.data" :key="user.id">
            <td data-label="id">
                {{ (currentPageHuman - 1) * 15 + key + 1 }}
            </td>

            <td data-label="Avatar">
                <Link :href="route(url,user.id)"
                      :data="{ prev_pages: props.Users.meta.current_page,...props.Query}" preserve-state>
                    <userAvatar :username="user.name" api="initials" class="w-12"/>
                </Link>
            </td>
            <td data-label="First Name">
                <div class="flex h-full items-center justify-start">
                    {{ user.first_name }}
                    <BaseIcon class="text-red-500" v-if="user.deleted_at" :path="mdiTrashCan" small/>
                </div>
            </td>
            <td data-label="Last Name">
                <div class="flex h-full items-center justify-start">
                    {{ user.last_name }}
                    <BaseIcon class="text-red-500" v-if="user.deleted_at" :path="mdiTrashCan" small/>
                </div>
            </td>
            <td data-label="Email Address">
                {{ user.email }}
            </td>
            <td class="before:hidden lg:w-1 whitespace-nowrap">
                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                    <BaseButton
                        v-if="props.Role.toLowerCase() === UserEnum.STUDENT"
                        color="info"
                        :icon="mdiEye"
                        small
                        :data="{ prev_pages: props.Users.meta.current_page,...props.Query}"
                        :routeParams="user.id"
                        :routeName="view"
                        title="View Profile"
                    />
                    <BaseButton
                        v-if="isAdmin()"
                        color="info"
                        :icon="mdiPencil"
                        small
                        :data="{ prev_pages: props.Users.meta.current_page,...props.Query}"
                        :routeParams="user.id"
                        :routeName="url"
                        title="Edit Profile"
                    />
                    <BaseButton
                        v-if="isAdmin()"
                        color="danger"
                        :icon="mdiTrashCan"
                        small
                        title="Delete Profile"
                        @click="isModalDangerActive = true;id=user.id"
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
