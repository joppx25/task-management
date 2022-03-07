<template>
    <Head title="Task List" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Task List
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="rounded-t mb-0 px-4 py-3 border-0">
                            <div class="flex flex-wrap items-center">
                                <div class="relative w-full max-w-full flex-grow flex-1 text-left">
                                    <input v-model="search" type="text" placeholder="search" class="border px-2 rounded-lg">
                                </div>
                                <div class="relative w-full max-w-full flex-grow flex-1 text-right">
                                    <a href="#" @click="state.isActive = true" class="bg-indigo-500 text-white active:bg-indigo-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150" type="button">
                                        Add Task
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="block w-full overflow-x-auto">
                            <div class="flex flex-col">
                                <div class="overflow-x-auto shadow-md sm:rounded-lg">
                                    <div class="inline-block min-w-full align-middle">
                                        <div class="overflow-hidden ">
                                            <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-700">
                                                <thead class="bg-gray-100 dark:bg-gray-700">
                                                    <tr>
                                                        <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                            ID #
                                                        </th>
                                                        <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                            Title
                                                        </th>
                                                        <th scope="col" class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                            Created At
                                                        </th>
                                                        <th scope="col" class="p-4"><span class="sr-only">Edit</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                                    <TaskList :tasks="props.tasks" :key="state.list_key" @triggerModalForEdit="editModalTriggered"/>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <pagination class="mt-6" :links="props.tasks.links" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
    <Modal @reload="refreshList" :edit-data="state.data" :onEdit="state.onEdit" :isActive="state.isActive" v-if="state.isActive" @hide="resetModalData()"/>
</template>

<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import Pagination from '@/Components/Pagination';
import { Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import { reactive, ref, watch } from 'vue';
import Modal from '@/Components/Modal.vue'
import TaskList from './TaskList.vue';

let state = reactive({
    list_key: 0,
    modal_key: 0,
    onEdit: false,
    data: {},
    isActive: false,
});
const props = defineProps({
    tasks: {
        type: Object,
        required: true,
        default: {},
    },
    filters: Object,
});

let search = ref(props.filters.search);
watch(search, value => {
    console.log(`Test: ${value}`);
    Inertia.get('/', {search: value}, {
        preserveState: true
    });
});

function refreshList() {
    state.list_key++;
}

function editModalTriggered(data) {
    state.onEdit   = true;
    state.data     = data;
    state.isActive = true;
}

function resetModalData() {
    state.onEdit   = false;
    state.data     = {};
    state.isActive = false;   
}

</script>