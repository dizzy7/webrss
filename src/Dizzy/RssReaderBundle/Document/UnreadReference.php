<?php

namespace Dizzy\RssReaderBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class UnreadReference {

    /**
     * @MongoDB\ReferenceOne(targetDocument="Feed")
     */
    private $feed;

    /**
     * @MongoDB\Int
     */
    private $unreadCount;

    /**
     * Set feed
     *
     * @param Dizzy\RssReaderBundle\Document\Feed $feed
     * @return self
     */
    public function setFeed(\Dizzy\RssReaderBundle\Document\Feed $feed)
    {
        $this->feed = $feed;
        return $this;
    }

    /**
     * Get feed
     *
     * @return Feed $feed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * Set unreadCount
     *
     * @param int $unreadCount
     * @return self
     */
    public function setUnreadCount($unreadCount)
    {
        $this->unreadCount = $unreadCount;
        return $this;
    }

    /**
     * Get unreadCount
     *
     * @return int $unreadCount
     */
    public function getUnreadCount()
    {
        return $this->unreadCount;
    }
}
