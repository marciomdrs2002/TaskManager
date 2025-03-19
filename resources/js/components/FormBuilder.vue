<template>
    <Dialog
        v-model:visible="visible"
        :header="pageTitle"
        :style="{ width: '50rem' }"
        :modal="true"
        :closable="true"
        @hide="cancel"
        v-if="showModal && definition && form"
    >
        <div class="p-fluid">
            <form @submit.prevent="submit">
                <!-- Layout de duas colunas -->
                <div class="space-y-6">
                    <div v-for="(pair, index) in fieldPairs" :key="index" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- Campos em pares -->
                        <div v-for="field in pair" :key="field.name" class="field">
                            <!-- Campo de texto -->
                            <template v-if="field.type === 'text'">
                                <label :for="field.name" class="mb-2 block text-sm font-medium">
                                    {{ field.label }}
                                    <span v-if="field.required" class="text-red-500">*</span>
                                </label>
                                <InputText
                                    :id="field.name"
                                    v-model="form[field.name]"
                                    :placeholder="field.placeholder"
                                    :required="field.required"
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors[field.name] }"
                                />
                                <small v-if="form.errors[field.name]" class="p-error mt-1 block">{{ form.errors[field.name] }}</small>
                            </template>

                            <!-- Campo de textarea -->
                            <template v-else-if="field.type === 'textarea'">
                                <label :for="field.name" class="mb-2 block text-sm font-medium">
                                    {{ field.label }}
                                    <span v-if="field.required" class="text-red-500">*</span>
                                </label>
                                <Textarea
                                    :id="field.name"
                                    v-model="form[field.name]"
                                    :placeholder="field.placeholder"
                                    :required="field.required"
                                    :rows="field.rows || 3"
                                    :autoResize="true"
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors[field.name] }"
                                />
                                <small v-if="form.errors[field.name]" class="p-error mt-1 block">{{ form.errors[field.name] }}</small>
                            </template>

                            <!-- Campo de select/dropdown -->
                            <template v-else-if="field.type === 'select'">
                                <label :for="field.name" class="mb-2 block text-sm font-medium">
                                    {{ field.label }}
                                    <span v-if="field.required" class="text-red-500">*</span>
                                </label>
                                <Dropdown
                                    :id="field.name"
                                    v-model="form[field.name]"
                                    :options="Object.entries(field.options).map(([value, label]) => ({ label, value }))"
                                    optionLabel="label"
                                    optionValue="value"
                                    :placeholder="field.placeholder"
                                    class="w-full"
                                    :class="{ 'p-invalid': form.errors[field.name] }"
                                />
                                <small v-if="form.errors[field.name]" class="p-error mt-1 block">{{ form.errors[field.name] }}</small>
                            </template>

                            <!-- Campo de checkbox -->
                            <template v-else-if="field.type === 'checkbox'">
                                <div class="mt-6 flex items-center">
                                    <Checkbox
                                        :id="field.name"
                                        v-model="form[field.name]"
                                        :binary="true"
                                        :class="{ 'p-invalid': form.errors[field.name] }"
                                    />
                                    <label :for="field.name" class="ml-2 text-sm">
                                        {{ field.label }}
                                        <span v-if="field.required" class="text-red-500">*</span>
                                    </label>
                                </div>
                                <small v-if="form.errors[field.name]" class="p-error mt-1 block">{{ form.errors[field.name] }}</small>
                            </template>

                            <!-- Campo de data -->
                            <template v-else-if="field.type === 'date'">
                                <label :for="field.name" class="mb-2 block text-sm font-medium">
                                    {{ field.label }}
                                    <span v-if="field.required" class="text-red-500">*</span>
                                </label>
                                <Calendar
                                    :id="field.name"
                                    v-model="form[field.name]"
                                    dateFormat="dd/mm/yy"
                                    :showIcon="true"
                                    :required="field.required"
                                    :class="{ 'p-invalid': form.errors[field.name] }"
                                />
                                <small v-if="form.errors[field.name]" class="p-error mt-1 block">{{ form.errors[field.name] }}</small>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="mt-8 flex justify-end space-x-3">
                    <Button type="button" label="Cancelar" class="p-button-outlined p-button-secondary" @click="cancel" :disabled="form.processing" />
                    <Button
                        type="submit"
                        :label="form.processing ? 'Processando...' : 'Salvar'"
                        class="p-button-primary"
                        :disabled="form.processing"
                        :loading="form.processing"
                    />
                </div>
            </form>
        </div>
    </Dialog>

    <div v-else-if="!isLoading && (!definition || !form)" class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
        <div class="p-message p-message-error">
            <div class="p-message-wrapper">
                <span class="p-message-icon pi pi-times-circle"></span>
                <div class="p-message-text">Erro ao carregar formulário. Verifique a configuração do componente.</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, onMounted, ref, watch } from 'vue';

import Button from 'primevue/button';
import Calendar from 'primevue/calendar';
import Checkbox from 'primevue/checkbox';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';

const props = defineProps({
    modelName: {
        type: String,
        required: true,
        description: 'Nome do modelo (ex: "tasks", "projects")',
    },
    itemId: {
        type: [Number, String],
        default: null,
        description: 'ID do item a ser editado (null para criação)',
    },
    showModal: {
        type: Boolean,
        default: true,
        description: 'Exibir como modal ou como parte da página',
    },
    title: {
        type: String,
        default: null,
        description: 'Título personalizado (sobrescreve o título automático)',
    },

    customEndpoint: {
        type: String,
        default: null,
        description: 'Endpoint personalizado para carregar definição do formulário',
    },
});

const emit = defineEmits(['submit', 'cancel']);

// Estado interno
const isLoading = ref(false);
const definition = ref(null);
const formData = ref({});
const form = ref(null);
const visible = ref(props.showModal);

// Valores computados
const isEdit = computed(() => !!props.itemId);
const pageTitle = computed(() => {
    if (props.title) return props.title;
    return isEdit.value ? `Editar ${props.modelName}` : `Novo ${props.modelName}`;
});

const formSubmitUrl = computed(() => {
    const basePath = `${props.modelName}`;
    if (isEdit.value) {
        return route(`${basePath}.update`, props.itemId);
    }
    return route(`${basePath}.store`);
});

const fieldPairs = computed(() => {
    if (!definition.value || !definition.value.fields) return [];

    const fields = definition.value.fields;
    const pairs = [];

    for (let i = 0; i < fields.length; i += 2) {
        const pair = [fields[i]];
        if (i + 1 < fields.length) {
            pair.push(fields[i + 1]);
        }
        pairs.push(pair);
    }

    return pairs;
});

const loadFormData = async () => {
    isLoading.value = true;

    try {
        const endpoint =
            props.customEndpoint ||
            (props.itemId ? route(`${props.modelName}.form-definition`, props.itemId) : route(`${props.modelName}.form-definition`));

        const response = await axios.get(endpoint);
        definition.value = response.data.formDefinition;

        if (props.itemId && response.data.item) {
            formData.value = response.data.item;
        }

        setupForm();
        emit('loaded', { formDefinition: definition.value, data: formData.value });
    } catch (error) {
        console.error(`Erro ao carregar formulário para ${props.modelName}:`, error);
    } finally {
        isLoading.value = false;
    }
};

const setupForm = () => {
    if (!definition.value || !definition.value.fields) return;

    const values = {};
    definition.value.fields.forEach((field) => {
        let value = formData.value[field.name] ?? '';

        // Conversões de tipos para campos específicos
        if (field.type === 'select' && value !== null && value !== undefined && value !== '') {
            // Garantir que o dropdown receba o valor no formato correto
            value = String(value);
        } else if (field.type === 'checkbox') {
            value = !!value;
        } else if (field.type === 'date' && value && !(value instanceof Date)) {
            value = new Date(value);
        }

        values[field.name] = value;
    });

    form.value = useForm(values);
};

const submit = () => {
    if (!form.value) return;

    const method = isEdit.value ? 'put' : 'post';

    form.value[method](formSubmitUrl.value, {
        onSuccess: () => {
            emit('submit', form.value);
            visible.value = false;
        },
        onError: (errors) => {
            console.error('Erros de validação:', errors);
        },
    });
};

const cancel = () => {
    emit('cancel');
    visible.value = false;
};

watch(
    () => [props.modelName, props.itemId],
    () => {
        if (props.modelName) {
            loadFormData();
        }
    },
    { immediate: false },
);

watch(
    () => props.showModal,
    (newValue) => {
        visible.value = newValue;
    },
);

onMounted(() => {
    if (props.modelName) {
        loadFormData();
    }
});
</script>