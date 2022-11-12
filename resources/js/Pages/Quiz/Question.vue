<script setup>
import CardBox from "@/Components/CardBox.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";

import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import FormCheckRadioGroup from "@/Components/FormCheckRadioGroup.vue";
import FormField from "@/Components/FormField.vue";
import {ref, reactive} from "vue";
import {notify} from "notiwind"

const emit = defineEmits(["submit"]);

const props = defineProps({
    questions: Object,
    start_time: String,
    exam: Object,
})
const step = ref(0);
const start_time = ref(new Date(props.start_time));
const stop_time = ref(null);

const quiz = reactive({
    questions: props.questions
})
const next = (key) => {
    if (!quiz.questions[key].answer) {
        notify({
            group: "notification",
            type: "error",
            title: "Error",
            text: 'Please select Question Answer'
        }, 4000) // 4s
        return false;
    }
    if (step.value < quiz.questions.length - 1) {
        step.value++;
    }
}
const submit = () => {
    stop_time.value = new Date();
    emit('submit',{
        class_id: null,
        start_time: start_time.value.valueOf(),
        stop_time: stop_time.value.valueOf(),
        total_questions: quiz.questions.length,
        complete: true,
        questions_answered: quiz.questions.map(q => {
            return {
                id: q.id,
                answer: q.answer
            }
        })
    })
}
</script>

<template>
    <CardBox v-if="quiz.questions.length">

        <!-- Start of quiz box -->
        <div class="px-4 py-5 sm:px-6 ">
            <div class="flex max-w-auto justify-end">

                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    <span class="text-gray-400 font-extrabold p-1">Quiz Progress </span>
                    <span
                        class="font-bold p-3 leading-loose bg-blue-500 text-white rounded-full">{{ step + 1 }}/{{ quiz.questions.length }}</span>
                </p>
            </div>
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
            <CardBox is-form class="max-w-2xl mx-auto">
                <div v-for="(question,key) in quiz.questions" :key="question.id">
                    <div class="px-4 py-5 sm:px-6" v-if="step===key">
                        <div class="text-lg leading-6 mb-2 font-medium text-gray-900">
                            <h3>
                                <span class="mr-2 font-extrabold"> {{ key + 1 }}</span>
                                <span>{{ question.title }}</span>
                            </h3>

                            <div class="block text-xs">
                                <div class="p-1" id="headingOne">
                                    <button
                                        class="underline text-blue-500 hover:text-blue-700 focus:outline-none text-xs px-3"
                                        type="button">
                                        Question Details
                                    </button>
                                </div>
                                <div class="block p-2 bg-green-100 text-xs" style="display: none;">
                                    {{ question.details }}
                                </div>
                            </div>
                        </div>
                        <FormField>
                            <FormCheckRadioGroup
                                :isColumn="true"
                                v-model="question.answer"
                                name="answer"
                                type="radio"
                                componentClass="px-3 py-3 m-3 mr-0 text-gray-800 rounded-lg border-2 border-gray-300 text-sm"
                                :options="question.options"
                            />
                        </FormField>
                    </div>
                </div>

                <template #footer>
                    <div class="flex items-center justify-end mt-4">
                        <div class="flex">
                            <BaseButtons class="block w-full">
                                <BaseButton
                                    v-if="step < quiz.questions.length - 1"
                                    color="info"
                                    type="button"
                                    label="Next Quiz"
                                    class="block w-full"
                                    @click="next(step)"
                                    :disabled="!quiz.questions[step].answer"
                                />
                                <BaseButton
                                    v-else
                                    color="info"
                                    type="button"
                                    label="Show Result"
                                    class="block w-full"
                                    @click="submit()"
                                    :disabled="!quiz.questions[step].answer || quiz.processing"
                                />
                            </BaseButtons>
                        </div>
                    </div>
                </template>
            </CardBox>
        </div>
        <!-- end of quiz box -->
    </CardBox>
    <CardBox v-else>
        <CardBoxComponentEmpty text="No Question found." />
        <div class="flex justify-center">
            <BaseButtons class="justify-center">
                <BaseButton
                    color="info"
                    type="button"
                    label="Back to Quiz"
                    class="block w-full"
                    @click="$emit('backToQuiz')"
                />
            </BaseButtons>
        </div>
    </CardBox>
</template>


