<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\MessageHandler;

use App\Domain\Membre\Message\ExcelsUploaded;
use App\Infrastructure\Data\Membre\Service\MembreImporter;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ExcelsUploadedHandler implements MessageHandlerInterface
{
    private MembreImporter $membreImporter;

    public function __construct(MembreImporter $membreImporter)
    {
        $this->membreImporter = $membreImporter;
    }

    public function __invoke(ExcelsUploaded $message)
    {
        $this->membreImporter->import($message->getImportId(), $message->getContent());
    }
}
