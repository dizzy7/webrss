<?php

namespace Dizzy\RssReaderBundle\Interfaces;

use Dizzy\RssReaderBundle\Document\Feed;

interface RssFetcherInterface
{
    public function fetchFeed(Feed $feed);
}
