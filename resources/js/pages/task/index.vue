<template>
    <app-layout headerTitle="Tarefas" header-subtitle="Cadastre e gerencie suas tarefas">
        <page-header title="Tarefas" subtitle="Gerencie suas tarefas">
            <template #actions>
                <Button @click="addTask"> <Plus /> Adicionar Tarefa </Button>
            </template>
        </page-header>

        <div class="max-w-full overflow-x-auto p-4">
            <data-table
                paginator
                :lazy="true"
                :value="items.data"
                :rows="items.meta.per_page"
                :totalRecords="items.meta.total"
                :first="(items.meta.current_page - 1) * items.meta.per_page"
                sortField="completed"
                removableSort
                :sortOrder="1"
                :rowsPerPageOptions="[5, 10, 20, 50]"
                paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                currentPageReportTemplate="{first} até {last} de {totalRecords}"
                @page="onPageChange('tasks.index', $event)"
                @sort="onSortChange('tasks.index', $event)"
            >
                <template #header>
                    <div class="bg-black-100 dark:bg-black-700 flex flex-wrap gap-2 rounded-t-lg p-3">
                        <div v-for="(label, key) in filterLabels" :key="key" class="flex w-full items-center space-x-2 sm:w-auto">
                            <Search class="h-4 w-4 text-gray-500 dark:text-gray-300" />
                            <InputText v-model="filters[key]" :placeholder="label" />
                        </div>
                    </div>
                </template>

                <template #empty>
                    <div class="p-4 text-center text-gray-600 dark:text-gray-300">Nenhum registro encontrado</div>
                </template>
                <template #loading>
                    <div class="p-4 text-center text-gray-600 dark:text-gray-300">Carregando dados...</div>
                </template>

                <!-- Colunas da tabela -->
                <column field="id" header="ID" class="w-[50px] min-w-[50px] max-w-[60px] truncate text-center dark:text-gray-200"></column>
                <column field="title" header="Título" class="w-[120px] min-w-[120px] max-w-[140px] truncate dark:text-gray-200"></column>

                <column header="Responsável" class="hidden w-[120px] min-w-[100px] max-w-[120px] truncate md:table-cell">
                    <template #body="{ data }">
                        <span class="block overflow-hidden text-ellipsis whitespace-nowrap dark:text-gray-200">
                            {{ data.assignee.name }}
                        </span>
                    </template>
                </column>

                <column
                    field="priority_label"
                    header="Prioridade"
                    class="hidden w-[100px] min-w-[80px] max-w-[100px] truncate dark:text-gray-200 md:table-cell"
                ></column>
                <column
                    field="deadline"
                    sortable
                    header="Prazo"
                    class="hidden w-[110px] min-w-[90px] max-w-[110px] truncate dark:text-gray-200 lg:table-cell"
                >
                    <template #body="{ data }">
                        {{ formatDate(data.deadline) }}
                    </template>
                </column>

                <column field="completed" sortable header="Situação" class="w-[90px] min-w-[90px] max-w-[100px]">
                    <template #body="{ data }">
                        <span
                            :class="[
                                'block rounded-full px-2 py-1 text-center text-xs font-semibold',
                                data.completed
                                    ? 'bg-green-200 text-green-800 dark:bg-green-800 dark:text-green-200'
                                    : 'bg-red-200 text-red-800 dark:bg-red-800 dark:text-red-200',
                            ]"
                        >
                            {{ data.completed ? 'Concluída' : 'Pendente' }}
                        </span>
                    </template>
                </column>

                <column header="Ações" class="w-[100px] min-w-[90px] max-w-[110px] text-center">
                    <template #body="{ data }">
                        <div class="flex flex-wrap items-center justify-center gap-1">
                            <Button v-if="data.can_complete_task" variant="success" @click="completeTask(data.id)" class="p-1 md:p-2">
                                <Check class="h-3 w-3 md:h-4 md:w-4" />
                            </Button>
                            <Button v-if="data.can_edit_task" @click="editTask(data.id)" class="p-1 md:p-2">
                                <Pencil class="h-3 w-3 md:h-4 md:w-4" />
                            </Button>
                            <Button variant="destructive" @click="deleteTask(data.id)" class="p-1 md:p-2">
                                <Trash class="h-3 w-3 md:h-4 md:w-4" />
                            </Button>
                        </div>
                    </template>
                </column>
            </data-table>
        </div>

        <form-task
            v-if="showTaskForm"
            :id="taskId"
            @saved="handleTaskSaved"
            @close="
                showTaskForm = false;
                taskId = null;
            "
        />
    </app-layout>
</template>

<script setup lang="ts">
import PageHeader from '@/components/PageHeader.vue';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Task } from '@/types';
import { defaultSorting, formatDate, onFilterChange, onPageChange, onSortChange } from '@/utils/tableUtils';
import { router } from '@inertiajs/vue3';
import { Check, Pencil, Plus, Search, Trash } from 'lucide-vue-next';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import InputText from 'primevue/inputtext';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref, watch } from 'vue';
import FormTask from './forms/formTask.vue';

const toast = useToast();
const props = defineProps<{
    items: { data: Task[]; meta: any };
}>();

const filters = ref({
    id: '',
    title: '',
    assignee: '',
    priority_label: '',
    deadline: '',
    completed: '',
});

const filterLabels = {
    id: 'ID',
    title: 'Título',
    assignee: 'Responsável',
};

onMounted(() => {
    defaultSorting('tasks.index', 'completed', 'asc');
});

const showTaskForm = ref(false);
const taskId = ref<number | null>(null);

watch(
    filters,
    (newFilters) => {
        onFilterChange('tasks.index', newFilters);
    },
    { deep: true },
);

const editTask = (id: number) => {
    showTaskForm.value = true;
    taskId.value = id;
};

const handleTaskSaved = () => {
    showTaskForm.value = false;
    taskId.value = null;

    toast.add({
        severity: 'success',
        summary: 'Sucesso',
        detail: 'Tarefa salva com sucesso!',
        life: 3000,
    });
};

const addTask = () => {
    showTaskForm.value = true;
};

const deleteTask = (id: number) => {
    if (!confirm('Deseja realmente excluir essa tarefa?')) return;
    router.delete(route('tasks.destroy', id), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Sucesso',
                detail: 'Tarefa excluida com sucesso!',
                life: 3000,
            });
        },
    });
};

const completeTask = (id: number) => {
    if (!confirm('Deseja realmente concluir essa tarefa?')) return;

    router.post(
        route('tasks.complete', id),
        {},
        {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Sucesso',
                    detail: 'Tarefa concluída com sucesso!',
                    life: 3000,
                });
            },
        },
    );
};

// Variável reativa para o filtro global
const globalFilter = ref('');
</script>
