<?php

namespace Dizzy\RssReaderBundle\Interfaces;

use Dizzy\RssReaderBundle\Document\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImportInterface
{
    public function importFile(User $user, UploadedFile $file);
}
 