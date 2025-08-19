<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetaData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MetaDashboardController extends Controller
{
    public function index()
    {
        try {
            // Get distinct parent types with count
            $metaGroups = MetaData::select('parent_type', DB::raw('COUNT(DISTINCT parent_id) as count'))
                ->groupBy('parent_type')
                ->get();
                
            // If we don't have any metadata, create an empty collection
            if ($metaGroups->isEmpty()) {
                $metaGroups = collect();
            } else {
                $metaGroups = $metaGroups->map(function ($item) {
                    // Convert full namespace to readable name
                    $modelName = class_basename($item->parent_type);
                    // Convert to URL-friendly format (lowercase plural)
                    $typeSlug = Str::plural(Str::lower($modelName));
                    
                    return [
                        'model_name' => $modelName,
                        'slug' => $typeSlug,
                        'count' => $item->count,
                        'full_type' => $item->parent_type
                    ];
                });
            }
            
            return view('admin.meta.dashboard', [
                'metaGroups' => $metaGroups
            ]);
        } catch (\Exception $e) {
            // Log the error but render the view anyway with an empty collection
            logger()->error('Error in metadata dashboard: ' . $e->getMessage());
            return view('admin.meta.dashboard', [
                'metaGroups' => collect(),
                'error' => $e->getMessage()
            ]);
        }
    }

    public function typeList($type)
    {
        // Map common types to their model classes
        $typeMap = [
            'users' => 'App\\Models\\User',
            'clients' => 'App\\Models\\Client',
            'tickets' => 'App\\Models\\Ticket',
            // Add more mappings as needed
        ];
        
        // Try to determine the model class from the type
        $modelClass = $typeMap[$type] ?? ('App\\Models\\' . Str::studly(Str::singular($type)));
        
        if (!class_exists($modelClass)) {
            return back()->with('error', "Model class {$modelClass} does not exist.");
        }
        
        // Get entities with metadata
        $entityIds = MetaData::where('parent_type', $modelClass)
            ->select('parent_id', DB::raw('COUNT(id) as meta_count'))
            ->groupBy('parent_id')
            ->get();
        
        // For each entity ID, try to load the actual entity
        $entities = [];
        $model = app($modelClass);
        foreach ($entityIds as $entityItem) {
            $entity = $model->find($entityItem->parent_id);
            if ($entity) {
                $displayName = method_exists($entity, 'getDisplayName') 
                    ? $entity->getDisplayName() 
                    : ($entity->name ?? ($entity->title ?? "#{$entityItem->parent_id}"));
                
                $entities[] = [
                    'id' => $entity->id,
                    'name' => $displayName,
                    'meta_count' => $entityItem->meta_count
                ];
            }
        }
        
        return view('admin.meta.type-list', [
            'type' => $type,
            'modelName' => class_basename($modelClass),
            'entities' => collect($entities)
        ]);
    }

    public function entityDetail($type, $id)
    {
        // Map common types to their model classes
        $typeMap = [
            'users' => 'App\\Models\\User',
            'clients' => 'App\\Models\\Client',
            'tickets' => 'App\\Models\\Ticket',
            // Add more mappings as needed
        ];
        
        // Try to determine the model class from the type
        $className = $typeMap[$type] ?? ('App\\Models\\' . Str::studly(Str::singular($type)));
        
        if (!class_exists($className)) {
            return back()->with('error', "Model class {$className} does not exist.");
        }
        
        // Try to load the entity
        $entity = $className::find($id);
        
        if (!$entity) {
            return back()->with('error', "Entity not found.");
        }
        
        $entityName = $type . ' #' . $id;
        if (method_exists($entity, 'getDisplayName')) {
            $entityName = $entity->getDisplayName();
        }
        
        $metaData = MetaData::where('parent_type', $className)
            ->where('parent_id', $id)
            ->get();
        
        return view('admin.meta.index', [
            'entity' => $entity,
            'entityName' => $entityName,
            'type' => $type,
            'id' => $id,
            'metaData' => $metaData
        ]);
    }

    public function store($type, $id)
    {
        // Implementation for adding new metadata
    }

    public function update($type, $id, $metaId)
    {
        // Implementation for updating metadata
    }

    public function destroy($type, $id, $metaId)
    {
        // Implementation for deleting metadata
    }
    
    public function cleanupModule($module)
    {
        $count = MetaData::where('module', $module)->delete();
        
        return back()->with('success', "{$count} metadata entries were removed for module '{$module}'.");
    }
}
