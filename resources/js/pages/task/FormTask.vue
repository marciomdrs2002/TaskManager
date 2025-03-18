<script setup>
import { onMounted, ref, computed } from 'vue';
import FormBuilder from '@/components/FormBuilder.vue';
import axios from 'axios';

const props = defineProps({
    id: {
        type: [Number, String],
        default: null
    }
});

const emit = defineEmits(['saved', 'close']);

const formDefinition = ref(null);
const taskData = ref(null);
const isLoading = ref(true);

const isEdit = computed(() => !!props.id);
const pageTitle = computed(() => isEdit.value ? 'Editar Tarefa' : 'Nova Tarefa');
const submitMethod = computed(() => isEdit.value ? 'put' : 'post');
const submitUrl = computed(() => {
    if (isEdit.value) {
        return route('tasks.update', props.id);
    }
    return route('tasks.store');
});

onMounted(async () => {
    await loadFormData();
});

// Carregar os dados do formulário
const loadFormData = async () => {
    isLoading.value = true;
    
    try {
        const endpoint = props.id 
            ? route('tasks.form-definition', props.id) 
            : route('tasks.form-definition');
            
        const response = await axios.get(endpoint);
        formDefinition.value = response.data.formDefinition;
        
        if (props.id && response.data.item) {
            taskData.value = response.data.item;
        } else {
            taskData.value = {};
        }
    } catch (error) {
        console.error('Erro ao carregar dados do formulário:', error);
    } finally {
        isLoading.value = false;
    }
};

const onSubmitSuccess = () => {
    emit('saved');
};

const onCancel = () => {
    emit('close');
};
</script>

<template>
    <div>
        
        <div v-if="isLoading" class="flex justify-center items-center h-60">
            <div class="text-gray-500 dark:text-gray-400">Carregando formulário...</div>
        </div>
        
        <div v-else-if="formDefinition">
            <form-builder
                :form-definition="formDefinition"
                :initial-values="taskData"
                :submit-url="submitUrl"
                :method="submitMethod"
                :title="pageTitle"
                @submit="onSubmitSuccess"
                @cancel="onCancel"
            />
        </div>
        <div v-else class="text-red-500 dark:text-red-400">
            Erro ao carregar formulário. Tente novamente mais tarde.
        </div>
    </div>
</template>