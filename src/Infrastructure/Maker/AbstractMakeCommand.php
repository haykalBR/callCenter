<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Maker;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

 abstract class AbstractMakeCommand extends Command
 {
     const YES = 'Y';
     const No  = 'N';

     protected string $projectDir;

     public function __construct(string $projectDir)
     {
         parent::__construct();
         $this->projectDir = $projectDir;
     }

     /**
      * Demande à l'utilisateur de choisir un domaine.
      */
     protected function askDomain(SymfonyStyle $io): string
     {
         // On construit la liste utilisé pour l'autocompletion
         $domains = [];
         $files   = (new Finder())->in("{$this->projectDir}/src/Domain")->depth(0)->directories();

         /** @var SplFileInfo $file */
         foreach ($files as $file) {
             $domains[] = $file->getBasename();
         }

         // On pose à l'utilisateur la question
         $q = new Question('Sélectionner un domaine');
         $q->setAutocompleterValues($domains);

         return $io->askQuestion($q);
     }

     /**
      * Demande à l'utilisateur de  créer ou choisir une entity.
      */
     protected function askEntity(SymfonyStyle $io, string $domain): string
     {
         $finder     = new Finder();
         $filesystem = new Filesystem();
         if (!$filesystem->exists("{$this->projectDir}/src/Domain/{$domain}")) {
             $question = new Question('domain name not found Do you Create '.self::YES.'/'.self::No.'?');
             $reponse  =$io->askQuestion($question);
             if (self::No === mb_strtoupper($reponse)) {
                 exit();
             }
             $filesystem->mkdir("{$this->projectDir}/src/Domain/{$domain}");
         }
         if (!$filesystem->exists("{$this->projectDir}/src/Domain/{$domain}/Entity")) {
             $filesystem->mkdir("{$this->projectDir}/src/Domain/{$domain}/Entity");
         }

         $files   = $finder->in("{$this->projectDir}/src/Domain/{$domain}/Entity");
         $entities=[];
         /** @var SplFileInfo $file */
         foreach ($files as $file) {
             $entities[] = str_replace('.php', '', $file->getBasename());
         }
         // On pose à l'utilisateur la question
         $q = new Question('Creee ou Sélectionner une entity');
         $q->setAutocompleterValues($entities);

         return $io->askQuestion($q);
     }
 }
