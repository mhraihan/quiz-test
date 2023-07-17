<style src="@vueform/multiselect/themes/default.css"></style>
<script setup>
import Multiselect from '@vueform/multiselect'
import {computed, reactive, watch} from "vue";
import {useMainStore} from "@/Stores/main";
import {
    mdiAccount,
    mdiMail,
    mdiAsterisk,
    mdiFormTextboxPassword,
    mdiAccountEdit
} from "@mdi/js";
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from '@inertiajs/inertia'
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import UserCard from "@/Components/UserCard.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import useValidatedForm from "@/useValidatorForm";
import {isRequired} from "intus/rules";

const mainStore = useMainStore();

import {isStudent, isTeacher} from "@/config";

const props = defineProps({
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
    }
});
const profileForm = reactive({
    name: usePage().props.value.auth.user.name || mainStore.userName,
    email: usePage().props.value.auth.user.email || mainStore.userEmail,
});

const passwordForm = useValidatedForm({
    current_password: [null, [isRequired()]],
    new_password: [null, [isRequired()]],
    new_password_confirmation: [null, [isRequired()]],
})
const disabled = computed(() => props.how_many_students > 0);
const schools = useValidatedForm({
    school_id: [props.current_school ?? null, [isRequired()]],
    teacher_id: [props.current_teacher ?? null, isTeacher() ? [] : [isRequired()]],
})

const handleSchoolChange = (selectedOptions) => {
    if (isTeacher()) return;
    Inertia.visit(route('user.profile'), {
        method: 'get',
        data: {
            school_id: selectedOptions,
            teacher_id: props.current_teacher
        },
        replace: false,
        preserveState: true,
        preserveScroll: true,
        only: ['Teachers', 'current_teacher'],
        onSuccess: () => {
            schools.school_id = selectedOptions;
            schools.teacher_id = null;
        },
        onError: () => {
        },
        onFinish: () => {
            history.pushState(null, null, window.location.pathname);
        },
    })

}
const submitProfile = () => {
    mainStore.setUser(profileForm);
};


const updateSchool = () => {
    useMainStore().update(schools, 'user.profile.school', 'POST');
};
const submitPass = () => {
    useMainStore().update(passwordForm, 'user.profile.password', 'POST');

};


</script>

<template>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiAccount" title="Profile" main/>

            <UserCard class="mb-6"/>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <CardBox is-form @submit.prevent="submitProfile">

                    <FormField label="Name" help="Required. Your name">
                        <FormControl
                            v-model="profileForm.name"
                            :icon="mdiAccount"
                            name="username"
                            required
                            autocomplete="username"
                        />
                    </FormField>
                    <FormField label="E-mail" help="Required. Your e-mail">
                        <FormControl
                            v-model="profileForm.email"
                            :icon="mdiMail"
                            type="email"
                            name="email"
                            required
                            autocomplete="email"
                        />
                    </FormField>

                    <template #footer>
                        <BaseButtons>
                            <BaseButton color="info" type="submit" label="Submit"/>
                        </BaseButtons>
                    </template>
                </CardBox>

                <CardBox is-form @submit.prevent="submitPass">
                    <FormField
                        label="Current password"
                        help="Required. Your current password"
                        :error="passwordForm.errors.current_password"
                    >
                        <FormControl
                            v-model="passwordForm.current_password"
                            :icon="mdiAsterisk"
                            name="current_password"
                            type="password"
                            autocomplete="current-password"
                            :error="passwordForm.errors.current_password"
                        />
                    </FormField>

                    <BaseDivider/>

                    <FormField label="New password" help="Required. New password" :error="passwordForm.errors.password">
                        <FormControl
                            v-model="passwordForm.new_password"
                            :icon="mdiFormTextboxPassword"
                            name="new_password"
                            type="password"
                            autocomplete="new-password"
                            :error="passwordForm.errors.new_password"
                        />
                    </FormField>

                    <FormField
                        label="Confirm password"
                        help="Required. New password one more time"
                        :error="passwordForm.errors.new_password_confirmation"
                    >
                        <FormControl
                            v-model="passwordForm.new_password_confirmation"
                            :icon="mdiFormTextboxPassword"
                            name="new_password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            :error="passwordForm.errors.new_password_confirmation"
                        />
                    </FormField>

                    <template #footer>
                        <BaseButtons>
                            <BaseButton type="submit" color="info" label="Submit"/>
                        </BaseButtons>
                    </template>
                </CardBox>
            </div>

            <div v-if="isTeacher() || isStudent()" id="update-school" class="grid grid-cols-1 mt-6">
                <CardBox is-form @submit.prevent="updateSchool">
                    <SectionTitleLineWithButton
                        :icon="mdiAccountEdit"
                        title="Update School"

                    />
                    <FormField
                        label="Choose a School"
                        help="Required. Please select the School"
                        :error="schools.errors.school_id"

                    >
                        <Multiselect
                            :disabled="disabled"
                            @change="handleSchoolChange"
                            :uppercase="'capitalize'"
                            v-model="schools.school_id"
                            name="school"
                            type="select"
                            placeholder="Choose a school"

                            :options="props.Schools"
                        />
                    </FormField>

                    <FormField
                        v-if="isStudent()"
                        label="Choose a Teacher"
                        help="Required. Please select the Teacher"
                        :error="schools.errors.teacher_id"

                    >
                        <Multiselect
                            :key="schools.teacher_id"
                            :uppercase="'capitalize'"
                            v-model="schools.teacher_id"
                            name="teacher"
                            type="select"
                            placeholder="Choose a teacher"

                            :options="props.Teachers"
                        />
                    </FormField>
                    <template #footer>
                        <BaseButtons>
                            <BaseButton :disabled="disabled" color="info" type="submit" label="Update"/>
                        </BaseButtons>
                        <div class="mt-2 text-lg text-red-500" v-if="props.how_many_students">
                            You have {{ props.how_many_students }} students. That why you cannot update the school.
                        </div>
                    </template>
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
