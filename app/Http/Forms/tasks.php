<?php
return [
    'fields' => [
        [
            'name' => 'title',
            'label' => 'Título',
            'type' => 'text',
            'required' => true,
            'placeholder' => 'Digite o título da tarefa',
        ],
        [
            'name' => 'description',
            'label' => 'Descrição',
            'type' => 'textarea',
            'required' => false,
            'placeholder' => 'Digite a descrição detalhada',
            'rows' => 3,
        ],
        [
            'name' => 'priority',
            'label' => 'Prioridade',
            'type' => 'select',
            'required' => true,
            'dynamicOptions' => 'getPriorityOptions',
            'placeholder' => 'Selecione a prioridade',
        ],
        [
            'name' => 'deadline',
            'label' => 'Data de Entrega',
            'type' => 'date',
            'required' => false,
        ],
        [
            'name' => 'assignee_id',
            'label' => 'Responsável',
            'type' => 'select',
            'required' => false,
            'dynamicOptions' => 'getAssigneeOptions',
            'placeholder' => 'Selecione um responsável',
        ],
    ],
    'actions' => [
        [
            'label' => 'Salvar',
            'type' => 'submit',
            'color' => 'primary',
        ],
        [
            'label' => 'Cancelar',
            'type' => 'button',
            'action' => 'close',
            'color' => 'secondary',
        ],
    ],
];
