<template>
  <Loader v-if="store.loading"></Loader>
  <div
    v-else
    class="grid w-11/12 grid-cols-1 grid-rows-6 mx-auto overflow-y-hidden text-gray-600  md:w-8/12 lg:w-7/12 custom-height"
  >
    <div class="row-auto">
      <div class="flex flex-col items-center justify-between py-4 rounded-lg">
        <div class="flex mt-0 mb-14">
          <div
            v-for="(item, index) in store.data.results"
            :key="index"
            class="flex items-center justify-center w-3 h-3 mx-1 text-xs text-center text-white rounded "
            :class="{
              'bg-green-300': item.guessedRight,
              'bg-red-300': item.guessedRight == false,
              'bg-gray-200': !item.guessedRight,
            }"
          ></div>
        </div>
        <div
          class="flex items-center justify-center w-full p-3 mb-3 border-4 border-gray-400 rounded-lg shadow-xl  md:p-5"
        >
          <h1
            class="font-medium text-center md:text-lg"
            v-html="store.data.results[store.currentQuestion].question"
          ></h1>
        </div>
      </div>
    </div>
    <div class="row-auto">
      <div class="flex flex-col justify-center">
        <div class="grid grid-cols-1 gap-4 md:gap-4 md:grid-cols-2">
          <Answer
            v-for="answer in store.data.results[store.currentQuestion]
              .shuffled_answers"
            :key="answer"
            :text="answer"
            :is-valid-answer="
              answer == store.data.results[store.currentQuestion].correct_answer
            "
            :is-invalid-answer="
              answer != store.data.results[store.currentQuestion].correct_answer
            "
            :show-answer="store.showAnswer"
            @check-answer="store.checkAnswer"
          ></Answer>
        </div>
      </div>
    </div>
    <div class="">
      <div class="flex items-center justify-center min-w-full min-h-full">
        <Transition name="grow-fade">
          <button
            @click="store.getNextQuestion"
            class="w-full px-12 py-4 text-lg text-white transition bg-gray-600 rounded-lg  hover:bg-gray-700 md:w-1/3"
            v-show="store.showAnswer"
          >
            Next
          </button>
        </Transition>
      </div>
    </div>
  </div>
</template>

<script>
import Answer from "@/Components/Quizs/Answer.vue";
import Loader from "@/Components/Quizs/Loader.vue";
import { store } from "@/store.js";

export default {
  components: {
    Answer,
    Loader,
  },
  data() {
    return {
      store,
    };
  },
  created() {
    this.store.getData();
  },
};
</script>

<style scoped>
.grow-fade-enter-active {
  transition: all 0.2s ease-out;
}

.grow-fade-leave-active {
  transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}

.grow-fade-enter-from,
.grow-fade-leave-to {
  opacity: 0;
  transform: scale(0.8) translateY(60px);
}
.custom-height {
  min-height: 100vh;
}

@media only screen and (max-width: 800px) {
  .custom-height {
    /* 92vh to make up for the toolbar in the mobile browser */
    min-height: 92vh;
  }
}
</style>
