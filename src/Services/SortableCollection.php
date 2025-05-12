<?php

namespace Milos\JobsApi\Services;

trait SortableCollection
{
    public function sort(array $DTOs, array $sortProps): array
    {
        $sorter = new Sorter($sortProps['properties'], $sortProps['direction']);
        return $sorter->sort($DTOs);
    }
}