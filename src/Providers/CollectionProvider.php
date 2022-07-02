<?php

namespace Zachgilbert\LaravelVueMultiselect\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;

/**
 * class CollectionProvider
 *
 * @package Zachgilbert\LaravelVueMultiselect\Providers
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
        $this->disabledByMacro();
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
                            if (is_null($labelAttribute)) {
                                return $item->{$trackByAttribute};
                            }

                            if (!$trackByKey) $trackByKey = 'value';
                            if (!$labelKey) $labelKey = 'label';

                            $option = [
                                $trackByKey => $item->{$trackByAttribute},
                                $labelKey => $item->{$labelAttribute},
                            ];

                            if ($item->{'$isDisabled'}) {
                                $option['$isDisabled'] = $item->{'$isDisabled'};
                            }

                            return $option;
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
    protected function disabledByMacro(): void
    {
        if (!method_exists(Collection::class, 'disabledBy')) {
            Collection::macro('disabledBy',
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
