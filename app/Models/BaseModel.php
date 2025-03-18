<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    // Métodos genéricos para todos os modelos
    
    // Exemplo: Scope para busca genérica
    public function scopeSearch($query, $term)
    {
        // Implementação base que pode ser sobrescrita
        return $query;
    }
    
    // Métodos para definir relações padrão
    public function getSearchableFields()
    {
        // Campos pesquisáveis padrão
        return ['id', 'created_at', 'updated_at'];
    }
}