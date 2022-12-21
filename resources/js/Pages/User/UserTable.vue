<script setup>
import {computed, ref} from "vue";
import {mdiEye, mdiTrashCan} from "@mdi/js";
import UserAvatar from "@/Components/UserAvatar.vue";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import BaseLevel from "@/Components/BaseLevel.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseIcon from "@/Components/BaseIcon.vue";
import {Link, useForm, usePage} from '@inertiajs/inertia-vue3'
import {notify} from "notiwind";

const props = defineProps({
    Users: Object,
    Query: Object,
    Role: {
        type: String,
        default: "Student"
    }
});
const id = ref(null);
const url = computed(() => {
    if (props.Role === 'User') return 'admin.users.edit';
    if (props.Role === 'Teacher') return 'admin.teachers.edit';
    return 'admin.students.edit';
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
    form['queries'] = props.Query;
    form.post(route('admin.users.destroy', id.value), {
        onSuccess: () => {
            notify({
                group: "notification",
                type: "success",
                title: "Success",
                text: usePage().props.flash?.success || `${props.Role} Moved to Trash Successfully`
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
        <p>Are you sure you want to Delete the {{ props.Role }}?</p>
    </CardBoxModal>


    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Avatar</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th/>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(user,key) in props.Users.data" :key="user.id">
            <td data-label="id">
                {{ (currentPageHuman - 1) * 15 + key + 1 }}
            </td>

            <td data-label="Avatar">
                <Link :href="route('admin.students.show',user.id)"
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
                        color="info"
                        :icon="mdiEye"
                        small
                        :data="{ prev_pages: props.Users.meta.current_page,...props.Query}"
                        :routeParams="user.id"
                        :routeName="url"
                    />
                    <BaseButton
                        color="danger"
                        :icon="mdiTrashCan"
                        small
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
