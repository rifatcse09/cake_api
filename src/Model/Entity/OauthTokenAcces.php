<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OauthTokenAcces Entity
 *
 * @property int $id
 * @property int $users_id
 * @property string $token
 * @property int $expired
 * @property int $disabled
 * @property int|null $updated
 * @property int $created
 *
 * @property \App\Model\Entity\User $user
 */
class OauthTokenAcces extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'users_id' => true,
        'token' => true,
        'expired' => true,
        'disabled' => true,
        'updated' => true,
        'created' => true,
        'user' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'token',
    ];
}
