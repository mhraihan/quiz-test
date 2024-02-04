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
import {QuillEditor} from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import TrashedMessage from '@/Components/TrashedMessage.vue'
import CardBoxModal from "@/Components/CardBoxModal.vue";
import {ref} from "vue"
import SectionMain from "@/Components/SectionMain.vue";
import {isAdminOrTeacher} from "@/config";

const props = defineProps({
  Categories: Object,
  Topics: Object,
  questions: Object,
  image: String,
  buttonText: {
    type: String,
    default: "Create Question",
  }
});
const editorOptions = ref({
  modules: {
    toolbar: [
      ['bold',{'script': 'sub'}, {'script': 'super'}],
    ],
  },
  theme: 'snow',
  // katex: katex,
});
/*
const handlePaste = (fieldName, event, option = null) => {
    event.preventDefault(); // Prevent default paste behavior
    // Get the plain text from the clipboard
    const text = (event.clipboardData || window.clipboardData).getData('text/plain');

    // Append the filtered plain text to the existing content
    if (option) {
        props.questions[option][fieldName] += text;
    } else {
        props.questions[fieldName] += text;
    }
};
*/


const emit = defineEmits(["destroy", "restore", 'removeImage', 'duplicateQuestion']);
const isModalDangerActive = ref(false);
const isModalDuplicateActive = ref(false);
const destroyModal = () => {
  emit('destroy');
}
const duplicateQuestionModal = () => {
  emit('duplicateQuestion');
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
    <CardBoxModal
        v-model="isModalDuplicateActive"
        title="Please confirm"
        button="info"
        has-cancel
        @confirm="duplicateQuestionModal"
    >
      <p>Are you sure you want to Duplicate the question?</p>
    </CardBoxModal>
    <trashed-message v-if="questions.deleted_at" @restore="$emit('restore')" :role="isAdminOrTeacher" class="mb-6"> This Question has been
      deleted.
    </trashed-message>
    <ValidationError/>
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-2">
      <FormField
          label="How many Options?"
          label-for="question_options"
          help="Required. Number of options"
          :error="questions.errors.que"
      >
        <FormCheckRadioGroup
            v-model="questions.question_options"
            id="question_options"
            type="radio"
            name="question_options"
            :error="questions.errors.question_options"
            :value="questions.question_options"
            :options="{
                            '2': 'Option 2',
                            '3': 'Option 3',
                            '4': 'Option 4'
                        }"
        />
      </FormField>
      <FormField
          label="Question topic"
          help="Required. Question topic"
          :error="questions.errors.topic_id"
      >
        <FormControl
            :uppercase="'capitalize'"
            v-model="questions.topic_id"
            name="topic"
            type="select"
            placeholder="please select the topic"
            required
            :options="props.Topics"
            :error="questions.errors.topic_id"
        />
      </FormField>
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

    <BaseDivider/>

    <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
      <SectionMain class="!p-0">
        <h2 class="my-3 font-bold text-2xl uppercase">Question (English)</h2>

        <FormField
            label="Question Name"
            help="Required. Question Name"
            :error="questions.errors.title"
        >
          <div class="flex flex-col ql-title">
            <QuillEditor

                :options="editorOptions"
                contentType="html"
                v-model:content="questions.title"
                name="title"
            />
          </div>
        </FormField>

        <FormField
            label="Question Details"
            help="Required. Question Details"
            :error="questions.errors.details"
        >
          <div class="flex flex-col min-height">
            <QuillEditor
                toolbar="full"
                contentType="html"
                theme="snow"
                v-model:content="questions.details"
                name="details"
            />
          </div>
        </FormField>
        <BaseDivider/>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-1 ql-options">
          <FormField
              v-for="option in Object.keys(questions.options)"
              :label="`Option ${option.toUpperCase()}`"
              :help="`Required. Question option ${option}`"
              :error="questions.errors[`options.${option.toLowerCase()}`]"
          >
            <QuillEditor

                :options="editorOptions"
                contentType="html"
                v-model:content="questions.options[option]"
                :name="option"
            />
          </FormField>
        </div>
        <BaseDivider/>

        <FormField
            label="Question Explaination (optional)"
            help="Please explain about the question"
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
      </SectionMain>
      <SectionMain class="!p-0">
        <h2 class="my-3 font-bold text-2xl uppercase">Question (Chinese )</h2>

        <FormField
            label="Question Name"
            help="Required. Question Name"
            :error="questions.errors.title_two"
        >
          <div class="flex flex-col ql-title">
            <QuillEditor
                contentType="html"
                :options="editorOptions"
                v-model:content="questions.title_two"
                name="title_two"

            />
          </div>
        </FormField>

        <FormField
            label="Question Details"
            help="Required. Question Details"
            :error="questions.errors.details_two"
        >
          <div class="flex flex-col min-height">
            <QuillEditor
                toolbar="full"
                contentType="html"
                theme="snow"
                v-model:content="questions.details_two"
                name="details_two"
            />
          </div>
        </FormField>
        <BaseDivider/>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-1 ql-options">
          <FormField
              v-for="option in Object.keys(questions.options_two)"
              :label="`Option ${option.toUpperCase()}`"
              :help="`Required. Question option ${option}`"
              :error="questions.errors[`options.${option.toLowerCase()}`]"
          >
            <QuillEditor

                :options="editorOptions"
                contentType="html"
                v-model:content="questions.options_two[option]"
                :name="option"
            />
          </FormField>
        </div>
        <BaseDivider/>

        <FormField
            label="Question Explaination (optional)"
            help="Please explain about the question"
            :error="questions.errors.explain_two"
        >
          <div class="flex flex-col">
            <QuillEditor
                toolbar="full"
                contentType="html"
                theme="snow"
                v-model:content="questions.explain_two"
                name="explain_two"
            />
          </div>
        </FormField>
      </SectionMain>
    </div>
    <BaseDivider/>


    <CardBox v-if="image" class="mb-2">
      <div class="relative">
        <img :src="image" alt="" srcset=""/>
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
      <FormFilePicker v-model="questions.image" label="Upload" :error="questions.errors.image"/>
    </FormField>
    <BaseDivider/>

    <template #footer>
      <div class="flex mb-2 md:mb-0 flex-col md:flex-row justify-between">
        <div class="flex mb-2 md:mb-0 flex-col md:flex-row">
          <BaseButtons class="mr-0 md:mr-10 mb-2 md:mb-0" v-if="!questions.deleted_at">
            <BaseButton
                class="w-full md:w-auto"
                color="info"
                type="submit"
                :label="buttonText"
                :disabled="questions.processing"
            />
          </BaseButtons>

          <BaseButtons class="mr-0 md:mr-10 mb-2 md:mb-0" v-if="route().current('admin.questions.edit')">
            <BaseButton
                class="w-full md:w-auto"
                @click="isModalDangerActive = true;"
                color="danger"
                type="button"
                label="Delete"
                :disabled="questions.processing"
            />
          </BaseButtons>
        </div>
        <BaseButtons
            v-if="!questions.deleted_at && route().current() === 'admin.questions.edit'">
          <BaseButton
              class="w-full md:w-auto"
              color="info"
              type="button"
               @click="isModalDuplicateActive = true;"
              label="Duplicate Question"
              :disabled="questions.processing"
          />
        </BaseButtons>
      </div>
    </template>
  </CardBox>
</template>

