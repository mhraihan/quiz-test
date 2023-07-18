<style src="@vueform/multiselect/themes/default.css"></style>
<script setup>
import Multiselect from '@vueform/multiselect'
import {computed} from "vue";
import {useMainStore} from "@/Stores/main";
import {
    mdiAccount,
    mdiAsterisk,
    mdiFormTextboxPassword,
    mdiAccountEdit,
    mdiTimetable, mdiEmail, mdiPhone, mdiCity, mdiStateMachine, mdiPost, mdiGenderMaleFemale, mdiHomeMapMarker
} from "@mdi/js";

import countries from "@/Components/Country/countries.json";

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
import {isEmail, isIn, isMin, isRequired, isSame} from "intus/rules";


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
    },
    User: {
        type: Object,
        required: true
    }
});
const User = useValidatedForm({
    id: [props.User?.id, [isRequired()]],
    first_name: [props.User?.first_name, [isRequired()]],
    last_name: [props.User?.last_name, [isRequired()]],
    email: [props.User?.email, [isRequired(), isEmail()]],
    photo_path: [null],
    active: [true],
    state: [props.User?.state, [isRequired()]],
    birthday: [props.User?.birthday, [isRequired()]],
    city: [props.User?.city, [isRequired()]],
    phone: [props.User?.phone, [isRequired()]],
    country: [props.User?.country ?? "HK", [isRequired()]],
    address: [props.User?.address, [isRequired()]],
    postcode: [props.User?.postcode, [isRequired()]],
    gender: [props.User?.gender ?? "male", [isRequired(), isIn("male", "female")]],
    deleted_at: [null],
});
const gender = [
    {
        "label": "Male",
        "value": "male"
    },
    {
        "label": "Female",
        "value": "female"
    }
];

const passwordForm = useValidatedForm({
    current_password: [null, [isRequired()]],
    new_password: [null, [isRequired(), isMin(8)]],
    new_password_confirmation: [null, [isRequired(), isSame('new_password')]],
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
    useMainStore().updateSync(User, 'user.profile.update');
};


const updateSchool = () => {
    useMainStore().updateSync(schools, 'user.profile.school', 'POST');
};
const submitPass = async () => {
    try {
        await useMainStore().updateSync(passwordForm, 'user.profile.password', 'POST').then(() => {
            // Request was successful
            passwordForm.clearErrors();
            passwordForm.reset();
            passwordForm.current_password = null;
            passwordForm.new_password = null;
            passwordForm.new_password_confirmation = null;

        })
            .catch((error) => {
                // Request encountered an error
                // Handle the error as needed
                console.error(error);
            });

    } catch (error) {
        console.error(error);
    }
};


</script>

<template>
    <LayoutAuthenticated>
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiAccount" title="Profile" main/>

            <UserCard class="mb-6"/>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <CardBox is-form @submit.prevent="submitProfile">

                    <FormField
                        label="First Name"
                        label-for="first_name"
                        help="Required. First Name"
                        :error="User.errors.first_name"
                    >
                        <FormControl
                            :icon="mdiAccount"
                            id="first_name"
                            v-model="User.first_name"
                            name="first_name"
                            :error="User.errors.first_name"
                            type="text"
                            autocomplete="first_name"
                        />
                    </FormField>
                    <FormField
                        label="Last Name"
                        label-for="last_name"
                        help="Required. Last Name"
                        :error="User.errors.last_name"
                    >
                        <FormControl
                            :icon="mdiAccount"
                            v-model="User.last_name"
                            name="last_name"
                            id="last_name"
                            :error="User.errors.last_name"
                            autocomplete="last_name"
                            type="text"
                        />
                    </FormField>
                    <FormField
                        label="Email"
                        label-for="email"
                        help="Required. Email Address"
                        :error="User.errors.email"
                    >
                        <FormControl
                            :icon="mdiEmail"
                            v-model="User.email"
                            name="email"
                            id="email"
                            type="email"
                            autocomplete="email"
                            :error="User.errors.email"
                        />
                    </FormField>
                    <FormField
                        label="Phone Number"
                        label-for="phone"
                        help="Required. Phone Number"
                        :error="User.errors.phone"
                    >
                        <FormControl
                            id="phone"
                            v-model="User.phone"
                            name="phone"
                            :icon="mdiPhone"
                            autocomplete="phone Number"
                            type="tel"
                            :error="User.errors.phone"
                        />
                    </FormField>
                    <FormField
                        label="Gender"
                        label-for="gender"
                        help="Required. Gender"
                        :error="User.errors.gender"
                    >
                        <Multiselect
                            class="border-red-500"
                            :icon="mdiGenderMaleFemale"
                            v-model="User.gender"
                            name="gender"
                            id="gender"
                            placeholder="Please select your Gender"
                            :options="gender"></Multiselect>
                    </FormField>
                    <FormField
                        label="Birthday"
                        label-for="birthday"
                        help="Required. Birthday"
                        :error="User.errors.birthday"
                    >
                        <FormControl
                            id="birthday"
                            v-model="User.birthday"
                            name="birthday"
                            :icon="mdiTimetable"
                            autocomplete="birthday Number"
                            type="date"
                            :error="User.errors.birthday"
                        />
                    </FormField>
                    <FormField
                        label="City"
                        label-for="city"
                        help="Required. City name"
                        :error="User.errors.city"
                    >
                        <FormControl
                            :icon="mdiCity"
                            id="city"
                            v-model="User.city"
                            name="city"
                            :error="User.errors.city"
                            type="text"
                            autocomplete="city"
                        />
                    </FormField>
                    <FormField
                        label="State"
                        label-for="state"
                        help="Required. State"
                        :error="User.errors.state"
                    >
                        <FormControl
                            :icon="mdiStateMachine"
                            v-model="User.state"
                            name="state"
                            id="state"
                            :error="User.errors.state"
                            autocomplete="state"
                            type="text"
                        />
                    </FormField>
                    <FormField
                        label="Postcode"
                        label-for="postcode"
                        help="Required. Postcode"
                        :error="User.errors.postcode"
                    >
                        <FormControl
                            :icon="mdiPost"
                            id="postcode"
                            v-model="User.postcode"
                            name="postcode"
                            :error="User.errors.postcode"
                            type="text"
                            autocomplete="postcode"
                        />
                    </FormField>
                    <FormField
                        label="Choose your Country"
                        label-for="country"
                        help="Required. Please select your Country"
                        :error="User.errors.country"
                    >
                        <Multiselect
                            class="border-red-500"
                            name="country"
                            v-model="User.country"
                            placeholder="Please select your Country"
                            :options="countries"></Multiselect>
                    </FormField>
                    <FormField
                        label="Address"
                        label-for="address"
                        help="Required. Address"
                        :error="User.errors.address"
                    >
                        <FormControl
                            :icon="mdiHomeMapMarker"
                            id="address"
                            v-model="User.address"
                            name="address"
                            :error="User.errors.address"
                            type="text"
                            autocomplete="address"
                        />
                    </FormField>
                    <template #footer>
                        <BaseButtons>
                            <BaseButton color="info" type="submit" label="Update Profile"/>
                        </BaseButtons>
                    </template>
                </CardBox>

                <CardBox is-form @submit.prevent="submitPass" class="!block">
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
                            <BaseButton type="submit" color="info" label="Change Password"/>
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
