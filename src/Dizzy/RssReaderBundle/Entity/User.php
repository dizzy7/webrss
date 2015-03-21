<?php
namespace Dizzy\RssReaderBundle\Entity;

use DateTime;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="rss_user")
 * @ORM\Entity(repositoryClass="Dizzy\RssReaderBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Feed",inversedBy="users")
     * @ORM\JoinTable(name="rss_user_feed")
     */
    protected $feeds;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $mobileToken;

    /**
     * @ORM\ManyToMany(targetEntity="Post",mappedBy="unreadByUsers")
     */
    protected $unreadPosts;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $registered;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime",nullable=true)
     */
    protected $mobileTokenExpire;

    /**
     * @ORM\PrePersist()
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
        parent::__construct();
        // your own logic
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add feeds
     *
     * @param \Dizzy\RssReaderBundle\Entity\Feed $feeds
     *
     * @return User
     */
    public function addFeed(\Dizzy\RssReaderBundle\Entity\Feed $feeds)
    {
        $this->feeds[] = $feeds;

        return $this;
    }

    /**
     * Remove feeds
     *
     * @param \Dizzy\RssReaderBundle\Entity\Feed $feeds
     */
    public function removeFeed(\Dizzy\RssReaderBundle\Entity\Feed $feeds)
    {
        $this->feeds->removeElement($feeds);
    }

    /**
     * Get feeds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFeeds()
    {
        return $this->feeds;
    }

    /**
     * @return mixed
     */
    public function getMobileToken()
    {
        return $this->mobileToken;
    }

    /**
     * @param mixed $mobileToken
     *
     * @return $this
     */
    public function setMobileToken($mobileToken)
    {
        $this->mobileToken = $mobileToken;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getMobileTokenExpire()
    {
        return $this->mobileTokenExpire;
    }

    /**
     * @param DateTime $mobileTokenExpire
     *
     * @return $this
     */
    public function setMobileTokenExpire($mobileTokenExpire)
    {
        $this->mobileTokenExpire = $mobileTokenExpire;

        return $this;
    }


    /**
     * Add unreadPosts
     *
     * @param \Dizzy\RssReaderBundle\Entity\Post $unreadPosts
     *
     * @return User
     */
    public function addUnreadPost(\Dizzy\RssReaderBundle\Entity\Post $unreadPosts)
    {
        $this->unreadPosts[] = $unreadPosts;

        return $this;
    }

    /**
     * Remove unreadPosts
     *
     * @param \Dizzy\RssReaderBundle\Entity\Post $unreadPosts
     */
    public function removeUnreadPost(\Dizzy\RssReaderBundle\Entity\Post $unreadPosts)
    {
        $this->unreadPosts->removeElement($unreadPosts);
    }

    /**
     * Get unreadPosts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnreadPosts()
    {
        return $this->unreadPosts;
    }

    /**
     * @return mixed
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * @param mixed $registered
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;

        return $this;
    }


}
