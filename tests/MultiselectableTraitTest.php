<?php

use PHPUnit\Framework\TestCase;
use ZachGilbert\VueMultiselectable\Exceptions\MissingPropertyException;
use ZachGilbert\VueMultiselectable\Traits\Multiselectable;

class MultiselectableTraitTest extends TestCase
{
    function test_has_to_multiselect_option_method()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);

        $this->assertTrue(method_exists($item, 'toMultiselectOption'));
    }

    function test_class_missing_missing_track_by_attribute()
    {
        $this->expectException(MissingPropertyException::class);

        $item = $this->getObjectForTrait(Multiselectable::class);

        $item->toMultiselectOption();
    }

    function test_class_missing_label_attribute()
    {
        $this->expectException(MissingPropertyException::class);

        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;

        $item->toMultiselectOption('id', 'name');
    }

    function test_to_multiselect_option_method_without_label_attribute_returns_expected_value()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;
        $item->name = 'test';

        $this->assertIsInt(
            $item->toMultiselectOption('id')
        );

        $this->assertIsString(
            $item->toMultiselectOption('name')
        );
    }

    function test_to_multiselect_option_method_with_label_attribute_returns_array()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;
        $item->name = 'test';

        $option = $item->toMultiselectOption('id', 'name');

        $this->assertIsArray($option);
    }

    function test_undefined_track_by_key()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;
        $item->name = 'test';

        $option = $item->toMultiselectOption('id', 'name');

        $this->assertArrayHasKey('value', $option);
    }

    function test_undefined_label_key()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;
        $item->name = 'test';

        $option = $item->toMultiselectOption('id', 'name');

        $this->assertArrayHasKey('label', $option);
    }

    function test_user_defined_track_by_key()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;
        $item->name = 'test';

        $option = $item->toMultiselectOption('id', 'name', 'foobar');

        $this->assertArrayHasKey('foobar', $option);
        $this->assertArrayHasKey('label', $option);
    }

    function test_user_defined_label_key()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;
        $item->name = 'test';

        $option = $item->toMultiselectOption('id', 'name', null, 'foobar');

        $this->assertArrayHasKey('value', $option);
        $this->assertArrayHasKey('foobar', $option);
    }

    function test_only_track_by_attribute_provided()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;
        $item->name = 'test';

        $this->assertEquals($item->toMultiselectOption('id'), $item->id);
        $this->assertEquals($item->toMultiselectOption('name'), $item->name);
    }

    function test_values_in_option_with_undefined_keys()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;
        $item->name = 'test';

        $option = $item->toMultiselectOption('id', 'name');

        $this->assertEquals($option['value'], $item->id);
        $this->assertEquals($option['label'], $item->name);
    }

    function test_values_in_option_with_user_defined_keys()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;
        $item->name = 'test';

        $option = $item->toMultiselectOption('id', 'name', 'foo', 'bar');

        $this->assertEquals($option['foo'], $item->id);
        $this->assertEquals($option['bar'], $item->name);
    }

    function test_not_disabled_item()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;
        $item->name = 'test';

        $option = $item->toMultiselectOption('id', 'name');
        $this->assertArrayNotHasKey('$isDisabled', $option);

        $option = $item->toMultiselectOption('id', 'name', null, null, true);
        $this->assertArrayHasKey('$isDisabled', $option);
        $this->assertTrue($option['$isDisabled']);

        $option = $item->toMultiselectOption('id', 'name', null, null, false);
        $this->assertArrayNotHasKey('$isDisabled', $option);
    }

    function test_intentionally_not_disabled_item()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;
        $item->name = 'test';
        $item->{'$isDisabled'} = false;

        $option = $item->toMultiselectOption('id', 'name');
        $this->assertArrayNotHasKey('$isDisabled', $option);

        $option = $item->toMultiselectOption('id', 'name', null, null, true);
        $this->assertArrayHasKey('$isDisabled', $option);
        $this->assertTrue($option['$isDisabled']);

        $option = $item->toMultiselectOption('id', 'name', null, null, false);
        $this->assertArrayNotHasKey('$isDisabled', $option);
    }

    function test_already_disabled_item()
    {
        $item = $this->getObjectForTrait(Multiselectable::class);
        $item->id = 1;
        $item->name = 'test';
        $item->{'$isDisabled'} = true;

        $option = $item->toMultiselectOption('id', 'name');
        $this->assertArrayHasKey('$isDisabled', $option);
        $this->assertTrue($option['$isDisabled']);

        $option = $item->toMultiselectOption('id', 'name', null, null, true);
        $this->assertArrayHasKey('$isDisabled', $option);
        $this->assertTrue($option['$isDisabled']);

        $option = $item->toMultiselectOption('id', 'name', null, null, false);
        $this->assertArrayNotHasKey('$isDisabled', $option);
    }
}
