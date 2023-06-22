<script setup>
import CardBox from "@/Components/CardBox.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";

import ValidationError from "@/Components/ValidationError.vue";

import TrashedMessage from '@/Components/TrashedMessage.vue'
import CardBoxModal from "@/Components/CardBoxModal.vue";
import {ref} from "vue";
import {mdiAccount} from "@mdi/js";


const props = defineProps({
    School: Object,
    buttonText: {
        type: String,
        default: "Create School",
    }
});

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
            <p>Are you sure you want to Delete the School?</p>
        </CardBoxModal>
        <trashed-message v-if="School.deleted_at" @restore="$emit('restore')" class="mb-6" :restore="`Are you sure you want to restore this School?`"> This School has been
            deleted.
        </trashed-message>
        <ValidationError/>

            <FormField
                label="School Name"
                label-for="name"
                help="Required. Name"
                :error="School.errors.name"
            >
                <FormControl
                    :icon="mdiAccount"
                    id="first_name"
                    v-model="School.name"
                    name="first_name"
                    :error="School.errors.name"
                    type="text"
                    autocomplete="name"
                />
            </FormField>
            <FormField
                label="School Short Name"
                label-for="short_name"
                help="Required. Short Name"
                :error="School.errors.short_name"
            >
                <FormControl
                    :icon="mdiAccount"
                    v-model="School.short_name"
                    name="short_name"
                    id="short_name"
                    :error="School.errors.short_name"
                    autocomplete="short_name"
                    type="text"
                />
            </FormField>
        <template #footer>
            <div class="flex">
                <BaseButtons class="mr-10" v-if="!School.deleted_at">
                    <BaseButton
                        color="info"
                        type="submit"
                        :label="buttonText"
                        :class="{ 'opacity-25': School.processing }"
                        :disabled="School.processing"
                    />
                </BaseButtons>

                <BaseButtons v-if="route().current('admin.schools.edit')">
                    <BaseButton
                        @click="isModalDangerActive = true;"
                        color="danger"
                        type="button"
                        label="Delete"
                        :class="{ 'opacity-25': School.processing }"
                        :disabled="School.processing"
                    />
                </BaseButtons>
            </div>
        </template>
    </CardBox>
</template>
