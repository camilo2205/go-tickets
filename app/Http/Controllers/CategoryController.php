<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('children')->parents()->orderBy('name', 'asc')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'subcategories.*.name' => 'required|string|max:255',
            'subcategories.*.descripcion' => 'nullable|string',
        ]);

        // Crear la categoría principal
        $category = Category::create([
            'name' => $request->name,
            'descripcion' => $request->descripcion,
            'parent_id' => null,
        ]);

        // Crear subcategorías si existen
        if ($request->has('subcategories')) {
            foreach ($request->subcategories as $subcategoryData) {
                if (!empty($subcategoryData['name'])) {
                    Category::create([
                        'name' => $subcategoryData['name'],
                        'descripcion' => $subcategoryData['descripcion'] ?? null,
                        'parent_id' => $category->id,
                    ]);
                }
            }
        }

        return redirect()->route('categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'subcategories.*.name' => 'required|string|max:255',
            'subcategories.*.descripcion' => 'nullable|string',
        ]);

        // Actualizar la categoría
        $category->update([
            'name' => $request->name,
            'descripcion' => $request->descripcion,
        ]);

        // Gestionar subcategorías
        if ($request->has('subcategories')) {
            $submittedIds = [];
            
            foreach ($request->subcategories as $subcategoryData) {
                if (!empty($subcategoryData['name'])) {
                    if (isset($subcategoryData['id'])) {
                        // Actualizar subcategoría existente
                        $subcategory = Category::find($subcategoryData['id']);
                        if ($subcategory && $subcategory->parent_id == $category->id) {
                            $subcategory->update([
                                'name' => $subcategoryData['name'],
                                'descripcion' => $subcategoryData['descripcion'] ?? null,
                            ]);
                            $submittedIds[] = $subcategory->id;
                        }
                    } else {
                        // Crear nueva subcategoría
                        $newSubcategory = Category::create([
                            'name' => $subcategoryData['name'],
                            'descripcion' => $subcategoryData['descripcion'] ?? null,
                            'parent_id' => $category->id,
                        ]);
                        $submittedIds[] = $newSubcategory->id;
                    }
                }
            }
            
            // Eliminar subcategorías que ya no están en la lista
            Category::where('parent_id', $category->id)
                ->whereNotIn('id', $submittedIds)
                ->delete();
        } else {
            // Si no hay subcategorías enviadas, eliminar todas las existentes
            Category::where('parent_id', $category->id)
                ->delete();
        }
        return redirect()->route('categories.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }

    /**
     * Get subcategories of a specific category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubcategories(Category $category)
    {
        $subcategories = $category->children()->orderBy('name', 'asc')->get(['id', 'name']);
        return response()->json($subcategories);
    }
}
