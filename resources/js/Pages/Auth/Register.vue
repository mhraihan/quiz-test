<script setup>
import {useForm,  Head} from "@inertiajs/inertia-vue3";
import {mdiAccount, mdiEmail, mdiFormTextboxPassword} from "@mdi/js";
import LayoutGuest from "@/Layouts/LayoutGuest.vue";
import SectionFullScreen from "@/Components/SectionFullScreen.vue";
import CardBox from "@/Components/CardBox.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import FormValidationErrors from "@/Components/FormValidationErrors.vue";
import Expired from "@/Components/Expired.vue";
import {UserEnum} from "@/config";
const { Role } = defineProps({
    Role: {
        type: String,
        default: UserEnum.STUDENT
    }
});
const form = useForm({
    first_name: "",
    last_name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: [],
    roles: Role,
});

const submit = () => {
    form
        .transform((data) => ({
            ...data,
            terms: form.terms && form.terms.length,
        }))
        .post(route("register"), {
            onFinish: () => form.reset("password", "password_confirmation"),
        });
};
</script>
<template>
    <LayoutGuest>
        <Head title="Register"/>
        <SectionFullScreen v-slot="{ cardClass }" bg="purplePink">
            <CardBox
                :class="cardClass"
                class="my-24"
                is-form
                @submit.prevent="submit"
            >
                <Expired/>
                <FormValidationErrors/>
                <FormField label="First Name" label-for="first_name" help="Please enter your first name">
                    <FormControl
                        v-model="form.first_name"
                        id="first_name"
                        :icon="mdiAccount"
                        autocomplete="first_name"
                        type="text"
                        required
                    />
                </FormField>
                <FormField label="Last Name" label-for="last_name" help="Please enter your last name">
                    <FormControl
                        v-model="form.last_name"
                        id="last_name"
                        :icon="mdiAccount"
                        autocomplete="last_name"
                        type="text"
                        required
                    />
                </FormField>
                <FormField
                    label="Email"
                    label-for="email"
                    help="Please enter your email"
                >
                    <FormControl
                        v-model="form.email"
                        id="email"
                        :icon="mdiEmail"
                        autocomplete="email"
                        type="email"
                        required
                    />
                </FormField>
                <FormField
                    label="Password"
                    label-for="password"
                    help="Please enter new password"
                >
                    <FormControl
                        v-model="form.password"
                        id="password"
                        :icon="mdiFormTextboxPassword"
                        type="password"
                        autocomplete="new-password"
                        required
                    />
                </FormField>
                <FormField
                    label="Confirm Password"
                    label-for="password_confirmation"
                    help="Please confirm your password"
                >
                    <FormControl
                        v-model="form.password_confirmation"
                        id="password_confirmation"
                        :icon="mdiFormTextboxPassword"
                        type="password"
                        autocomplete="new-password"
                        required
                    />
                </FormField>
                <BaseDivider/>
                <BaseButtons>
                    <BaseButton
                        type="submit"
                        color="info"
                        label="Register"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    />
                    <BaseButton route-name="login" color="info" outline label="Login"/>
                </BaseButtons>
            </CardBox>
        </SectionFullScreen>
    </LayoutGuest>
</template>
