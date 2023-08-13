<script setup>
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import {mdiPencilPlus} from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import {Head} from "@inertiajs/inertia-vue3";
import useValidatedForm from "@/useValidatorForm";
import Form from "./Form.vue";
import {computed} from "vue";
import {useMainStore} from "@/Stores/main.js";
import {generateUserFormConfig, getUrlByRole} from "@/Pages/User/userFormConfig";
import {UserEnum} from "@/config";

const props = defineProps({
    Role: String,
    User: Object,
    Schools: {
        type: Object,
        default: []
    },
    Teachers: {
        type: Object,
        default: []
    },
    current_school: {
        type: Number,
        default: null
    },
    current_teacher: {
        type: Number,
        default: null
    },
    how_many_students: {
        type: Number,
        default: 0
    },
});
const hasTable = true;

const User = useValidatedForm({
    id: [props.User.id],
    ...generateUserFormConfig(props),
    _method: ["put"],
});
const url = computed(() => {
    return getUrlByRole(props.Role); // Use the utility function
});
const updateUser = () => {
    if (!User.password && !User.password_confirmation) {
        delete User.password_confirmation;
        delete User.password;
    }
    useMainStore().updateSync(User, 'admin.users.update');
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
            <Breadcrumbs class="capitalize" :href="route(url)" :title="props.Role" :location="`Update ${props.Role}`"/>
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
                    <Form :User="User" :Schools="props.Schools" :Teachers="props.Teachers"
                          :current_school="props.current_school"
                          :current_teacher="props.current_teacher" :Role="props.Role"
                          :how_many_students="props.how_many_students"
                          @destroy="destroyUser"
                          @restore="restoreUser"
                          :buttonText="`Update ${props.Role}`"
                          method="update"

                    />
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
