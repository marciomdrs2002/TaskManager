<?php

namespace App\Repositories;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class BaseRepository
{
    protected $model;
    
    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }
    
    public function all(): Collection
    {
        return $this->model->all();
    }
    
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }
    
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function getModel(): Model
    {
        return $this->model;
    }
    
    public function update($id, array $attributes)
    {
        $entity = $this->findById($id);
        $entity->update($attributes);
        return $entity;
    }
    
    public function delete($id)
    {
        return $this->model->destroy($id);
    }
    
    public function paginate($perPage = 15)
    {
        return $this->model->paginate($perPage);
    }
    
    /**
     * Retorna a lista de campos permitidos para filtro
     * Deve ser sobrescrita nas classes filhas
     */
    protected function allowedFilters(): array
    {
        return ['id', 'created_at', 'updated_at'];
    }
    
    /**
     * Permite personalizar a lógica de busca para campos específicos
     * Pode ser sobrescrita nas classes filhas
     */
    protected function customSearch($query, $field, $value)
    {
        return false; // Retorna false se não houver tratamento customizado
    }

    /**
     * Aplica os filtros à query
     */
    protected function applyFilters($query, Request $request)
    {
        // Obtém apenas os filtros permitidos
        $allowedFilters = $this->allowedFilters();
        $filters = $request->only($allowedFilters);

        foreach ($filters as $field => $value) {
            if ($value !== null && $value !== '') {
                // Verifica se existe um tratamento customizado para este campo
                $customHandled = $this->customSearch($query, $field, $value);
                
                if (!$customHandled) {
                    // Aplica um filtro ILIKE básico para o campo
                    $query->where($field, 'ILIKE', '%' . $value . '%');
                }
            }
        }
        
        return $query;
    }
    
    /**
     * Encontra registros com filtros e paginação
     */
    public function findWithFilters(Request $request)
    {
        $query = $this->model->newQuery();

        $this->applyFilters($query, $request);

        if ($request->has('sort') && $request->has('direction')) {
            $query->orderBy($request->sort, $request->direction);
        }

        return $query->paginate($request->get('per_page', 5));
    }
}