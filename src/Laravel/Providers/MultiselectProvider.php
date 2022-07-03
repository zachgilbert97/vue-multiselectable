<?php

namespace ZachGilbert\VueMultiselectable\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use ZachGilbert\VueMultiselectable\Laravel\Traits\CollectionMacros;
use ZachGilbert\VueMultiselectable\Laravel\Traits\QueryBuilderMacros;
use ZachGilbert\VueMultiselectable\Traits\Multiselectable;

/**
 * class MultiselectProvider
 *
 * @package ZachGilbert\VueMultiselectable\Laravel\Providers
 */
class MultiselectProvider extends ServiceProvider
{
    use CollectionMacros, QueryBuilderMacros;

    /**
     * @return void
     */
    public function boot()
    {
        $this->configureCollectionMacros();
        $this->configureQueryBuilderMacros();
    }

    /**
     * Configure collection macros
     *
     * @return void
     */
    protected function configureCollectionMacros(): void
    {
        $this->multiselectCollectionMacro();
        $this->disableOptionsByCollectionMacro();
    }

    /**
     * Configure query builder macros
     *
     * @return void
     */
    protected function configureQueryBuilderMacros(): void
    {
        $this->multiselectQueryBuilderMacro();
    }
}
