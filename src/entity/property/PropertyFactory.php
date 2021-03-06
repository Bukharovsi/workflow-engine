<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 03.10.14
 * Time: 16:43
 */

namespace dicom\workflow\entity\property;


use dicom\workflow\entity\property\exceptions\PropertyFactoryExceptions;
use dicom\workflow\entity\property\rule\PropertyRuleFactory;
use dicom\workflow\rules\creation\RulesFactory;

class PropertyFactory
{
    public static function create($name, $definition)
    {

        if (array_key_exists('properties', $definition)) {
            $property = static::createCompositeProperty($name, $definition);
        } else {
            $property = static::createLeafProperty($name, $definition);
        }

        return $property;
    }

    /**
     * Создать Property содержащее правила
     *
     * @param $name
     * @param $rules
     * @return Property
     */
    protected static function createLeafProperty($name, $rules)
    {
        $property = new Property($name);
        $property->setRules(RulesFactory::createBatchByShortNames($rules));

        return $property;
    }

    /**
     * Создать Property содержащее другие Property
     *
     * @param $propertyName
     * @param $definitions
     * @return CompositeProperty
     * @throws PropertyFactoryExceptions
     */
    protected static function createCompositeProperty($propertyName, $definitions)
    {
        if (!array_key_exists('properties', $definitions)) {
            throw PropertyFactoryExceptions::CompositePropertyMustContainsSubPropertiesDefinitions($propertyName, $definitions);
        }

        $property = new CompositeProperty($propertyName);
        foreach ($definitions['properties'] as $propertyName => $propertyDefinition) {
            $subProperty = PropertyFactory::create($propertyName, $propertyDefinition);
            $property->addProperty($subProperty);
        }

        return $property;
    }
} 