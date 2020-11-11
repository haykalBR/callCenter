<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Infrastructure\Data\Membre\Service\MembreImporter;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Membre\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Ods;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DefaultController extends AbstractController
{
    /**
     * @var NormalizerInterface
     */
    private NormalizerInterface $normalizer;
    /**
     * @var MembreImporter
     */
    private MembreImporter $membreImporter;

    public function __construct(NormalizerInterface $normalizer,MembreImporter $membreImporter)
     {
         $this->normalizer = $normalizer;
         $this->membreImporter = $membreImporter;
     }

    protected function createSpreadsheet()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $columnNames = [
            'Browser',
            'Developper',
            'Release date',
            'Written in',
        ];
        $columnLetter = 'A';
        foreach ($columnNames as $columnName) {
            $sheet->setCellValue($columnLetter.'1', $columnName);
            $columnLetter++;
        }


        $columnValues = [
            ['Google Chrome', 'Google Inc.', 'September 2, 2008', 'C++'],
            ['Firefox', 'Mozilla Foundation', 'September 23, 2002', 'C++, JavaScript, C, HTML, Rust'],
            ['Microsoft Edge', 'Microsoft', 'July 29, 2015', 'C++'],
            ['Safari', 'Apple', 'January 7, 2003', 'C++, Objective-C'],
            ['Opera', 'Opera Software', '1994', 'C++'],
            ['Maxthon', 'Maxthon International Ltd', 'July 23, 2007', 'C++'],
            ['Flock', 'Flock Inc.', '2005', 'C++, XML, XBL, JavaScript'],
        ];

        $i = 2; // Beginning row for active sheet
        foreach ($columnValues as $columnValue) {
            $columnLetter = 'A';
            foreach ($columnValue as $value) {
            $sheet->setCellValue($columnLetter.$i, $value);
                $columnLetter++;

            }
            $i++;
        }

        return $spreadsheet;
    }

    /**
     * @Route("/", name="test")
     */
    public function index( PublisherInterface $publisher ,RouterInterface $router, Request $request,
                           UserRepository $repository, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        return new JsonResponse(1);
        $users = $userRepository->findAll();
       // $x=  $this->normalizer->normalize($users,'export_users');
      //   return  $this->membreImporter->export($x);

/*
        //TODO nkamil nraka w nsob messanger 3ala redis  w nebda f tandhim

        $update = new Update(
            '/test',
            json_encode(['status' => 'OutOfStock'])
        );
        $publisher($update);




        $result = array_filter(array_keys($router->getRouteCollection()->all()), function ($v) {
            return preg_match('/admin_/', $v);
        });


        return $this->render('admin/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);*/
    }
}
