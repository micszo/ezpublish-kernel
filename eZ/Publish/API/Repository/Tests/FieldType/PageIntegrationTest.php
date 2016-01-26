<?php

/**
 * File contains: eZ\Publish\API\Repository\Tests\FieldType\PageIntegrationTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license   For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version   //autogentag//
 */
namespace eZ\Publish\API\Repository\Tests\FieldType;

use eZ\Publish\Core\FieldType\Page\Parts\Block;
use eZ\Publish\Core\FieldType\Page\Parts\Page;
use eZ\Publish\Core\FieldType\Page\Parts\Zone;
use eZ\Publish\Core\FieldType\Page\Value as PageValue;
use eZ\Publish\API\Repository\Values\Content\Field;

/**
 * Integration test for use field type.
 *
 * @group integration
 * @group field-type
 */
class PageIntegrationTest extends BaseIntegrationTest
{
    /**
     * Get name of tested field type.
     *
     * @return string
     */
    public function getTypeName()
    {
        return 'ezpage';
    }

    /**
     * Get expected settings schema.
     *
     * @return array
     */
    public function getSettingsSchema()
    {
        return array(
            'defaultLayout' => array(
                'type' => 'string',
                'default' => '',
            ),
        );
    }

    /**
     * Get a valid $fieldSettings value.
     *
     * @return mixed
     */
    public function getValidFieldSettings()
    {
        return array(
            'defaultLayout' => '',
        );
    }

    /**
     * Get $fieldSettings value not accepted by the field type.
     *
     * @return mixed
     */
    public function getInvalidFieldSettings()
    {
        return array(
            'somethingUnknown' => 0,
        );
    }

    /**
     * Get expected validator schema.
     *
     * @return array
     */
    public function getValidatorSchema()
    {
        return array();
    }

    /**
     * Get a valid $validatorConfiguration.
     *
     * @return mixed
     */
    public function getValidValidatorConfiguration()
    {
        return array();
    }

    /**
     * Get $validatorConfiguration not accepted by the field type.
     *
     * @return mixed
     */
    public function getInvalidValidatorConfiguration()
    {
        return array(
            'somethingUnknown' => 0,
        );
    }

    /**
     * Get initial field data for valid object creation.
     *
     * @return PageValue
     */
    public function getValidCreationFieldData()
    {
        $blockData = array(
            'name' => 'Block 1',
            'id' => '50dc64c82efa83cfe53959240e159915',
        );
        $block = new Block($blockData);
        $zoneData = array(
            'id' => '8386907d951657e087507f49a92bb06c',
            'identifier' => 'Zone 1',
            'blocks' => array($block),
        );
        $zone = new Zone($zoneData);
        $pageData = array(
            'zones' => array($zone),
            'layout' => 'my_layout',
        );
        $page = new Page($pageData);

        return new PageValue($page);
    }

    /**
     * Asserts that the field data was loaded correctly.
     *
     * Asserts that the data provided by {@link getValidCreationFieldData()}
     * was stored and loaded correctly.
     *
     * @param Field $field
     */
    public function assertFieldDataLoadedCorrect(Field $field)
    {
        $this->assertInstanceOf('eZ\\Publish\\Core\\FieldType\\Page\\Value', $field->value);
    }

    /**
     * Get field data which will result in errors during creation.
     *
     * This is a PHPUnit data provider.
     *
     * The returned records must contain of an error producing data value and
     * the expected exception class (from the API or SPI, not implementation
     * specific!) as the second element. For example:
     *
     * <code>
     * array(
     *      array(
     *          new DoomedValue( true ),
     *          'eZ\\Publish\\API\\Repository\\Exceptions\\ContentValidationException'
     *      ),
     *      // ...
     * );
     * </code>
     *
     * @return array[]
     */
    public function provideInvalidCreationFieldData()
    {
        return array(
            array(
                new \stdClass(),
                'eZ\\Publish\\Core\\Base\\Exceptions\\InvalidArgumentType',
            ),
        );
    }

    /**
     * Get update field externals data.
     *
     * @return PageValue
     */
    public function getValidUpdateFieldData()
    {
        return $this->getValidCreationFieldData();
    }

    /**
     * Get externals updated field data values.
     *
     * This is a PHPUnit data provider
     *
     * @param Field $field
     *
     * @return array
     */
    public function assertUpdatedFieldDataLoadedCorrect(Field $field)
    {
        $this->assertInstanceOf('eZ\\Publish\\Core\\FieldType\\Page\\Value', $field->value);

        $expectedData = array(
            'page' => $this->getValidUpdateFieldData()->page,
        );
        $this->assertPropertiesCorrect($expectedData, $field->value);
    }

    /**
     * Get field data which will result in errors during update.
     *
     * This is a PHPUnit data provider.
     *
     * The returned records must contain of an error producing data value and
     * the expected exception class (from the API or SPI, not implementation
     * specific!) as the second element. For example:
     *
     * <code>
     * array(
     *      array(
     *          new DoomedValue( true ),
     *          'eZ\\Publish\\API\\Repository\\Exceptions\\ContentValidationException'
     *      ),
     *      // ...
     * );
     * </code>
     *
     * @return array[]
     */
    public function provideInvalidUpdateFieldData()
    {
        return $this->provideInvalidCreationFieldData();
    }

    /**
     * Asserts the the field data was loaded correctly.
     *
     * Asserts that the data provided by {@link getValidCreationFieldData()}
     * was copied and loaded correctly.
     *
     * @param Field $field
     */
    public function assertCopiedFieldDataLoadedCorrectly(Field $field)
    {
        $this->assertInstanceOf('eZ\\Publish\\Core\\FieldType\\Page\\Value', $field->value);

        $expectedData = array(
            'page' => $this->getValidCreationFieldData()->page,
        );
        $this->assertPropertiesCorrect($expectedData, $field->value);
    }

    /**
     * Get data to test to hash method.
     *
     * This is a PHPUnit data provider
     *
     * The returned records must have the the original value assigned to the
     * first index and the expected hash result to the second. For example:
     *
     * <code>
     * array(
     *      array(
     *          new MyValue( true ),
     *          array( 'myValue' => true ),
     *      ),
     *      // ...
     * );
     * </code>
     *
     * @return array
     */
    public function provideToHashData()
    {
        return array(
            array(
                new PageValue(),
                0,
            ),
        );
    }

    /**
     * Get expectations for the fromHash call on our field value.
     *
     * This is a PHPUnit data provider
     *
     * @return array
     */
    public function provideFromHashData()
    {
        return array(
            array(
                array('zones' => array()),
                new PageValue(new Page()),
            ),
        );
    }

    public function providerForTestIsEmptyValue()
    {
        return array(
            array(new PageValue()),
        );
    }

    public function providerForTestIsNotEmptyValue()
    {
        return array(
            array(
                $this->getValidCreationFieldData(),
            ),
        );
    }
}
