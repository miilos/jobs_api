<?php

namespace Milos\JobsApi\Services;

class Sorter
{
    public function __construct(
      private array $sortProperties,
      private string $sortDirection
    ) {}

    public function sort(array $data): array
    {
        // spread the array because to not affect the original array that is passed in
        $toSort = [...$data];

        /*
         * for each field the user wants to sort by, extract the values for that field from the objects
         * into a separate array so that it can be passed in array_multisort()
        */
        $columnVals = [];
        foreach ($this->sortProperties as $property) {
            $columnVals[$property] = array_map(function($curr) use ($property) {
                return $curr->{$property};
            }, $data);
        }

        /*
         * because we don't know how many or which fields the user wants to sort by, we don't know
         * what to pass as arguments to array_multisort(),
         * so we have to assemble the argument list like this - column and direction for each column,
         * and the array we want to perform the sorting on as the last argument
        */
        $multisortArgs = [];
        foreach ($columnVals as $column) {
            $multisortArgs[] = $column;
            $multisortArgs[] = $this->sortDirection === 'asc' ? SORT_ASC : SORT_DESC;
        }
        $multisortArgs[] = &$toSort;

        call_user_func_array('array_multisort', $multisortArgs);
        return $toSort;
    }
}