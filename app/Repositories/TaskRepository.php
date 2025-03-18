<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;

class TaskRepository extends BaseRepository
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    /**
     * Campos permitidos para filtro
     */
    protected function allowedFilters(): array
    {
        return array_merge(parent::allowedFilters(), ['title', 'description', 'completed', 'assignee']);
    }

    /**
     * Tratamento personalizado para campos específicos
     */
    protected function customSearch($query, $field, $value)
    {
        if ($field === 'assignee') {
            // Filtro por nome do responsável
            $query->whereHas('assignee', function (Builder $q) use ($value) {
                $q->where('name', 'ILIKE', '%' . $value . '%');
            });
            return true;
        }

        return false;
    }
}