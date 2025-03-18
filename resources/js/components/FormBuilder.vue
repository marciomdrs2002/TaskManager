<script setup>
// import { XMarkIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import Button from './ui/button/Button.vue';

const props = defineProps({
    formDefinition: {
        type: Object,
        required: true,
    },
    initialValues: {
        type: Object,
        default: () => ({}),
    },
    submitUrl: {
        type: String,
        required: true,
    },
    method: {
        type: String,
        default: 'post',
    },
    showModal: {
        type: Boolean,
        default: true,
    },
    title: {
        type: String,
        default: 'Formulário',
    },
});

const emit = defineEmits(['submit', 'cancel', 'close']);

const formValues = {};
props.formDefinition.fields.forEach((field) => {
    formValues[field.name] = props.initialValues[field.name] ?? '';
});

const form = useForm(formValues);

const submit = () => {
    form[props.method](props.submitUrl, {
        onSuccess: () => {
            emit('submit', form);
        },
    });
};

const cancel = () => {
    emit('cancel');
};

const closeModal = () => {
    emit('close');
};
</script>

<template>
    <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" aria-hidden="true" @click="closeModal"></div>

            <!-- Modal panel -->
            <div
                class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle dark:bg-gray-800"
            >
                <div class="bg-white px-4 pb-4 pt-5 dark:bg-gray-800 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between pb-3">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                            {{ title }}
                        </h3>
                        <button
                            type="button"
                            class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-gray-300 dark:hover:text-gray-100"
                            @click="closeModal"
                        >
                            <span class="sr-only">Fechar</span>
                            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                        </button>
                    </div>

                    <div class="mt-2">
                        <form @submit.prevent="submit">
                            <div class="space-y-4">
                                <div v-for="field in formDefinition.fields" :key="field.name" class="mb-4">
                                    <!-- Campo de texto -->
                                    <div v-if="field.type === 'text'" class="form-group">
                                        <label :for="field.name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ field.label }}
                                        </label>
                                        <input
                                            :id="field.name"
                                            v-model="form[field.name]"
                                            :type="field.type"
                                            :placeholder="field.placeholder"
                                            :required="field.required"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 dark:border-gray-700 dark:bg-gray-800 sm:text-sm"
                                        />
                                        <p v-if="form.errors[field.name]" class="mt-1 text-sm text-red-600">{{ form.errors[field.name] }}</p>
                                    </div>

                                    <!-- Campo de texto de área -->
                                    <div v-else-if="field.type === 'textarea'" class="form-group">
                                        <label :for="field.name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ field.label }}
                                        </label>
                                        <textarea
                                            :id="field.name"
                                            v-model="form[field.name]"
                                            :placeholder="field.placeholder"
                                            :required="field.required"
                                            :rows="field.rows || 3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 dark:border-gray-700 dark:bg-gray-800 sm:text-sm"
                                        ></textarea>
                                        <p v-if="form.errors[field.name]" class="mt-1 text-sm text-red-600">{{ form.errors[field.name] }}</p>
                                    </div>

                                    <!-- Campo de seleção -->
                                    <div v-else-if="field.type === 'select'" class="form-group">
                                        <label :for="field.name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ field.label }}
                                        </label>
                                        <select
                                            :id="field.name"
                                            v-model="form[field.name]"
                                            :required="field.required"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 dark:border-gray-700 dark:bg-gray-800 sm:text-sm"
                                        >
                                            <option value="" disabled selected>{{ field.placeholder }}</option>
                                            <option v-for="(label, value) in field.options" :key="value" :value="value">
                                                {{ label }}
                                            </option>
                                        </select>
                                        <p v-if="form.errors[field.name]" class="mt-1 text-sm text-red-600">{{ form.errors[field.name] }}</p>
                                    </div>

                                    <!-- Campo de checkbox -->
                                    <div v-else-if="field.type === 'checkbox'" class="form-group">
                                        <div class="flex items-center">
                                            <input
                                                :id="field.name"
                                                v-model="form[field.name]"
                                                type="checkbox"
                                                class="h-4 w-4 rounded border-gray-300 text-black focus:ring-gray-500 dark:border-gray-700"
                                            />
                                            <label :for="field.name" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                {{ field.label }}
                                            </label>
                                        </div>
                                        <p v-if="form.errors[field.name]" class="mt-1 text-sm text-red-600">{{ form.errors[field.name] }}</p>
                                    </div>

                                    <!-- Campo de data -->
                                    <div v-else-if="field.type === 'date'" class="form-group">
                                        <label :for="field.name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ field.label }}
                                        </label>
                                        <input
                                            :id="field.name"
                                            v-model="form[field.name]"
                                            type="date"
                                            :required="field.required"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 dark:border-gray-700 dark:bg-gray-800 sm:text-sm"
                                        />
                                        <p v-if="form.errors[field.name]" class="mt-1 text-sm text-red-600">{{ form.errors[field.name] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões de ação -->
                            <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                <Button
                                    v-for="action in formDefinition.actions.filter((a) => a.type === 'submit')"
                                    :key="action.label"
                                    type="submit"
                                    class="inline-flex w-full justify-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm"
                                    :disabled="form.processing"
                                >
                                    {{ form.processing ? 'Processando...' : action.label }}
                                </Button>

                                <button
                                    type="button"
                                    class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 sm:col-start-1 sm:mt-0 sm:text-sm"
                                    @click="cancel"
                                >
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>