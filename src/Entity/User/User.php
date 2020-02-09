<?php
declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Entity;

/**
 * Class Customer
 * @package App\Entity\User
 * @property-read $id
 * @property-read $username
 * @property-read $password
 * @property-read $role
 * @property-read $created_at
 * @property-read $updated_at
 */
class User extends Entity
{
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        unset($data['password']);
        return $data;
    }

}