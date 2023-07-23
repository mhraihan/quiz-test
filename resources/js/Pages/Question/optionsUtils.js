// optionsUtils.js
import {reactive} from "vue";

export const optionsPlaceholder = reactive(
    {
        a: "",
        b: "",
        c: "",
        d: "",
    }
);
export const getQuestionOptions = (obj = {}, len = 4) => {
  const property = { ...optionsPlaceholder, ...obj };
  const keys = Object.keys(property).slice(0, len);
  const result = Object.fromEntries(keys.map((key) => [key, property[key]]));
  return result;
};

// formUtils.js
export function createFormData(props) {
    const options = props?.Question?.options || {...optionsPlaceholder};
    const options_two = props?.Question?.options_two || {...optionsPlaceholder};
  return {
    id: props.Question?.id || null,
    title: props.Question?.title || "",
    details: props.Question?.details || "",
    explain: props.Question?.explain || "",
    options: { ...options },
    title_two: props.Question?.title_two || "",
    details_two: props.Question?.details_two || "",
    explain_two: props.Question?.explain_two || "",
    options_two: { ...options_two },
    correct_answer: props.Question?.correct_answer || "a",
    is_active: props.Question?.is_active || true,
    image: null,
    deleted_at: props.Question?.deleted_at || null,
    category_id: props.Question?.category_id ||  props?.Categories[0]?.id || null,
    topic_id: props.Question?.topic_id ||  props?.Topics[0]?.id || null,
    question_options: "4",
    _method: props.Question ? "put" : "post",
  };
}
