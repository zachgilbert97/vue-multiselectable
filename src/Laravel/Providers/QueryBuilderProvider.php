<?php

namespace ZachGilbert\VueMultiselectable\Laravel\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

/**
 * class QueryBuilderProvider
 *
 * @package ZachGilbert\VueMultiselectable\Laravel\Providers
 */
class QueryBuilderProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->configureQueryBuilderMacros();
    }

    /**
     * Configure collection macros
     *
     * @return void
     */
    protected function configureQueryBuilderMacros(): void
    {
        $this->multiselectMacro();
    }

    /**
     * @return void
     */
    protected function multiselectMacro(): void
    {
        if (!method_exists(Builder::class, 'multiselect')) {
            Builder::macro('multiselect',
                function (
                    string $trackBySelect,
                    string $labelSelect,
                    string $trackByAlias = null,
                    string $labelAlias = null
                ) {
                    $query = $this;

                    if (is_null($trackByAlias)) {
                        $trackByAlias = 'value';
                    }

                    if (is_null($labelAlias)) {
                        $labelAlias = 'label';
                    }

                    return $this->addSelect(
                        \DB::raw("$trackBySelect AS `$trackByAlias`"),
                        \DB::raw("$labelSelect AS `$labelAlias`")
                    );
                }
            );
        }
    }
}
