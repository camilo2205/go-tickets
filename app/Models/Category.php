<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'descripcion',
        'parent_id',
    ];

    /**
     * Relación con categoría padre
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relación con subcategorías
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Obtener todas las subcategorías recursivamente
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Verificar si es una categoría principal
     */
    public function isParent()
    {
        return is_null($this->parent_id);
    }

    /**
     * Obtener solo categorías principales
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Obtener solo subcategorías
     */
    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }
}
