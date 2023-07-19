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
    Topic: Object,
    buttonText: {
        type: String,
        default: "Create Topic",
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
            <p>Are you sure you want to Delete the Topic?</p>
        </CardBoxModal>
        <trashed-message v-if="Topic.deleted_at" @restore="$emit('restore')" class="mb-6" :restore="`Are you sure you want to restore this Topic?`"> This Topic has been
            deleted.
        </trashed-message>
        <ValidationError/>

            <FormField
                label="Topic Name"
                label-for="name"
                help="Required. Name"
                :error="Topic.errors.name"
            >
                <FormControl
                    :icon="mdiAccount"
                    id="title"
                    v-model="Topic.title"
                    name="title"
                    :error="Topic.errors.title"
                    type="text"
                    autocomplete="name"
                />
            </FormField>
        <template #footer>
            <div class="flex">
                <BaseButtons class="mr-10" v-if="!Topic.deleted_at">
                    <BaseButton
                        color="info"
                        type="submit"
                        :label="buttonText"
                        :class="{ 'opacity-25': Topic.processing }"
                        :disabled="Topic.processing"
                    />
                </BaseButtons>

                <BaseButtons v-if="route().current('admin.topics.edit')">
                    <BaseButton
                        @click="isModalDangerActive = true;"
                        color="danger"
                        type="button"
                        label="Delete"
                        :class="{ 'opacity-25': Topic.processing }"
                        :disabled="Topic.processing"
                    />
                </BaseButtons>
            </div>
        </template>
    </CardBox>
</template>
