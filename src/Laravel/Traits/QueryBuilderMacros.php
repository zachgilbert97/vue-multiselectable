<?php

namespace ZachGilbert\VueMultiselectable\Laravel\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;

/**
 * class QueryBuilderProvider
 *
 * @package ZachGilbert\VueMultiselectable\Laravel\Traits
 */
trait QueryBuilderMacros
{
    /**
     * Configure collection macros
     *
     * @return void
     */
    abstract protected function configureQueryBuilderMacros();

    /**
     * @return void
     */
    protected function multiselectQueryBuilderMacro(): void
    {
        if (!method_exists(Builder::class, 'multiselect')) {
            Builder::macro('multiselect',
                function (
                    string $trackBySelect,
                    string $labelSelect,
                    string $trackByAlias = 'value',
                    string $labelAlias = 'label'
                ) {                    
                    return $this
                        ->selectRaw("$trackBySelect AS `$trackByAlias`")
                        ->selectRaw("$labelSelect AS `$labelAlias`");
                }
            );
        }
    }
}
