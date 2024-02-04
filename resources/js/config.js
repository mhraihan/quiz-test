import {usePage} from "@inertiajs/inertia-vue3";

export const darkModeKey = "darkMode";

export const styleKey = "style";

export const containerMaxW = "xl:max-w-6xl xl:mx-auto";

export const languages = [
    {
        "label": "English",
        "value": "en",
    },
    {
        "label": "Chinese",
        "value": "zh",
    }
]

export const UserEnum = {
    "SUPER_ADMIN": 'super-admin',
    "ADMIN": 'admin',
    "TEACHER": 'teacher',
    "STUDENT": 'student',
};

export function getUserProperty(user) {
    switch (user) {
        case UserEnum.SUPER_ADMIN:
        case UserEnum.ADMIN:
            return {
                url: 'admin.users.index',
                store: 'Admin Created Successfully',
                update: 'Admin Profile updated successfully',
                delete: 'Admin Profile deleted successfully',
                trash: 'Admin Profile moved to the Trash successfully',
                restore: 'Admin Profile restored',
            };
        case UserEnum.TEACHER:
            return {
                url: 'admin.teachers.index',
                store: 'Teacher Created Successfully',
                update: 'Teacher Profile updated successfully',
                delete: 'Teacher Profile deleted successfully',
                trash: 'Teacher Profile moved to the Trash successfully',
                restore: 'Teacher Profile restored',
            };
        case UserEnum.STUDENT:
            return {
                url: 'admin.students.index',
                store: 'Student Created Successfully',
                update: 'Student Profile updated successfully',
                delete: 'Student Profile deleted successfully',
                trash: 'Student Profile moved to the Trash successfully',
                restore: 'Student Profile restored',
            };
        default:
            return {};
    }
}

export const userAdmin = () => {
    return [UserEnum.SUPER_ADMIN, UserEnum.ADMIN];
}
export const userAdminOrTeacher = () => {
    return [UserEnum.SUPER_ADMIN, UserEnum.ADMIN, UserEnum.TEACHER];
}
export const userAdminStudent = () => {
    return [...userAdmin(), UserEnum.STUDENT];
}
const getRole = () => {
    return usePage().props.value.auth.roles;
};

export const isAdmin = () => {
    const role = getRole();
    return role === UserEnum.SUPER_ADMIN || role === UserEnum.ADMIN;
}
export const isTeacher = () => {
    const role = getRole();
    return role === UserEnum.TEACHER;
}
export const isAdminOrTeacher = () => {
    const role = getRole();
    return isAdmin() || isTeacher();
}
export const isStudent = () => {
    const role = getRole();
    return role === UserEnum.STUDENT;
}

export const removeHTMLTags = (input) => {
    return input.replace(/^<p>(.*?)<\/p>/, '$1');
}
export const renderMath = (elementId = "mh-math") => {
    // Ensure MathJax is loaded before rendering
    if (window.MathJax) {
        console.log("hmm")
        window.MathJax.Hub.Queue(['Typeset', window.MathJax.Hub, document.getElementById(elementId)]);
    } else {
        // Load MathJax if it's not already loaded
        const script = document.createElement('script');
        script.type = 'text/javascript';
        script.async = true;
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML';

        script.onload = () => {
            // Render math equations when MathJax is loaded
            console.log(" </div>")
            window.MathJax.Hub.Queue(['Typeset', window.MathJax.Hub, document.getElementById(elementId)]);
        };

        document.head.appendChild(script);
    }
}
