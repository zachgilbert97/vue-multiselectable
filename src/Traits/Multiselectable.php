<?php

namespace ZachGilbert\VueMultiselectable\Traits;

use ZachGilbert\VueMultiselectable\Exceptions\DuplicateKeysException;
use ZachGilbert\VueMultiselectable\Exceptions\MissingPropertyException;

/**
 * trait Multiselectable
 *
 * @package ZachGilbert\VueMultiselectable\Traits
 */
trait Multiselectable
{
    /**
     * @param  string  $trackByAttribute
     * @param  string  $labelAttribute
     * @param  string  $trackByKey
     * @param  string  $labelKey
     * @param  boolean $isDisabled
     *
     * @return mixed
     */
    public function toMultiselectOption(
        string $trackByAttribute,
        string $labelAttribute = null,
        string $trackByKey = null,
        string $labelKey = null,
        bool $isDisabled = null
    ) {
        if (!isset($this->{$trackByAttribute})) {
            throw new MissingPropertyException(
                "Undefined property \"$trackByAttribute\" on class " . get_class($this)
            );
        }

        if (is_null($labelAttribute)) {
            return $this->{$trackByAttribute};
        }

        if (!isset($this->{$labelAttribute})) {
            throw new MissingPropertyException(
                "Undefined property \"$labelAttribute\" on class" . get_class($this)
            );
        }

        if ($trackByKey && $labelKey && $trackByKey === $labelKey) {
            throw new DuplicateKeysException(
                "Duplicate key \"$trackByKey\" encountered on multiselect option."
            );
        }

        $option = [
            $trackByKey ?? 'value' => $this->{$trackByAttribute},
            $labelKey ?? 'label' => $this->{$labelAttribute},
        ];

        // If setting $isDisabled via this method, use this value
        if (!is_null($isDisabled)) {
            if ($isDisabled) {
                $option['$isDisabled'] = true;
            }
        }

        // If $isDisabled is already set on the instance, use this value
        else if (isset($this->{'$isDisabled'})) {
            if ($this->{'$isDisabled'}) {
                $option['$isDisabled'] = true;
            }
        }

        return $option;
    }
}
