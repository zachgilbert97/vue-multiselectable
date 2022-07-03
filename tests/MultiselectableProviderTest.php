<?php

use Illuminate\Support\ServiceProvider;
use PHPUnit\Framework\TestCase;
use ZachGilbert\VueMultiselectable\Laravel\Providers\MultiselectProvider;

class MultiselectableProviderTest extends TestCase
{
    protected $appMock;

    protected $provider;

    function setUp(): void
    {
        $this->setUpMocks();

        $this->provider = new MultiselectProvider($this->appMock);

        parent::setUp();
    }

    function setUpMocks()
    {
        $this->appMock = \Mockery::mock(Application::class);
    }

    function test_if_provider_is_constructed()
    {
        $this->assertInstanceOf(
            MultiselectProvider::class,
            $this->provider
        );
    }

    function test_is_instance_of_service_provider()
    {
        $this->assertInstanceOf(
            ServiceProvider::class,
            $this->provider
        );
    }

    function test_has_boot_method()
    {
        $this->assertTrue(
            method_exists(MultiselectProvider::class, 'boot')
        );
    }

    function test_returns_null_in_the_boot_method()
    {
        $this->assertNull(
            $this->provider->boot()
        );
    }

    function test_has_configure_collection_macros_method()
    {
        $this->assertTrue(
            method_exists(MultiselectProvider::class, 'configureCollectionMacros')
        );
    }

    function test_has_multiselect_collection_macro_method()
    {
        $this->assertTrue(
            method_exists(MultiselectProvider::class, 'multiselectCollectionMacro')
        );
    }

    function test_has_disable_options_by_collection_macro_method()
    {
        $this->assertTrue(
            method_exists(MultiselectProvider::class, 'disableOptionsByCollectionMacro')
        );
    }

    function test_has_configure_query_builder_macros_method()
    {
        $this->assertTrue(
            method_exists(MultiselectProvider::class, 'configureQueryBuilderMacros')
        );
    }

    function test_has_multiselect_query_builder_macro_method()
    {
        $this->assertTrue(
            method_exists(MultiselectProvider::class, 'multiselectQueryBuilderMacro')
        );
    }
}
