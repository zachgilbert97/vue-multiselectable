<?php

namespace ZachGilbert\VueMultiselectable\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use ZachGilbert\VueMultiselectable\Exceptions\NotMultiselectableException;
use ZachGilbert\VueMultiselectable\Traits\Multiselectable;

/**
 * class CollectionProvider
 *
 * @package ZachGilbert\VueMultiselectable\Laravel\Providers
 */
class CollectionProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->configureCollectionMacros();
    }

    /**
     * Configure collection macros
     *
     * @return void
     */
    protected function configureCollectionMacros(): void
    {
        $this->multiselectMacro();
        $this->disableOptionsByMacro();
    }

    /**
     * @return void
     */
    protected function multiselectMacro(): void
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
                                'Item ' . get_class($item) .
                                ' does not implement trait ' .
                                Multiselectable::class
                            );
                        }
                    )
                    ->toJson();
                }
            );
        }
    }

    /**
     * @return void
     */
    protected function disableOptionsByMacro(): void
    {
        if (!method_exists(Collection::class, 'disableOptionsBy')) {
            Collection::macro('disableOptionsBy',
                function (callable $callback = null) {
                    return $this->map(function ($item, $key) use ($callback) {
                        if ($callback) {
                            $disabled = $callback($item, $key);

                            if ($disabled) $item->{'$isDisabled'} = $disabled;
                        }

                        return $item;
                    });
                }
            );
        }
    }
}
