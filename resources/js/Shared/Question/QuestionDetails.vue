<script setup>
const props = defineProps({
    question: {
        type: Object,
        required: true
    },
    key: {
        type: Number,
        default: 0
    }
});
const quiz = (answer, correct_answer, key) => {
    if (route().current() !== 'results.show') return;
    if (answer === key) {
        return answer !== correct_answer ? 'bg-red-600 text-white' : 'bg-green-500 text-white';
    }
    return key === correct_answer ? 'bg-green-500 text-white' : '';
}

</script>

<template>
    <div class="px-4 py-5 sm:px-6">
        <div class="text-lg leading-6 mb-4 font-medium text-gray-900">
            <h3><span v-if="!!props.key" class="mr-2 font-extrabold"> {{ props.key + 1 }}</span> {{
                    props.question.title
                }}</h3>
            <details class="block text-xs mt-2" v-if="props.question.details" :open="route().current() === 'admin.questions.show'">
                <summary class="p-1 hover:cursor-pointer" :id="'questions-details' + props.key">
                               <span class="underline text-blue-500 hover:text-blue-700 focus:outline-none text-xs "
                                     type="button">
                                   Question Details
                               </span>
                </summary>
                <div class="block p-2 bg-green-100 text-xs leading-5 my-3 question-details"
                     v-html="props.question.details"></div>


            </details>
            <details class="block text-xs mt-2" v-if="props.question.explain" :open="route().current() === 'admin.questions.show'">
                <summary class="p-1 hover:cursor-pointer" :id="'questions-explain' + props.key">
                               <span class="underline text-blue-500 hover:text-blue-700 focus:outline-none text-xs "
                                     type="button">
                                   Explanation
                               </span>
                </summary>
                <div class="block p-2 bg-green-100 text-xs leading-5 my-3" v-html="props.question.explain"></div>
            </details>
        </div>
        <div v-for="(option,key) in props.question.options"
             :class="quiz(props.question.answer,props.question.correct_answer,key)"
             class="mt-1 max-w-auto text-sm px-2 rounded-lg  bg-none ">
            <span class="mr-2 font-extrabold">{{ key }} </span> {{ option }}
            <span v-if="key === props.question.correct_answer && route().current() === 'results.show'"
                  class="p-1 font-extrabold">(Correct Answer)</span>
            <span v-if="key === props.question.answer && route().current() === 'results.show'"
                  class="p-1 font-extrabold">(Your Answer)</span>
        </div>

    </div>
</template>
<style >
.question-details ol {
    list-style: decimal;
    margin-left: 10px;
}
.question-details ul {
    list-style: disc;
    margin-left: 10px;
}
</style>
