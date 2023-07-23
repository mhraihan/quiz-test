<script setup>
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import {mdiPencilPlus} from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import CardBox from "@/Components/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import {Head, useForm} from "@inertiajs/inertia-vue3";
import {notify} from "notiwind"
import Form from "./Form.vue";
import { ref, watch} from "vue";
import {handleQuestionSubmit} from "@/Pages/Question/useQuestionValidator";
import {createFormData, getQuestionOptions} from "@/Pages/Question/optionsUtils";

const props = defineProps({
    Categories: Object,
    Topics: Object,
});
const hasTable = true;



const questions = useForm(createFormData(props));;
const removeQuestionImage = ref(false);
const image = ref(null);

watch(() => questions.image, () => {
    if (questions.image) {
        image.value = URL.createObjectURL(questions.image)
    }
})

watch(() => questions.question_options, (newValue) => {
   questions.options = getQuestionOptions(questions.options , newValue);
   questions.options_two = getQuestionOptions(questions.options_two , newValue);
})
const removeImage = () => {
    image.value = null;
    removeQuestionImage.value = true;
    questions.image = null;
};
const createQuestion = () => {
    console.log(questions)

    questions.clearErrors();
    handleQuestionSubmit(
        questions,
        route("admin.questions.store"),
        {
            onSuccess: () => {
                notify({
                    group: "notification",
                    type: "success",
                    title: "Success",
                    text: 'Question created successfully'
                }, 4000);
            },
            onError: () => {
                notify({
                    group: "notification",
                    type: "error",
                    title: "Error",
                    text: 'Something went wrong'
                }, 4000); // 4s
            }
        }
    );
};

</script>

<template>
    <Head title="Create new Question"/>
    <LayoutAuthenticated>
        <SectionMain>
            <Breadcrumbs :href="route('admin.questions.index')" title="Questions" location="Create New Question"/>
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
                    <Form :questions="questions" :Categories="props.Categories" :Topics="props.Topics" :image="image"
                          @removeImage="removeImage"/>
                </CardBox>
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
