<?php

/**
 * File containing the UserStorage Gateway.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Publish\Core\FieldType\User\UserStorage;

use eZ\Publish\SPI\FieldType\StorageGateway;
use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;

abstract class Gateway extends StorageGateway
{
    /**
     * Get field data.
     *
     * The User storage handles the following attributes, following the user field
     * type in eZ Publish 4:
     * - account_key
     * - has_stored_login
     * - contentobject_id
     * - login
     * - email
     * - password_hash
     * - password_hash_type
     * - password_updated_at
     * - is_enabled
     * - is_locked
     * - last_visit
     * - login_count
     * - max_login
     *
     * @param mixed $fieldId
     * @param mixed $userId
     *
     * @return array
     */
    abstract public function getFieldData($fieldId, $userId = null);

    abstract public function storeFieldData(VersionInfo $versionInfo, Field $field): bool;

    /**
     * @param \eZ\Publish\SPI\Persistence\Content\VersionInfo $versionInfo
     * @param int[] $fieldIds
     *
     * @return bool
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    abstract public function deleteFieldData(VersionInfo $versionInfo, array $fieldIds): bool;
}
