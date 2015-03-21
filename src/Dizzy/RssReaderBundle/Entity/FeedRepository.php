<?php

namespace Dizzy\RssReaderBundle\Entity;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * FeedRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FeedRepository extends EntityRepository
{


    public function getUserFeedsWithPostsCount(User $user)
    {
        $dql = '
        SELECT f,COUNT(p) FROM Dizzy\RssReaderBundle\Entity\Feed f
        LEFT JOIN f.posts p
        WHERE
        :user MEMBER OF p.unreadByUsers
        AND
        :user MEMBER OF f.users
        GROUP BY f
        ORDER BY f.title
        ';

        $result = $this->_em->createQuery($dql)
            ->setParameter('user', $user)
            ->getArrayResult();

        return $result;
    }

    public function getUserFeeds(User $user){
        $dql = '
            SELECT f FROM Dizzy\RssReaderBundle\Entity\Feed f
            WHERE
            :user MEMBER OF f.users
        ';

        $result = $this->_em->createQuery($dql)
            ->setParameter('user',$user)
            ->getArrayResult();

        return $result;
    }
}
