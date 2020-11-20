<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Symfony\Component\Mercure\Update;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use App\Domain\Membre\Message\ExcelsUploaded;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\Membre\Repository\UserRepository;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Infrastructure\Data\Membre\Service\MembreImporter;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    private NormalizerInterface $normalizer;

    private MembreImporter $membreImporter;

    private PublisherInterface $mercurePublisher;

    private MessageBusInterface $bus;

    public function __construct(NormalizerInterface $normalizer, MembreImporter $membreImporter, PublisherInterface $mercurePublisher, MessageBusInterface $bus)
    {
        $this->normalizer       = $normalizer;
        $this->membreImporter   = $membreImporter;
        $this->mercurePublisher = $mercurePublisher;
        $this->bus              = $bus;
    }

    /**
     * @Route("/", name="default")
     */
    public function index(PublisherInterface $publisher, RouterInterface $router, Request $request, UserRepository $repository, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $x     =  $this->normalizer->normalize($users, 'export_users');

        return  $this->membreImporter->export($x);
        $filename = 'Browser_characteristics.csv';

        $spreadsheet = $this->createSpreadsheet();
        //  $contentType = 'text/csv';
        $writer   = new Csv($spreadsheet);
        $response = new StreamedResponse();
        //$response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
        $response->setPrivate();
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setCallback(function () use ($writer) {
            $writer->save('php://output');
        });

        return $response;
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
             *
        /*     if ($request->isMethod('POST')){

                      $content=$request->files->get('db');
                      if ($content){
                          $type = IOFactory::identify($content->getRealPath());
                          $objReader = IOFactory::createReader($type);
                          $objPHPExcel = $objReader->load($content->getRealPath());
                          $importId = uuid_create();
                          $importId = 123456;
                          //$this->membreImporter->import($importId,$content);
                          $this->bus->dispatch(new ExcelsUploaded($importId, $objPHPExcel));
                      }



                 return $this->render('admin/default/index.html.twig', [
                     'controller_name' => 'DefaultController',
                     'importId' => $importId,
                 ]);
                 }*/

        return $this->render('admin/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    private function publishProgress(string $importId, string $type, $data = null)
    {
        $update = new Update(
            "csv:$importId",
            json_encode(['type' => $type, 'data' => $data]),
        );

        ($this->mercurePublisher)($update);
    }
}
