<?php

namespace Yajra\Acl\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

/**
 * @mixin \Illuminate\Routing\Controller
 */
trait AuthorizesPermissionResources
{
    /**
     * Permission resource ability mapping.
     *
     * @var array
     */
    protected $resourcePermissionMap = [
        'index' => 'viewAny',
        'create' => 'create',
        'store' => 'create',
        'show' => 'view',
        'edit' => 'update',
        'update' => 'update',
        'destroy' => 'delete',
    ];

    /**
     * Authorize a permission resource action based on the incoming request.
     *
     * @return void
     */
    public function authorizePermissionResource(string $resource, array $options = [])
    {
        $permissions = $this->resourcePermissionMap();
        $collection = new Collection;
        foreach ($permissions as $method => $ability) {
            $collection->push(new Fluent([
                'ability' => $ability,
                'method' => $method,
            ]));
        }

        $collection->groupBy('ability')->each(function ($permission, $ability) use ($resource) {
            $this->middleware("can:{$resource}.{$ability}")
                ->only($permission->pluck('method')->toArray());
        });
    }

    /**
     * Get the map of permission resource methods to ability names.
     */
    protected function resourcePermissionMap(): array
    {
        if (property_exists($this, 'customPermissionMap') && is_array($this->customPermissionMap)) {
            return array_merge($this->resourcePermissionMap, $this->customPermissionMap);
        }

        return $this->resourcePermissionMap;
    }
}
