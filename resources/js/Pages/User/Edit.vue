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
import {isRequired, isIn, isMin, isImage, isEmail, isSame} from "intus/rules";
import Form from "./Form.vue";

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
    roles: ['student'],
    _method: ["put"],
});
const updateUser = () => {
    if (!User.password && !User.password_confirmation){
        delete User.password_confirmation;
        delete User.password;
    }
    User
        .transform((data) => ({
            ...data,
        }))
        .post(route("admin.users.update", props.User.id), {
            "onSuccess": () => {
                notify({
                    group: "notification",
                    type: "success",
                    title: "Success",
                    text: 'Student Profile updated successfully'
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
const destroyUser = () => {
    User.delete(route('admin.users.destroy', User.id), {
        onSuccess: () => {
            User.deleted_at = User.deleted_at || new Date();
            notify({
                group: "notification",
                type: "success",
                title: "Success"
            }, 4000) // 4s
            User.clearErrors();
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
const restorUser = () => {
    User.put(route('admin.users.restore', User.id), {
        onSuccess: () => {
            User.deleted_at = null;
            notify({
                group: "notification",
                type: "success",
                title: "Success"
            }, 4000) // 4s
        },
        onError: () => {
            notify({
                group: "notification",
                type: "error",
                title: "Error",
                text: 'Something went wrong'
            }, 4000) // 4s
        }
    })

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
                    @submit.prevent="updateUser"
                    :hasTable="hasTable"
                >
                    <Form :User="User" :Role="props.Role"  @destroy="destroyUser" buttonText="Update Student"/>
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
