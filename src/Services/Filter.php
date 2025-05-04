<?php

namespace Milos\JobsApi\Services;

use Milos\JobsApi\Mappers\FilterMapper;

/*
 * each filter in the $filters array is a subarray like:
 * $filters[i] = [
 *  "property" -> the property on the object whose value is being checked
 *  "compareTo" -> the value "property is being compared to"
 *  "compareFn" -> callback function that takes "property" and "compareTo"
 *                  and returns the result of the logical operation
 *                  that was specified in the request JSON
 * ]
*/

class Filter
{
    public array $filters = [];
    public string $separator = 'and';
    public static function create(array $filterData): self
    {
        $mapper = new FilterMapper();
        return $mapper->toFilter($filterData);
    }

    public function filterData(array $data): array
    {
        $results = [];
        $add = $this->separator === 'and'; // the start value should be true if the separator is and, and false if it's or

        foreach ($data as $obj) {
            foreach ($this->filters as $filter) {
                /*
                 * passing properties to filter in the JSON request is possible
                 * for nested properties as well ("propName->nestedProp" in the request JSON)
                 * so this is handled by exploding the string and going down the chain one prop at a time.
                 * for non-nested props the foreach loop just runs once
                */
                $valToCheck = $obj;
                $props = $filter['property'];
                $nestedProps = explode('->', $props);

                foreach ($nestedProps as $prop) {
                    /*
                     * if at any point a property specified in the request JSON
                     * does not exist in the object or it's nested objects,
                     * skip any filtering for that property
                    */
                    if (!property_exists($valToCheck, $prop)) {
                        continue 2;
                    }

                    $valToCheck = $valToCheck->{$prop};
                }

                if (
                    $this->separator === 'and' &&
                    !$this->callFilterFn($filter, $valToCheck)
                ) {
                    $add = false;
                    break;
                }

                if (
                    $this->separator === 'or' &&
                    $this->callFilterFn($filter, $valToCheck)
                ) {
                    $add = true;
                    break;
                }
            }

            if ($add) {
                $results[] = $obj;
            }
            $add = $this->separator === 'and'; // reset to start value for the next object
        }

        return $results;
    }

    private function callFilterFn(array $filter, mixed $valToCheck): bool
    {
        if (isset($filter['compareToMin']) && isset($filter['compareToMax'])) {
            return $filter['compareFn']($valToCheck, $filter['compareToMin'], $filter['compareToMax']);
        }
        else {
            return $filter['compareFn']($valToCheck, $filter['compareTo']);
        }
    }
}