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
