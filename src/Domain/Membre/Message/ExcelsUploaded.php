<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Membre\Message;

class ExcelsUploaded
{
    private $importId;
    private $content;

    public function __construct($importId, $content)
    {
        $this->importId = $importId;
        $this->content  = $content;
    }

    public function getImportId(): string
    {
        return $this->importId;
    }

    public function getContent()
    {
        return $this->content;
    }
}
