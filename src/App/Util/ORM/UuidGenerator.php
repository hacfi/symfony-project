<?php

namespace App\Util\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

/**
 * Represents an ID generator that uses the database UUID expression
 *
 * @link http://www.percona.com/blog/2014/12/19/store-uuid-optimized-way/
 */
class UuidGenerator extends AbstractIdGenerator
{
    /**
     * Generates an ID for the given entity.
     *
     * @param EntityManager $em
     * @param object        $entity
     *
     * @return string The generated value
     */
    public function generate(EntityManager $em, $entity)
    {
        $conn = $em->getConnection();
        $sql = 'SELECT '.$conn->getDatabasePlatform()->getGuidExpression();
        $uuid = $conn->query($sql)->fetchColumn(0);

        $parts = explode('-', $uuid);

        return $parts[2].'-'.$parts[1].'-'.$parts[0].'-'.$parts[3].'-'.$parts[4];
    }
}
