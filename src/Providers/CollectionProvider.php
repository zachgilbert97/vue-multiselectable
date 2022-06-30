<?php

namespace Zachgilbert\Providers;

use Illuminate\Support\ServiceProvider;

class CollectionProvider extends ServiceProvider
{
    public function boot()
    {
        $this->configureCollectionMacros();
    }

    protected function configureCollectionMacros()
    {
        $this->singleSelectMacro();
        $this->multipleSelectMacro();
    }

    protected function singleSelectMacro()
    {
        //
    }

    protected function multipleSelectMacro()
    {
        Collection::macro('multipleSelect',
            function (
                string $labelAttribute = 'name',
                string $trackByAttribute = 'id',
                string $labelKey = 'label',
                string $trackByKey = 'value',
                function $disableCallback = null
            ) {
                return $this->map(function ($item) {
                    $array = [
                        $labelKey => $item->{$labelAttribute},
                        $trackByKey => $item->{$trackByKey},
                    ];

                    // @TODO - Apply $disabled filter

                    return $array;
                });
            }
        );
    }

    protected function groupedMultipleSelectMacro()
    {
        Collection::macro('groupedMultipleSelect',
            function () {
                //
            }
        );
    }
}
