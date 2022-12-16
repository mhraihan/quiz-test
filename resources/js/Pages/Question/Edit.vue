<script setup>
import {mdiPencilPlus} from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";

import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import {Head, useForm} from "@inertiajs/inertia-vue3";
import Form from "./Form.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import {computed, ref, watch} from "vue";
import {notify} from "notiwind"
import intus from "intus";
import {isBetween, isImage, isIn, isRequired} from "intus/rules";

const props = defineProps({
    Categories: Object,
    Topics: Object,
    Question: Object,
    image: String,
});

const options = props.Question.options;
const hasTable = true;
const questions = useForm({
    id: props.Question.id,
    title: props.Question.title,
    details: props.Question.details,
    explain: props.Question.explain,
    options,
    correct_answer: props.Question.correct_answer,
    is_active: props.Question.is_active,
    image: null,
    deleted_at: props.Question.deleted_at,
    category_id: props.Question.category_id || null,
    topic_id: props.Question.topic_id || null,
    _method: "put",
});
const link = computed(() => questions.deleted_at ? 'admin.questions.trash' : 'admin.questions.index');

const removeQuestionImage = ref(false);
const image = ref(props.image);

watch(() => questions.image, () => {
    if (questions.image) {
        image.value = URL.createObjectURL(questions.image)
    }
})
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
    const validation = intus.validate(questions.data(), {
        title: [isRequired()],
        details: [isRequired()],
        "options.*": [isRequired()],
        correct_answer: [isRequired(), isIn("a", "b", "c", "d")],
        category_id: [isRequired(), isBetween(1, 4)],
        image: [isImage()]
    }, {
        'title.isRequired': 'Please, write the question name',
        'details.isRequired': 'Please, Describe about the question',
        'options.a.isRequired': "Option A can not be blank",
        'options.b.isRequired': "Option B can not be blank",
        'options.c.isRequired': "Option C can not be blank",
        'options.d.isRequired': "Option C can not be blank",
        'image.isImage': "Input file must be a valid image",
    });
    if (validation.passes()) {
        questions
            .transform((data) => ({
                ...data,
                details: data.details !== "<p><br></p>" ? data.details : null,
                explain: data.explain !== "<p><br></p>" ? data.explain : null
            }))
            .post(route("admin.questions.update", props.Question.id), {
                "onSuccess": () => {
                    questions.image = null;
                    notify({
                        group: "notification",
                        type: "success",
                        title: "Success"
                    }, 4000) // 4s
                    removeQuestionImage.value = false;
                },
                onError: () => {
                    notify({
                        group: "notification",
                        type: "error",
                        title: "Error",
                        text: 'Something went wrong'
                    }, 4000) // 4s
                }
            });
    } else {
        notify({
            group: "notification",
            type: "error",
            title: "Error",
            text: 'Something went wrong'
        }, 4000) // 4s
        questions.setError(validation.errors());
    }
};


const destroyQuestion = () => {
    questions['_method'] = "delete";
    questions
        .post(route('admin.questions.destroy', questions.id), {
        onSuccess: () => {
            questions.deleted_at = questions.deleted_at || new Date();
            notify({
                group: "notification",
                type: "success",
                title: "Success"
            }, 4000) // 4s
            questions.clearErrors();
        },
        onError: () => {
            notify({
                group: "notification",
                type: "error",
                title: "Error",
                text: 'Something went wrong'
            }, 4000) // 4s
        }
    });
}
const restoreQuestion = () => {
    questions['_method'] = "put";
    questions.post(route('admin.questions.restore', questions.id), {
        onSuccess: () => {
            questions.deleted_at = null;
            notify({
                group: "notification",
                type: "success",
                title: "Success"
            }, 4000) // 4s
        },
        onError: () => {
            notify({
                group: "notification",
                type: "error",
                title: "Error",
                text: 'Something went wrong'
            }, 4000) // 4s
        }
    })

}
</script>

<template>
    <Head><title>Edit Question - {{ questions.title }}</title></Head>
    <LayoutAuthenticated>

        <SectionMain>
            <Breadcrumbs :href="route(link)" title="Questions" :location="questions.title"/>
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
                    @submit.prevent="updateQuestion">
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
