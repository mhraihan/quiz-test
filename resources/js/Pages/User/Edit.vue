<script setup>
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import {mdiPencilPlus} from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import {Head} from "@inertiajs/inertia-vue3";
import {notify} from "notiwind"
import useValidatedForm from "@/useValidatorForm";
import {isRequired, isIn, isMin, isEmail, isSame} from "intus/rules";
import Form from "./Form.vue";
import {computed} from "vue";
import { useMainStore } from "@/Stores/main.js";
const props = defineProps({
    Role: String,
    User: Object,
});
const hasTable = true;

const User = useValidatedForm({
    id: [props.User.id],
    first_name: [props.User.first_name || null, [isRequired()]],
    last_name: [props.User.last_name || null, [isRequired()]],
    email: [props.User.email || null, [isRequired(), isEmail()]],
    password: [null, [isMin(8)]],
    password_confirmation: [null, [isSame('password')]],
    photo_path: [null],
    active: [true],
    state: [props.User.state || null, [isRequired()]],
    birthday: [new Date(props.User.birthday).toISOString().slice(0, 10) || null, [isRequired()]],
    city: [props.User.city || null, [isRequired()]],
    phone: [props.User.phone || null, [isRequired()]],
    country: [props.User.country || "HK", [isRequired()]],
    address: [props.User.address || null, [isRequired()]],
    postcode: [props.User.postcode || null, [isRequired()]],
    gender: [props.User.gender || "male", [isRequired(), isIn("male", "female")]],
    deleted_at: [props.User.deleted_at || null],
    roles: [props.Role.toLowerCase()],
    _method: ["put"],
});
const url = computed(() => {
    if (props.Role === 'User')  return 'admin.users.index';
    if (props.Role === 'Teacher')  return 'admin.teachers.index';
    return 'admin.students.index';
});
const updateUser = () => {
    if (!User.password && !User.password_confirmation) {
        delete User.password_confirmation;
        delete User.password;
    }
    useMainStore().update(User, 'admin.users.update');
}
const destroyUser = () => {
   useMainStore().destroy(User, 'admin.users.destroy');
}
const restoreUser = () => {
    useMainStore().restore(User, 'admin.users.restore');
}
</script>

<template>
    <Head title="Create new Question"/>
    <LayoutAuthenticated>
        <SectionMain>
            <Breadcrumbs :href="route(url)" :title="props.Role" :location="`Update ${props.Role}`" />
            <SectionTitleLineWithButton
                :icon="mdiPencilPlus"
                :title="`Update ${props.Role}`"
                main
            />

            <div class="grid grid-cols-1">
                <CardBox
                    is-form
                    @submit.prevent="updateUser"
                    :hasTable="hasTable"
                >
                    <Form :User="User" :Role="props.Role" @destroy="destroyUser" @restore="restoreUser"
                          :buttonText="`Update ${props.Role}`"/>
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
