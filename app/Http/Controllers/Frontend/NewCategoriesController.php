<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\NewCategory;
use App\Models\CategoryTranslation;
use App\Models\DpsScan;
use Illuminate\Support\Str;

class NewCategoriesController extends Controller
{
    /**
     * Display the categories page
     */
   public function index($company_id, $config_id)
{
    $company = Company::findOrFail($company_id);
    $configuration = Configuration::findOrFail($config_id);
    
    // Ensure the configuration belongs to the company
    if ($configuration->company_id != $company_id) {
        abort(403, 'Configuration does not belong to this company');
    }
    
    // Get categories with their services ordered by order_index
    $categories = NewCategory::where('configuration_id', $config_id)
        ->where('company_id', $company_id) // Add this line to filter by company_id
        ->orderBy('order_index')
        ->get();
    
    // Load services for each category
    foreach ($categories as $category) {
        // EXPLICITLY select only the columns we need
        $dpsScans = DpsScan::select(['id', 'display_name'])
            ->where('configuration_id', $config_id)
            ->where('category', $category->identifier)
            ->where('status', 'added')
            ->whereNotNull('display_name')
            ->whereRaw("TRIM(display_name) != ''") // Ensure display_name is not empty string
            ->orderBy('display_name')
            ->get();
        
        // Extra safeguard to ensure we use ONLY display_name
        $services = collect();
        foreach ($dpsScans as $scan) {
            // Only add if display_name is a valid, non-empty string
            if (!empty($scan->display_name) && is_string($scan->display_name)) {
                $services->push((object)[
                    'id' => $scan->id,
                    'name' => $scan->display_name
                ]);
            }
        }
        
        // Assign to category
        $category->servicesList = $services;
    }
    
    return view('frontend.service-settings.categories', [
        'company' => $company,
        'configuration' => $configuration,
        'categories' => $categories,
        'activeTab' => 'categories'
    ]);
}
    
    /**
     * Store a new category
     */
    public function store(Request $request, $company_id, $config_id)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_essential' => 'boolean',
            'order_index' => 'nullable|integer'
        ]);
        
        // Generate identifier from name if not provided
        $identifier = $request->input('identifier') ?? Str::slug($request->input('name'));
        
        // Check if identifier already exists
        $existingCategory = NewCategory::where('configuration_id', $config_id)
            ->where('identifier', $identifier)
            ->first();
            
        if ($existingCategory) {
            // Append random string to make identifier unique
            $identifier = $identifier . '-' . Str::random(5);
        }
        
        // Get max order_index
        $maxOrderIndex = NewCategory::where('configuration_id', $config_id)
            ->max('order_index');
        
        // Create new category
        $category = NewCategory::create([
            'company_id' => $company_id,
            'configuration_id' => $config_id,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'identifier' => $identifier,
            'is_essential' => $request->input('is_essential', false),
            'is_default' => false, // Custom categories are not default
            'order_index' => $request->input('order_index', $maxOrderIndex + 1)
        ]);
        
        // Handle translations if provided
        if ($request->has('translations')) {
            foreach ($request->input('translations') as $lang => $fields) {
                foreach ($fields as $field => $value) {
                    CategoryTranslation::create([
                        'category_id' => $category->id,
                        'language' => $lang,
                        'field' => $field,
                        'translation' => $value
                    ]);
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'category' => $category
        ]);
    }
    
    /**
     * Update an existing category
     */
    public function update(Request $request, $company_id, $config_id, $category_id)
{
    $company = Company::findOrFail($company_id);
    $configuration = Configuration::findOrFail($config_id);
    
    // Ensure the configuration belongs to the company
    if ($configuration->company_id != $company_id) {
        return response()->json([
            'success' => false,
            'message' => 'Configuration does not belong to this company'
        ], 403);
    }
    
    $category = NewCategory::findOrFail($category_id);
    
    // Ensure the category belongs to the company and configuration
    if ($category->company_id != $company_id || $category->configuration_id != $config_id) {
        return response()->json([
            'success' => false,
            'message' => 'Category does not belong to this company or configuration'
        ], 403);
    }
    
    // Validate request
    $validated = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'description' => 'nullable|string',
        'is_essential' => 'sometimes|boolean',
        'order_index' => 'sometimes|integer'
    ]);
    
    // Update category
    $category->update([
        'name' => $request->input('name', $category->name),
        'description' => $request->input('description', $category->description),
        'is_essential' => $request->input('is_essential', $category->is_essential),
        'order_index' => $request->input('order_index', $category->order_index)
    ]);
    
    // Handle translations if provided
    if ($request->has('translations')) {
        foreach ($request->input('translations') as $lang => $fields) {
            foreach ($fields as $field => $value) {
                CategoryTranslation::updateOrCreate(
                    [
                        'category_id' => $category_id,
                        'language' => $lang,
                        'field' => $field
                    ],
                    [
                        'translation' => $value
                    ]
                );
            }
        }
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Category updated successfully',
        'category' => $category
    ]);
}
    
    /**
     * Delete a category
     */
    public function destroy($company_id, $config_id, $category_id)
    {
        $category = NewCategory::findOrFail($category_id);
        
        // Ensure the category belongs to the configuration
        if ($category->configuration_id != $config_id) {
            return response()->json([
                'success' => false,
                'message' => 'Category does not belong to this configuration'
            ], 403);
        }
        
        // Don't allow deleting default categories
        if ($category->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete default category'
            ], 400);
        }
        
        // Check if there are services using this category
        $servicesCount = DpsScan::where('configuration_id', $config_id)
            ->where('category', $category->identifier)
            ->count();
            
        if ($servicesCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with assigned services',
                'services_count' => $servicesCount
            ], 400);
        }
        
        // Delete translations
        CategoryTranslation::where('category_id', $category_id)->delete();
        
        // Delete category
        $category->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
    
    /**
     * Reorder categories
     */
    public function reorder(Request $request, $company_id, $config_id)
    {
        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'required|integer|exists:service_categories,id'
        ]);
        
        $categories = $request->input('categories');
        
        // Update order_index for each category
        foreach ($categories as $index => $categoryId) {
            NewCategory::where('id', $categoryId)
                ->where('configuration_id', $config_id)
                ->update(['order_index' => $index + 1]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Categories reordered successfully'
        ]);
    }
    
    /**
     * Toggle essential status
     */
    public function toggleEssential(Request $request, $company_id, $config_id, $category_id)
    {
        $category = NewCategory::findOrFail($category_id);
        
        // Ensure the category belongs to the configuration
        if ($category->configuration_id != $config_id) {
            return response()->json([
                'success' => false,
                'message' => 'Category does not belong to this configuration'
            ], 403);
        }
        
        // Toggle is_essential
        $category->is_essential = !$category->is_essential;
        $category->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Category essential status updated',
            'is_essential' => $category->is_essential
        ]);
    }
}