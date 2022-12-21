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

const props = defineProps({
    Role: String,
});
const hasTable = true;

const User = useValidatedForm({
    first_name: ["",[isRequired()]],
    last_name: ["",[isRequired()]],
    email: ["",[isRequired(), isEmail()]],
    password: ["",[isRequired(), isMin(8)]],
    password_confirmation: ["",[isRequired(),isSame('password')]],
    photo_path:[null],
    active:[true],
    state: ["",[isRequired()]],
    birthday: ["",[isRequired()]],
    city: ["",[isRequired()]],
    phone: ["",[isRequired()]],
    country: ["HK",[isRequired()]],
    address: ["",[isRequired()]],
    postcode: ["",[isRequired()]],
    gender: ["male",[isRequired(), isIn("male", "female")]],
    deleted_at: [null],
    roles: [props.Role.toLowerCase()],
});
const createUser = () => {
    User
        .transform((data) => ({
            ...data,
        }))
        .post(route("admin.users.store"), {
            "onSuccess": () => {
                notify({
                    group: "notification",
                    type: "success",
                    title: "Success",
                    text: 'Student created successfully'
                }, 4000)
            },
            onError: () => {
                notify({
                    group: "notification",
                    type: "error",
                    title: "Error",
                    text: 'Something went wrong'
                }, 4000) // 4s
            }
        });
}
</script>

<template>
    <Head title="Create new Question"/>
    <LayoutAuthenticated>
        <SectionMain>
            <Breadcrumbs :href="route('admin.students.index')" title="Students" location="Create New Student"/>
            <SectionTitleLineWithButton
                :icon="mdiPencilPlus"
                title="Create new Student"
                main
            />

            <div class="grid grid-cols-1">
                <CardBox
                    is-form
                    @submit.prevent="createUser"
                    :hasTable="hasTable"
                >
                    <Form :User="User" :Role="props.Role"/>
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
