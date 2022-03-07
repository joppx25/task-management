<template>
    <!-- Main modal -->
    <div id="authentication-modal" aria-hidden="true" class="hidden overflow-x-hidden overflow-y-auto fixed h-modal md:h-full top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center">
        <div class="relative w-full max-w-md px-4 h-full md:h-auto">
            <!-- Modal content -->
            <div class="bg-white rounded-lg shadow relative dark:bg-gray-700">
                <div class="flex justify-end p-2">
                    <button type="button" @click="hideModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                    </button>
                </div>
                <form 
                    class="space-y-6 px-6 lg:px-8 pb-4 sm:pb-6 xl:pb-8" @submit.prevent="submitForm($page.props.auth.user.id)">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">Create a new Task</h3>
                    <div>
                        <label for="title" :class="state.error ? 'text-red-700 dark:text-red-500' : 'text-sm font-medium text-gray-900 block mb-2 dark:text-gray-300'">Title</label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            v-model="state.title"
                            :class="state.error ? state.classTitleError : state.classTitleDefault" placeholder="Lorem ipsum todo">
                        <p v-if="state.error" class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">Oops!</span> {{ state.error }}</p>
                    </div>
                    <button type="submit" :disabled="processing" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        {{ props.onEdit ? 'Update' : 'Create' }}
                    </button>
                </form>
            </div>
        </div>
    </div> 
</template>

<script setup>
    import { onMounted, reactive, computed, ref } from 'vue'
    import axios from 'axios'
    import { Inertia } from '@inertiajs/inertia'

    let props = defineProps({
        onEdit: {
            type: Boolean,
            default: false,
        },
        editData: {
            type: Object,
            default: {},
        },
        isActive: {
            type: Boolean,
            default: false,
        },
    });

    let state = reactive({
        result: false,
        id: Object.keys(props.editData).length ? props.editData.id : '',
        title: Object.keys(props.editData).length ? props.editData.title : '',
        error: '',
        classTitleDefault: 'bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white',
        classTitleError: 'bg-red-50 border border-red-500 text-red-900 placeholder-red-700 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-red-100 dark:border-red-400',
    });

    let processing = ref(false);
    onMounted(function () {
        toggleModal('authentication-modal', props.isActive);
    });

    let emits = defineEmits(['reload', 'hide']);
    function submitForm(userId) {
        processing.value = true;
        axios.post('/tasks/storeOrUpdate', {id: state.id, title: state.title, user_id: userId})
            .then((response) => {
                state.result = response.status === 200;
                if (state.result) {
                    state.error = '';
                    emits('reload');
                    processing.value = false;
                    window.location = '/';
                }
            })
            .catch((error) => {
                let errors = error.response.data.errors;
                state.error = errors.title[0];
            });
    }

    function hideModal() {
        toggleModal('authentication-modal', false);
        emits('hide');
    }

</script>