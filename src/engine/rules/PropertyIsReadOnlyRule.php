<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 10/2/14
 * Time: 5:48 PM
 */

namespace dicom\workflow\engine\rules;

use dicom\workflow\engine\rules\error\IsReadOnlyRuleExecutionError;
use dicom\workflow\engine\rules\error\RuleExecutionError;
use dicom\workflow\engine\rules\executionResult\RuleExecutionResult;
use dicom\workflow\building\rules\RuleInterface\IRuleCompareTwoValueInterface;

/**
 * Class PropertyIsReadOnlyRule
 *
 * Check new value == old value
 *
 * @package dicom\workflow\rules
 */
class PropertyIsReadOnlyRule extends Rule implements IRuleCompareTwoValueInterface
{
    /**
     * проверяет соответсвует ли значение сущности условиям аттрибута
     *
     * @param $newValue
     * @param $oldValue
     * @return RuleExecutionResult
     */
    public function execute($newValue, $oldValue)
    {
        $result = new RuleExecutionResult($this);
        $isValid = $this->isValid($newValue, $oldValue);
        $result->setResult($isValid);

        if (!$isValid) {
            $result->setError($this->constructException($newValue, $oldValue));
        }

        return $result;
    }

    /**
     * Проверяет на идентичность значений
     *
     * @param null|mixed $entityNewValue
     * @param null|mixed $entityOldValue

     * @return bool
     */
    protected function isValid($entityNewValue = null, $entityOldValue = null)
    {
        return $entityOldValue == $entityNewValue;
    }

    /**
     *
     * @param null|mixed $entityNewValue
     * @param null|mixed $entityOldValue
     *
     * @return RuleExecutionError
     */
    protected function constructException($entityNewValue = null, $entityOldValue = null)
    {
        return IsReadOnlyRuleExecutionError::create($entityOldValue, $entityNewValue);
    }
}