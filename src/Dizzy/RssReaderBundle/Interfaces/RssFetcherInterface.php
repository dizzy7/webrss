<?php

namespace Dizzy\RssReaderBundle\Interfaces;

use Dizzy\RssReaderBundle\Entity\Feed;

interface RssFetcherInterface
{
    public function fetchFeed(Feed $feed);
}
