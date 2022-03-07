<template>
    <tr v-for="(task, index) in props.tasks.data" :key="index">
        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ task.id }}</td>
        <td class="py-4 px-6 text-sm font-medium text-gray-500 whitespace-nowrap dark:text-white">{{ task.title }}</td>
        <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ task.created_at }}</td>
        <td class="py-4 px-6 text-sm font-medium text-right whitespace-nowrap">
            <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline" @click="triggerModalForEdit({id: task.id, title: task.title})">Edit</a>
            <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline mx-3" @click="destroy(task.id)">Delete</a>
        </td>
    </tr>
</template>

<script setup>
    import { Inertia } from '@inertiajs/inertia' 

    const props = defineProps({
        tasks: {
            type: Object,
            required: true,
            default: {},
        },
    });

    let emits = defineEmits(['triggerModalForEdit']);
    function triggerModalForEdit(data) {
        emits('triggerModalForEdit', data);
    }

    function destroy(id) {
        let confirmation = confirm('Are you sure you want to delete this item?');
        if (confirmation) {
            Inertia.post('/tasks/delete', {id});    
        }
    }
</script>