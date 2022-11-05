<script setup>
import CardBox from "@/Components/CardBox.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import FormFilePicker from "@/Components/FormFilePicker.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import FormCheckRadioGroup from "@/Components/FormCheckRadioGroup.vue";
import ValidationError from "@/Components/ValidationError.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import TrashedMessage from '@/Components/TrashedMessage.vue'
import CardBoxModal from "@/Components/CardBoxModal.vue";
import {ref} from "vue";

const props = defineProps({
    Categories: Object,
    questions: Object,
    image: String,
    buttonText: {
        type: String,
        default: "Create Question",
    }
});
const emit = defineEmits(["destroy","restore",'removeImage']);
const isModalDangerActive = ref(false);
const destroyModal = () => {
    console.log('destroy');
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
            <p>Are you sure you want to Delete the question?</p>
        </CardBoxModal>
        <trashed-message v-if="questions.deleted_at" @restore="$emit('restore')" class="mb-6" > This Question has been deleted. </trashed-message>
        <ValidationError />
        <FormField
            label="Question Name"
            help="Required. Question Name"
            :error="questions.errors.title"
        >
            <FormControl
                v-model="questions.title"
                name="title"
                :error="questions.errors.title"
            />
        </FormField>

        <FormField
            label="Question Details"
            help="Required. Question Details"
            :error="questions.errors.details"
        >
            <div class="flex flex-col min-height">
                <QuillEditor
                    toolbar="essential"
                    contentType="html"
                    theme="snow"
                    v-model:content="questions.details"
                    name="details"
                />
            </div>
        </FormField>
        <BaseDivider />
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <FormField
                v-for="option in Object.keys(questions.options)"
                :label="`Option ${option.toUpperCase()}`"
                :help="`Required. Question option ${option}`"
                :error="questions.errors[`options.${option.toLowerCase()}`]"
            >
                <FormControl
                    v-model="questions.options[option]"
                    :name="option"
                    :error="questions.errors[`options.${option.toLowerCase()}`]"
                />
            </FormField>
        </div>
        <BaseDivider />

        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            <FormField
                label="Question category"
                help="Required. Question category"
                :error="questions.errors.category_id"
            >
                <FormControl
                    :uppercase="'capitalize'"
                    v-model="questions.category_id"
                    name="category"
                    type="select"
                    placeholder="please select the category"
                    required
                    :options="props.Categories"
                    :error="questions.errors.category_id"
                />
            </FormField>
            <FormField
                label="Correct Answer"
                help="Required. Question correct answer"
                :error="questions.errors.correct_answer"
            >
                <FormControl
                    :uppercase="'uppercase'"
                    v-model="questions.correct_answer"
                    name="Correct Answer"
                    type="select"
                    placeholder="please select the correct answer"
                    required
                    :options="Object.keys(questions.options)"
                    :error="questions.errors.correct_answer"
                />
            </FormField>
        </div>

        <BaseDivider />
        <CardBox v-if="image" class="mb-2">
           <div class="relative">
               <img :src="image" alt="" srcset="" />
               <BaseButtons class="absolute top-0 right-0">
                   <BaseButton
                       @click="$emit('removeImage')"
                       color="danger"
                       type="button"
                       label="Remove"
                   />
               </BaseButtons>
           </div>

        </CardBox>
        <progress v-if="questions.progress" :value="questions.progress.percentage" max="100">
            {{ questions.progress.percentage }}%
        </progress>
        <FormField label="Question Image (optional)" help="" :error="questions.errors.image">
            <FormFilePicker v-model="questions.image" label="Upload" :error="questions.errors.image" />
        </FormField>
        <BaseDivider />

        <FormField
            label="Explain"
            help="Required. Question explain"
            :error="questions.errors.explain"
        >
            <div class="flex flex-col">
                <QuillEditor
                    toolbar="full"
                    contentType="html"
                    theme="snow"
                    v-model:content="questions.explain"
                    name="explain"
                />
            </div>
        </FormField>

        <template #footer >
            <div class="flex">
                <BaseButtons class="mr-10" v-if="!questions.deleted_at">
                    <BaseButton
                        color="info"
                        type="submit"
                        :label="buttonText"
                        :disabled="questions.processing"
                    />
                </BaseButtons>

                <BaseButtons v-if="route().current('admin.questions.edit')">
                    <BaseButton
                        @click="isModalDangerActive = true;"
                        color="danger"
                        type="button"
                        label="Delete"
                        :disabled="questions.processing"
                    />
                </BaseButtons>
            </div>
        </template>
    </CardBox>
</template>

