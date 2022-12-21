<style src="@vueform/multiselect/themes/default.css"></style>
<script setup>
import Multiselect from '@vueform/multiselect'
import CardBox from "@/Components/CardBox.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import Grid from "@/Components/Grid.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
// import FormFilePicker from "@/Components/FormFilePicker.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";

import ValidationError from "@/Components/ValidationError.vue";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import TrashedMessage from '@/Components/TrashedMessage.vue'
import CardBoxModal from "@/Components/CardBoxModal.vue";
import {ref} from "vue";
import {mdiAccount, mdiTimetable , mdiEmail, mdiFormTextboxPassword, mdiPhone, mdiCity, mdiStateMachine, mdiPost, mdiGenderMaleFemale, mdiHomeMapMarker} from "@mdi/js";
import countries from "@/Components/Country/countries.json";

const props = defineProps({
    Role: String,
    User: Object,
    buttonText: {
        type: String,
        default: "Create Student",
    }
});
const gender = [
    {
        "label" : "Male",
        "value": "male"
    }  ,
    {
        "label" : "Female",
        "value": "female"
    }
];
const emit = defineEmits(["destroy", "restore",]);
const isModalDangerActive = ref(false);
const destroyModal = () => {
    emit('destroy');
}

</script>

<template>

    <CardBox>
        <CardBoxModal
            v-model="isModalDangerActive"
            title="Please confirm"
            button="danger"
            has-cancel
            @confirm="destroyModal"
        >
            <p>Are you sure you want to Delete the {{ props.Role }}?</p>
        </CardBoxModal>
        <trashed-message v-if="User.deleted_at" @restore="$emit('restore')" class="mb-6" :restore="`Are you sure you want to restore this ${props.Role}?`"> This {{ props.Role }} has been
            deleted.
        </trashed-message>
        <ValidationError/>
        <Grid>
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
        </Grid>
        <Grid>
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
        </Grid>
        <Grid>
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
        </Grid>
        <BaseDivider/>
        <Grid>
            <FormField
                label="Password"
                label-for="password"
                help="Please enter new password"
                :error="User.errors.password"
            >
                <FormControl
                    v-model="User.password"
                    id="password"
                    :icon="mdiFormTextboxPassword"
                    type="password"
                    autocomplete="new-password"
                    :error="User.errors.password"
                />
            </FormField>
            <FormField
                label="Confirm Password"
                label-for="password_confirmation"
                help="Please confirm your password"
                :error="User.errors.password_confirmation || User.errors.password"
            >
                <FormControl
                    v-model="User.password_confirmation"
                    id="password_confirmation"
                    :icon="mdiFormTextboxPassword"
                    type="password"
                    autocomplete="new-password"
                    :error="User.errors.password_confirmation || User.errors.password"
                />
            </FormField>
        </Grid>


        <BaseDivider/>
        <Grid>
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
        </Grid>
        <Grid>
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
        </Grid>
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
        <BaseDivider/>
        <template #footer>
            <div class="flex">
                <BaseButtons class="mr-10" v-if="!User.deleted_at">
                    <BaseButton
                        color="info"
                        type="submit"
                        :label="buttonText"
                        :class="{ 'opacity-25': User.processing }"
                        :disabled="User.processing"
                    />
                </BaseButtons>

                <BaseButtons v-if="route().current('admin.students.edit')">
                    <BaseButton
                        @click="isModalDangerActive = true;"
                        color="danger"
                        type="button"
                        label="Delete"
                        :class="{ 'opacity-25': User.processing }"
                        :disabled="User.processing"
                    />
                </BaseButtons>
            </div>
        </template>
    </CardBox>
</template>
