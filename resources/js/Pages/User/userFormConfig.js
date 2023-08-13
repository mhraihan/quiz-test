import { UserEnum } from "@/config";
import { isRequired, isIn, isMin, isEmail, isSame } from "intus/rules";

export function generateUserFormConfig(props) {
    const commonFields = {
        first_name: [props?.User?.first_name || null, [isRequired()]],
        last_name: [props?.User?.last_name || null, [isRequired()]],
        email: [props?.User?.email || null, [isRequired(), isEmail()]],
        password: [null, [isMin(8)]],
        password_confirmation: [null, [isSame('password')]],
        photo_path: [null],
        active: [true],
        state: [props?.User?.state || null, [isRequired()]],
        birthday: [props?.User?.birthday ? new Date(props.User.birthday).toISOString().slice(0, 10) : null, [isRequired()]],
        city: [props?.User?.city || null, [isRequired()]],
        phone: [props?.User?.phone || null, [isRequired()]],
        country: [props?.User?.country || "HK", [isRequired()]],
        address: [props?.User?.address || null, [isRequired()]],
        postcode: [props?.User?.postcode || null, [isRequired()]],
        gender: [props?.User?.gender || "male", [isRequired(), isIn("male", "female")]],
        deleted_at: [props?.User?.deleted_at || null],
        roles: [props.Role.toLowerCase()],
    };

    if (props.Role === UserEnum.STUDENT) {
        commonFields.school_id = [props.current_school ?? null, [isRequired()]];
        commonFields.teacher_id = [props.current_teacher ?? null, [isRequired()]];
    } else if (props.Role === UserEnum.TEACHER) {
        commonFields.school_id = [props.current_school ?? null, [isRequired()]];
    }

    return {
        ...commonFields,
    };
}


export function generateRouterConfigByRole(role) {
    if (UserEnum.TEACHER === role.toLowerCase()) {
        return {
            title: 'Create New Teachers',
            route: 'admin.teachers.index',
            create:'admin.teachers.create'
        };
    }

    return {
        title: 'Create New Student',
        route: 'admin.students.index',
        create: 'admin.students.create'
    };
}

export function getUrlByRole(role) {
    if (role.toLowerCase() === UserEnum.ADMIN) {
        return 'admin.users.index';
    } else if (role.toLowerCase() === UserEnum.TEACHER) {
        return 'admin.teachers.index';
    } else {
        return 'admin.students.index';
    }
}
