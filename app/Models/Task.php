<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Task extends BaseModel
{
    use SoftDeletes;

    public const PRIORITY_LOW = 1;
    public const PRIORITY_MEDIUM = 2;
    public const PRIORITY_HIGH = 3;

    protected static function booted()
    {
        static::addGlobalScope('owner', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('owner_id', Auth::id());
            }
        });

        static::creating(function ($task) {
            if (!$task->owner_id && Auth::check()) {
                $task->owner_id = Auth::id();
            }
        });
    }

    protected $fillable = [
        'title',
        'description',
        'priority',
        'deadline',
        'completed',
        'assignee_id',
        'owner_id',
    ];

    protected $appends = ['priority_label'];

    protected $with = ['assignee', 'owner'];

    protected $casts = [
        'completed' => 'boolean',
        'priority' => 'integer',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function getSearchableFields()
    {
        return ['title', 'description', 'completed', 'deadline'];
    }

    // Accessor para obter o rótulo da prioridade
    public function getPriorityLabelAttribute()
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => 'Baixa',
            self::PRIORITY_MEDIUM => 'Média',
            self::PRIORITY_HIGH => 'Alta',
            default => 'Não definida'
        };
    }

    public static function getPriorityOptions()
    {
        return [
            self::PRIORITY_LOW => 'Baixa',
            self::PRIORITY_MEDIUM => 'Média',
            self::PRIORITY_HIGH => 'Alta',
        ];
    }

    public static function getAssigneeOptions(): array
    {
        return User::select('id', 'name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }
}
