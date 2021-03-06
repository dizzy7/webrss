<?php

namespace Dizzy\RssReaderBundle\Interfaces;

use Dizzy\RssReaderBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImportInterface
{
    public function importFile(User $user, UploadedFile $file);
}
 