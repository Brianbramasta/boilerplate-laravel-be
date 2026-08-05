<?php

namespace App\Support\Scribe;

use Knuckles\Camel\Output\OutputEndpointData;
use Knuckles\Scribe\Writing\OpenApiSpecGenerators\OpenApiGenerator;

/**
 * Emits the `x-tagGroups` OpenAPI extension (supported by Scalar) so that
 * Scribe's Group > Subgroup metadata renders as nested sidebar groups
 * instead of a flat tag list.
 */
class TagGroupsGenerator extends OpenApiGenerator
{
    public function root(array $root, array $groupedEndpoints): array
    {
        $tagGroups = [];
        $subgroupTags = [];

        foreach ($groupedEndpoints as $group) {
            $endpoints = collect($group['endpoints']);

            $subgroups = $endpoints
                ->map(fn (OutputEndpointData $endpoint) => $endpoint->metadata->subgroup)
                ->filter()
                ->unique()
                ->values();

            $tags = $subgroups->all();

            if ($subgroups->isEmpty() || $endpoints->contains(fn (OutputEndpointData $e) => empty($e->metadata->subgroup))) {
                array_unshift($tags, $group['name']);
            }

            $tagGroups[] = [
                'name' => $group['name'],
                'tags' => $tags,
            ];

            foreach ($subgroups as $subgroup) {
                $subgroupTags[] = [
                    'name' => $subgroup,
                    'description' => $endpoints
                        ->first(fn (OutputEndpointData $e) => $e->metadata->subgroup === $subgroup)
                        ->metadata->subgroupDescription ?? '',
                ];
            }
        }

        $root['tags'] = array_merge($root['tags'] ?? [], $subgroupTags);
        $root['x-tagGroups'] = $tagGroups;

        return $root;
    }

    public function pathItem(array $pathItem, array $groupedEndpoints, OutputEndpointData $endpoint): array
    {
        if (! empty($endpoint->metadata->subgroup)) {
            $pathItem['tags'] = [$endpoint->metadata->subgroup];
        }

        return $pathItem;
    }
}
