<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    // Métodos para definir relações padrão
    public function getSearchableFields()
    {
        // Campos pesquisáveis padrão
        return ['id', 'created_at', 'updated_at'];
    }

    public function isOwner()
    {
        if (Auth::check()) {
            return Auth::id() === $this->owner_id;
        }

        return false;
    }
}
