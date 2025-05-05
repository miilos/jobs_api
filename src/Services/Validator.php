<?php

namespace Milos\JobsApi\Services;

class Validator
{
    public const string REQUIRED = 'required';
    public const string MIN_LENGTH = 'min_length';

    private array $dataToValidate;
    private array $errors = [];

    public function __construct(array $dataToValidate)
    {
        $this->dataToValidate = $dataToValidate;
    }

    public function validate(array $validators): array
    {
        /*
         * $validators is an array like:
         * [field, rules]
         * elements of the rules array are either constants from the Validator class,
         * or an array like [constant, extraArg] in case an extra argument is needed to pass to the validator function
        */
        foreach ($validators as $validator) {
            $valToValidate = $this->dataToValidate[$validator['field']] ?? null;

            foreach ($validator['rules'] as $rule) {
                if (is_array($rule)) {
                    $ruleFn = $this->getValidatorFunctions()[$rule[0]]['validator'];
                    $ruleErrMsg = $this->getValidatorFunctions()[$rule[0]]['error_msg'];
                    $validatorFnArg = $rule[1];

                    $ruleErrMsg = str_replace('{n}', $validatorFnArg, $ruleErrMsg);

                    if(!$ruleFn($valToValidate, $validatorFnArg)) {
                        $this->errors[$validator['field']][] = $ruleErrMsg;
                    }
                }
                else {
                    $ruleFn = $this->getValidatorFunctions()[$rule]['validator'];
                    $ruleErrMsg = $this->getValidatorFunctions()[$rule]['error_msg'];

                    if (!$ruleFn($valToValidate)) {
                        $this->errors[$validator['field']][] = $ruleErrMsg;
                    }
                }
            }
        }

        return $this->errors;
    }

    private function getValidatorFunctions(): array
    {
        return [
            self::REQUIRED => [
                'validator' => fn($val) => isset($val),
                'error_msg' => 'this value can\'t be empty',
            ],
            self::MIN_LENGTH => [
                'validator' => fn($val, $minLength) => strlen($val ?? '') >= $minLength,
                'error_msg' => 'this value has to be at least {n} characters long',
            ]
        ];
    }
}