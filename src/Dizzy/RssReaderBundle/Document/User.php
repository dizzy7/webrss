<?php

namespace Dizzy\RssReaderBundle\Document;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @MongoDB\Document(repositoryClass="Dizzy\RssReaderBundle\Document\Repositories\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Feed", mappedBy="user")
     */
    protected $feeds;

    /**
     * @MongoDB\String()
     */
    protected $mobileToken;

    /**
     * @MongoDB\Date
     */
    protected $registered;

    /**
     * @MongoDB\Date
     */
    protected $mobileTokenExpire;

    /**
     * @MongoDB\EmbedMany(targetDocument="UnreadReference")
     */
    protected $unreadFeeds;

    /**
     * @MongoDB\PrePersist()
     *
     * @return void
     */
    public function prePersist()
    {
        if ($this->registered === null) {
            $this->registered = new DateTime();
        }
    }
    public function __construct()
    {
        $this->feeds = new \Doctrine\Common\Collections\ArrayCollection();
        $this->unreadFeeds = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add feed
     *
     * @param Dizzy\RssReaderBundle\Document\Feed $feed
     */
    public function addFeed(\Dizzy\RssReaderBundle\Document\Feed $feed)
    {
        $this->feeds[] = $feed;
    }

    /**
     * Remove feed
     *
     * @param Dizzy\RssReaderBundle\Document\Feed $feed
     */
    public function removeFeed(\Dizzy\RssReaderBundle\Document\Feed $feed)
    {
        $this->feeds->removeElement($feed);
    }

    /**
     * Get feeds
     *
     * @return Collection $feeds
     */
    public function getFeeds()
    {
        return $this->feeds;
    }

    /**
     * Set mobileToken
     *
     * @param string $mobileToken
     * @return self
     */
    public function setMobileToken($mobileToken)
    {
        $this->mobileToken = $mobileToken;
        return $this;
    }

    /**
     * Get mobileToken
     *
     * @return string $mobileToken
     */
    public function getMobileToken()
    {
        return $this->mobileToken;
    }

    /**
     * Set registered
     *
     * @param date $registered
     * @return self
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;
        return $this;
    }

    /**
     * Get registered
     *
     * @return date $registered
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * Set mobileTokenExpire
     *
     * @param date $mobileTokenExpire
     * @return self
     */
    public function setMobileTokenExpire($mobileTokenExpire)
    {
        $this->mobileTokenExpire = $mobileTokenExpire;
        return $this;
    }

    /**
     * Get mobileTokenExpire
     *
     * @return date $mobileTokenExpire
     */
    public function getMobileTokenExpire()
    {
        return $this->mobileTokenExpire;
    }

    /**
     * Add unreadFeed
     *
     * @param Dizzy\RssReaderBundle\Document\UnreadReference $unreadFeed
     */
    public function addUnreadFeed(\Dizzy\RssReaderBundle\Document\UnreadReference $unreadFeed)
    {
        $this->unreadFeeds[] = $unreadFeed;
    }

    /**
     * Remove unreadFeed
     *
     * @param Dizzy\RssReaderBundle\Document\UnreadReference $unreadFeed
     */
    public function removeUnreadFeed(\Dizzy\RssReaderBundle\Document\UnreadReference $unreadFeed)
    {
        $this->unreadFeeds->removeElement($unreadFeed);
    }

    /**
     * Get unreadFeeds
     *
     * @return Collection $unreadFeeds
     */
    public function getUnreadFeeds()
    {
        return $this->unreadFeeds;
    }
}
