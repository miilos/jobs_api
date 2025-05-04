<?php

namespace Milos\JobsApi\Mappers;

use Milos\JobsApi\Core\Exceptions\APIException;
use Milos\JobsApi\Services\Filter;

class FilterMapper
{
    public function toFilter(array $data): Filter
    {
        $filter = new Filter();
        if (isset($data['separator'])) {
            $separator = strtolower($data['separator']);

            if ($separator !== 'and' && $separator !== 'or') {
                throw new APIException('the valid separators for filters are AND and OR', 400);
            }

            $filter->separator = $separator;
            unset($data['separator']);
        }

        foreach ($data as $key => $value) {
            if ($value['operation'] === 'between') {
                $filter->filters[] = [
                    'property' => $key,
                    'compareToMin' => $value['valueMin'],
                    'compareToMax' => $value['valueMax'],
                    'compareFn' => $this->getCallback()[$value['operation']]
                ];
            }
            else {
                $filter->filters[] = [
                    'property' => $key,
                    'compareTo' => $value['value'],
                    'compareFn' => $this->getCallback()[$value['operation']]
                ];
            }
        }

        return $filter;
    }

    private function getCallback(): array
    {
        return [
            '==' => fn($objVal, $compVal) => $objVal == $compVal,
            '===' => fn($objVal, $compVal) => $objVal === $compVal,
            '!=' => fn($objVal, $compVal) => $objVal != $compVal,
            '!==' => fn($objVal, $compVal) => $objVal !== $compVal,
            '>' => fn($objVal, $compVal) => $objVal > $compVal,
            '>=' => fn($objVal, $compVal) => $objVal >= $compVal,
            '<' => fn($objVal, $compVal) => $objVal < $compVal,
            '<=' => fn($objVal, $compVal) => $objVal < $compVal,
            'contains' => fn($objVal, $compVal) => str_contains($objVal, $compVal),
            'between' => fn($objVal, $compValMin, $compValMax) => ($objVal >= $compValMin && $objVal <= $compValMax),
        ];
    }
}