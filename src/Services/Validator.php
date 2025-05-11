<?php

namespace Milos\JobsApi\Services;

class Validator
{
    public const string REQUIRED = 'required';
    public const string MIN_LENGTH = 'min_length';
    public const string VALID_EMAIL = 'valid_email';
    public const string MATCHES = 'matches';

    private array $dataToValidate;
    private array $errors = [];

    public function __construct(array $dataToValidate)
    {
        $this->dataToValidate = $dataToValidate;
    }

    public function validate(string $entity, array $options = []): array
    {
        /*
         * get all the validation rules for the given entity (job, user, comment...),
         * go through the rules and add any error to the $errors array
         * the errors array will have a separate key for each field that did not pass validation
         *
         * $options is an array that can contain the 'check' key which specifies only certain keys
         * to run validation on
        */
        foreach ($this->getValidators()[$entity] as $validator) {
            $valToCheck = $this->dataToValidate[$validator['field']] ?? null;

            if (isset($options['check']) && !in_array($validator['field'], $options['check'])) {
                continue;
            }

            // get the rules based on which the validator functions will be retrieved
            foreach ($validator['rules'] as $rule) {
                if (is_array($rule)) {
                    $ruleFn = $this->getValidatorFunctions()[$rule[0]]['validator'];
                    $ruleErrMsg = $this->getValidatorFunctions()[$rule[0]]['error_msg'];
                    $validatorFnArg = $rule[1];

                    $ruleErrMsg = str_replace('{replace}', $validatorFnArg, $ruleErrMsg);

                    if(!$ruleFn($valToCheck, $validatorFnArg)) {
                        $this->errors[$validator['field']][] = $ruleErrMsg;
                    }
                }
                else {
                    $ruleFn = $this->getValidatorFunctions()[$rule]['validator'];
                    $ruleErrMsg = $this->getValidatorFunctions()[$rule]['error_msg'];

                    if (!$ruleFn($valToCheck)) {
                        $this->errors[$validator['field']][] = $ruleErrMsg;
                    }
                }
            }
        }

        return $this->errors;
    }

    private function getValidators(): array
    {
        return [
            'jobs' => [
                ['field' => 'jobName', 'rules' => [Validator::REQUIRED]],
                ['field' => 'description', 'rules' => [Validator::REQUIRED, [Validator::MIN_LENGTH, 10]]],
                ['field' => 'employerId', 'rules' => [Validator::REQUIRED]],
                ['field' => 'field', 'rules' => [Validator::REQUIRED]],
                ['field' => 'startSalary', 'rules' => [Validator::REQUIRED]],
                ['field' => 'shifts', 'rules' => [Validator::REQUIRED]],
                ['field' => 'location', 'rules' => [Validator::REQUIRED]],
                ['field' => 'flexibleHours', 'rules' => [Validator::REQUIRED]],
                ['field' => 'workFromHome', 'rules' => [Validator::REQUIRED]]
            ],
            'users' => [
                ['field' => 'firstName', 'rules' => [Validator::REQUIRED]],
                ['field' => 'lastName', 'rules' => [Validator::REQUIRED]],
                ['field' => 'email', 'rules' => [Validator::REQUIRED, Validator::VALID_EMAIL]],
                ['field' => 'field', 'rules' => [Validator::REQUIRED]],
                ['field' => 'password', 'rules' => [Validator::REQUIRED, [Validator::MIN_LENGTH, 8]]],
                ['field' => 'passwordConfirm', 'rules' => [Validator::REQUIRED, [Validator::MATCHES, 'password']]],
            ],
            'employers' => [
                ['field' => 'employerName', 'rules' => [Validator::REQUIRED]],
                ['field' => 'basedIn', 'rules' => [Validator::REQUIRED]],
                ['field' => 'employerDescription', 'rules' => [Validator::REQUIRED]],
            ]
        ];
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
                'error_msg' => 'this value has to be at least {replace} characters long',
            ],
            self::VALID_EMAIL => [
                'validator' => fn($val) => filter_var($val, FILTER_VALIDATE_EMAIL),
                'error_msg' => 'email address is not valid',
            ],
            self::MATCHES => [
                'validator' => fn($val, $compareVal) => $val === $this->dataToValidate[$compareVal],
                'error_msg' => 'this field must match {replace}',
            ]
        ];
    }
}