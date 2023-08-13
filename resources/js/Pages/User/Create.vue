<script setup>
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import {mdiPencilPlus} from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import {Head} from "@inertiajs/inertia-vue3";
import useValidatedForm from "@/useValidatorForm";
import {isRequired, isIn, isMin, isEmail, isSame} from "intus/rules";
import Form from "./Form.vue";
import {useMainStore} from "@/Stores/main";
import {isAdmin, UserEnum} from "@/config";
import {generateRouterConfigByRole, generateUserFormConfig} from "@/Pages/User/userFormConfig";
import {computed} from "vue";

const props = defineProps({
    Role: String,
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
    ...generateUserFormConfig(props),
});
const createUser = () => {
    useMainStore().createSync(User, 'admin.users.store');
}

const routerConfig = computed(() => {
    return generateRouterConfigByRole(props.Role); // Use the utility function
});
</script>
<template>
    <Head title="Create new Question"/>
    <LayoutAuthenticated>
        <SectionMain>
            <Breadcrumbs class="capitalize" :href="route(routerConfig.route)" :title="props.Role" :location="routerConfig.title"/>
            <SectionTitleLineWithButton
                :icon="mdiPencilPlus"
                :title="routerConfig.title"
                main
            />

            <div class="grid grid-cols-1">
                <CardBox
                    is-form
                    @submit.prevent="createUser"
                    :hasTable="hasTable"
                >
                    <Form :User="User" :Schools="props.Schools" :Teachers="props.Teachers"
                          :current_school="props.current_school"
                          :current_teacher="props.current_teacher" :Role="props.Role"
                          method="create"
                    />
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
