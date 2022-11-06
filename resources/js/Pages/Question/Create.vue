<script setup>
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

import { mdiPencilPlus } from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";

import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import {Head, useForm} from "@inertiajs/inertia-vue3";
import { notify } from "notiwind"
import intus from "intus";
import {isRequired, isIn, isBetween, isImage} from "intus/rules";
import Form from "./Form.vue";
import {ref, watch} from "vue";
const props = defineProps({
    Categories: Object,
});
const hasTable = true;

const options = {
    a: "",
    b: "",
    c: "",
    d: "",
};
const questions = useForm({
    title: "",
    details: "",
    explain: "",
    options,
    correct_answer: "a",
    is_active: true,
    image: null,
    category_id: props?.Categories[0]?.id || null,
    topic_id: null,
});
const removeQuestionImage = ref(false);
const image = ref(null);

watch(() => questions.image , () => {
    if (questions.image){
        image.value = URL.createObjectURL(questions.image)
    }
})
const removeImage = () => {
    image.value = null;
    removeQuestionImage.value = true;
    questions.image = null;
};
const createQuestion = () => {
    questions.clearErrors();
    const validation = intus.validate(questions.data(), {
        title: [isRequired()],
        details: [isRequired()],
        "options.*": [isRequired()],
        correct_answer: [isRequired(),isIn("a", "b", "c","d")],
        category_id: [isRequired(),isBetween(1,4)],
        image: [isImage()]
    },{
        'title.isRequired' : 'Please, write the question name',
        'details.isRequired' : 'Please, Describe about the question',
        'options.a.isRequired' : "Option A can not be blank",
        'options.b.isRequired' : "Option B can not be blank",
        'options.c.isRequired' : "Option C can not be blank",
        'options.d.isRequired' : "Option C can not be blank",
        'image.isImage' : "Input file must be a valid image",
    });
    if (validation.passes()) {
        questions
            .transform((data) => ({
                ...data,
                image: data.image ? data.image : null,
                explain: data.explain !== "<p><br></p>" ? data.explain : null
            }))
            .post(route("admin.questions.store"), {
                "onSuccess": () => {
                    notify({
                        group: "notification",
                        type: "success",
                        title: "Success",
                        text:  'Question created successfully'
                    }, 4000)
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
        questions.setError(validation.errors());
        notify({
            group: "notification",
            type: "error",
            title: "Error",
            text: 'Something went wrong'
        }, 4000) // 4s
    }

};
</script>

<template>
    <Head title="Create new Question" />
    <LayoutAuthenticated>
        <SectionMain>
            <Breadcrumbs :href="route('admin.questions.index')" title="Questions" location="Create New Question" />
            <SectionTitleLineWithButton
                :icon="mdiPencilPlus"
                title="Create new Question"
                main
            />

            <div class="grid grid-cols-1">
                <CardBox
                    is-form
                    @submit.prevent="createQuestion"
                    :hasTable="hasTable"
                >
                    <Form :questions="questions" :Categories="Categories" :image="image"  @removeImage="removeImage"/>
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
