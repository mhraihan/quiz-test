<script setup>
import { mdiPencilPlus } from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";

import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import Form from "./Form.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import { computed, ref, watch } from "vue";
import { notify } from "notiwind";
import {
    createFormData,
    getQuestionOptions,
} from "@/Pages/Question/optionsUtils";
import { handleQuestionSubmit } from "@/Pages/Question/useQuestionValidator";

const props = defineProps({
    Categories: Object,
    Topics: Object,
    Question: Object,
    image: String,
});

const hasTable = true;
const questions = useForm(createFormData(props));

// Computed property for question_options
const questionOptionsValue = computed(() => {
    return Object.keys(questions.options).length.toString();
});

// Set the initial value of question_options based on options length
questions.question_options = questionOptionsValue.value;
watch(
    () => questions.question_options,
    (newValue) => {
        questions.options = getQuestionOptions(questions.options, newValue);
        questions.options_two = getQuestionOptions(
            questions.options_two,
            newValue
        );
    }
);
const link = computed(() =>
    questions.deleted_at ? "admin.questions.trash" : "admin.questions.index"
);

const removeQuestionImage = ref(false);
const image = ref(props.image);

watch(
    () => questions.image,
    () => {
        if (questions.image) {
            image.value = URL.createObjectURL(questions.image);
        }
    }
);
const removeImage = () => {
    image.value = null;
    removeQuestionImage.value = true;
    questions.image = null;
};
const updateQuestion = () => {
    if (!questions.image && !removeQuestionImage.value) {
        delete questions.image;
    }
    questions.clearErrors();
    handleQuestionSubmit(
        questions,
        route("admin.questions.update", props.Question.id),
        {
            onSuccess: () => {
                questions.image = null;
                notify(
                    {
                        group: "notification",
                        type: "success",
                        title: "Success",
                    },
                    4000
                ); // 4s
                removeQuestionImage.value = false;
            },
            onError: () => {
                notify(
                    {
                        group: "notification",
                        type: "error",
                        title: "Error",
                        text: "Question Updated Failed!",
                    },
                    4000
                ); // 4s
            },
        }
    );
};

const destroyQuestion = () => {
    questions["_method"] = "delete";
    questions.post(route("admin.questions.destroy", questions.id), {
        onSuccess: () => {
            questions.deleted_at = questions.deleted_at || new Date();
            notify(
                {
                    group: "notification",
                    type: "success",
                    title: "Success",
                },
                4000
            ); // 4s
            questions.clearErrors();
        },
        onError: () => {
            notify(
                {
                    group: "notification",
                    type: "error",
                    title: "Error",
                    text: "Something went wrong",
                },
                4000
            ); // 4s
        },
    });
};
const restoreQuestion = () => {
    questions["_method"] = "put";
    questions.post(route("admin.questions.restore", questions.id), {
        onSuccess: () => {
            questions.deleted_at = null;
            notify(
                {
                    group: "notification",
                    type: "success",
                    title: "Success",
                },
                4000
            ); // 4s
        },
        onError: () => {
            notify(
                {
                    group: "notification",
                    type: "error",
                    title: "Error",
                    text: "Something went wrong",
                },
                4000
            ); // 4s
        },
    });
};
</script>

<template>
    <Head
        ><title>Edit Question - {{ questions.title }}</title></Head
    >
    <LayoutAuthenticated>
        <SectionMain>
            <Breadcrumbs
                :href="route(link)"
                title="Questions"
                :location="questions.title"
            />
            <SectionTitleLineWithButton
                v-if="!questions.deleted_at"
                :icon="mdiPencilPlus"
                title="Update the Question"
                main
            />

            <div class="grid grid-cols-1">
                <CardBox
                    :hasTable="hasTable"
                    is-form
                    @submit.prevent="updateQuestion"
                >
                    <Form
                        :questions="questions"
                        :image="image"
                        :Categories="props.Categories"
                        :Topics="props.Topics"
                        buttonText="Update Question"
                        @destroy="destroyQuestion"
                        @restore="restoreQuestion"
                        @removeImage="removeImage"
                    />
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
