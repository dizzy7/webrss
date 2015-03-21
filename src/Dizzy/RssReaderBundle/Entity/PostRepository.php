<?php

namespace Dizzy\RssReaderBundle\Entity;

use DateInterval;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{

    /**
     * @param User     $user
     * @param Feed     $feed
     * @param DateTime $fromDate
     * @param int      $limit
     *
     * @return array
     */
    public function getPosts(User $user, Feed $feed = null, $fromId = null, $limit = 100)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('p')
            ->from('Dizzy\RssReaderBundle\Entity\Post', 'p')
            ->where(':user MEMBER OF p.unreadByUsers')
            ->orderBy('p.created', 'ASC')
            ->setMaxResults($limit)
            ->setParameter('user', $user);

        if ($feed) {
            $qb->andWhere('p.feed=:feed')
                ->setParameter('feed', $feed);
        }

        if ($fromId) {
            $qb->andWhere('p.id>:id')
                ->setParameter('id', $fromId);
        }

        return $qb->getQuery()->setHint(Query::HINT_INCLUDE_META_COLUMNS, true)->getArrayResult();
    }

    public function getPostsCountForDay(DateTime $date)
    {
        $dateFrom = clone($date);
        $dateFrom->setTime(0, 0, 0);
        $dateTo = clone($date);
        $dateTo->setTime(23, 59, 59);

        $result = $this->_em
            ->createQuery(
                "SELECT count(p) FROM Dizzy\RssReaderBundle\Entity\Post p WHERE p.fetched BETWEEN :dateFrom AND :dateTo"
            )
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('dateTo', $dateTo)
            ->getSingleScalarResult();

        return $result;
    }

    public function setPostsReaded(array $posts, User $user)
    {
        $postRepository = $this->_em->getRepository('DizzyRssReaderBundle:Post');

        $posts = $postRepository->findById($posts);

        /** @var Post $post */
        foreach ($posts as $post) {
            if ($post->getUnreadByUsers()->contains($user)) {
                $post->removeUnreadByUser($user);
            }
        }

        $this->_em->flush();

        return true;
    }


    /**
     * Возвращает статистику по количеству постов за 30 дней
     *
     * @return array
     */
    public function getPostsStat(){
        $from = new DateTime();
        $from->sub(new DateInterval('P30D'));
        $from->setTime(23,59,58);
        $to = new DateTime();
        $to->setTime(23,59,59);

        $result = [];
        /** @var DateTime $date */
        foreach (new \DatePeriod($from, new DateInterval('P1D'), $to) as $date) {
            $date1 = clone($date);
            $date1->setTime(0,0,0);
            $count = intval($this->_em->createQuery('
                SELECT COUNT(p) FROM Dizzy\RssReaderBundle\Entity\Post p WHERE p.fetched<=:date AND p.fetched>=:date1
            ')->setParameter('date',$date)->setParameter('date1',$date1)->getSingleScalarResult());
            $result['values'][] = $count;
            $result['labels'][] = $date->format('d.m.Y');
        }

        return $result;
    }
}
