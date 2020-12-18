<?php


namespace App\DataFixtures;

use App\Core\Services\PermessionService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PermessionFixtures extends Fixture
{
    /**
     * @var PermessionService
     */
    private PermessionService $permessionService;

    public function __construct(PermessionService $permessionService)
    {
        $this->permessionService = $permessionService;
    }

    public function load(ObjectManager $manager)
    {
        $this->permessionService->load();
    }
}