import { notify } from "notiwind";
import intus from "intus";
import { isRequired, isIn, isBetween, isImage } from "intus/rules";

export const questionValidateForm = (formData) => {
    // Define the dynamic validation rules for correct_answer based on formData.question_options
    const answerRules =
        formData.question_options === "2"
            ? { correct_answer: [isRequired(), isIn("a", "b")] }
            : { correct_answer: [isRequired(), isIn("a", "b", "c", "d")] };
    const validation = intus.validate(
        formData,
        {
            title: [isRequired()],
            // details: [isRequired()],
            "options.*": [isRequired()],
            title_two: [isRequired()],
            // details_two: [isRequired()],
            "options_two.*": [isRequired()],
            ...answerRules,
            category_id: [isRequired(), isBetween(1, 4)],
            image: [isImage()],
            question_options: [isRequired(), isIn("2", "3", "4")],
        },
        {
            "title.isRequired": "Please, write the question name",
            "details.isRequired": "Please, Describe about the question",
            "options.a.isRequired": "Option A can not be blank",
            "options.b.isRequired": "Option B can not be blank",
            "options.c.isRequired": "Option C can not be blank",
            "options.d.isRequired": "Option C can not be blank",
            "title_two.isRequired": "Please, write the question name",
            "details_two.isRequired": "Please, Describe about the question",
            "options_two.a.isRequired": "Option A can not be blank",
            "options_two.b.isRequired": "Option B can not be blank",
            "options_two.c.isRequired": "Option C can not be blank",
            "options_two.d.isRequired": "Option C can not be blank",
            "image.isImage": "Input file must be a valid image",
        }
    );

    return validation;
};

export const handleQuestionSubmit = (formData, onSuccess, onError) => {
    const validation = questionValidateForm(formData);
    if (validation.passes()) {
        formData
            .transform((data) => ({
                ...data,
                details: data.details !== "<p><br></p>" ? data.details : null,
                explain: data.explain !== "<p><br></p>" ? data.explain : null,
                image: data.image || null,
            }))
            .post(onSuccess, onError);
    } else {
        notify(
            {
                group: "notification",
                type: "error",
                title: "Error",
                text: "Something went wrong",
            },
            4000
        ); // 4s
        formData.setError(validation.errors());
    }
};
