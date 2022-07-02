<?php

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Mockery;
use PHPUnit\Framework\TestCase;
use Zachgilbert\LaravelVueMultiselect\Providers\CollectionProvider;

class CollectionProviderTest extends TestCase
{
    protected $appMock;

    protected $provider;

    function setUp(): void
    {
        $this->setUpMocks();

        $this->provider = new CollectionProvider($this->appMock);

        parent::setUp();
    }

    function setUpMocks()
    {
        $this->appMock = Mockery::mock(Application::class);
    }

    function test_if_provider_is_constructed()
    {
        $this->assertInstanceOf(
            CollectionProvider::class,
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
            method_exists(CollectionProvider::class, 'boot')
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
            method_exists(CollectionProvider::class, 'configureCollectionMacros')
        );
    }

    // function test_has_multiselect_method()
    // {
    //     $this->assertTrue(
    //         method_exists(Collection::class, 'multiselect')
    //     );
    // }
    //
    // function test_has_disabled_by_method()
    // {
    //     $this->assertTrue(
    //         method_exists(Collection::class, 'disabledBy')
    //     );
    // }
}
