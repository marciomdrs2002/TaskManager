<script setup>
import FormBuilder from '@/components/FormBuilder.vue';

const props = defineProps({
    id: {
        type: [Number, String],
        default: null
    }
});

const emit = defineEmits(['saved', 'close']);

const onSubmitSuccess = () => {
    emit('saved');
};

const onCancel = () => {
    emit('close');
};
</script>

<template>
    <div>
        <form-builder
            modelName="tasks"
            :itemId="id"
            :title="id ? 'Editar Tarefa' : 'Criar Task'"
            @submit="onSubmitSuccess"
            @cancel="onCancel"
        />
    </div>
</template>