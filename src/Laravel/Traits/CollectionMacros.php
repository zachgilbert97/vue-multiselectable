<?php

namespace ZachGilbert\VueMultiselectable\Laravel\Traits;

use Illuminate\Support\Collection;
use ZachGilbert\VueMultiselectable\Exceptions\NotMultiselectableException;
use ZachGilbert\VueMultiselectable\Traits\Multiselectable;

/**
 * trait CollectionMacros
 *
 * @package ZachGilbert\VueMultiselectable\Laravel\Traits
 */
trait CollectionMacros
{
    /**
     * Configure collection macros
     *
     * @return void
     */
    abstract protected function configureCollectionMacros();

    /**
     * @return void
     */
    protected function multiselectCollectionMacro(): void
    {
        if (!method_exists(Collection::class, 'multiselect')) {
            Collection::macro('multiselect',
                function (
                    string $trackByAttribute = 'id',
                    string $labelAttribute = null,
                    string $trackByKey = null,
                    string $labelKey = null
                ) {
                    return $this->map(
                        function ($item) use (
                            $trackByAttribute,
                            $labelAttribute,
                            $trackByKey,
                            $labelKey
                        ) {
                            if (in_array(Multiselectable::class, class_uses($item))) {
                                return $item->toMultiselectOption(
                                    $trackByAttribute,
                                    $labelAttribute,
                                    $trackByKey,
                                    $labelKey
                                );
                            }

                            throw new NotMultiselectableException(
                                'Collection item ' . get_class($item) .
                                ' must use ' . Multiselectable::class .
                                ' in order to use collection method multiselect().'
                            );
                        }
                    );
                }
            );
        }
    }

    /**
     * @return void
     */
    protected function disableOptionsByCollectionMacro(): void
    {
        if (!method_exists(Collection::class, 'disableOptionsBy')) {
            Collection::macro('disableOptionsBy',
                function (callable $callback = null) {
                    return $this->map(function ($item, $key) use ($callback) {
                        if ($callback) {
                            $item->{'$isDisabled'} = (bool) $callback($item, $key);
                        }

                        return $item;
                    });
                }
            );
        }
    }
}
