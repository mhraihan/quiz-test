<script setup>


const props = defineProps({
    Users: {
        type: Object,
        required: true
    },
});


</script>

<template>
    <table>
        <thead>
        <tr>

            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(user,key) in props.Users.data" :key="key">
            <td data-label="Name">
                {{ user.first_name }}
            </td>
            <td data-label="Company">
                {{ user.last_name }}
            </td>
            <td data-label="Email">
                {{ user.email }}
            </td>
        </tr>
        </tbody>
    </table>
</template>
