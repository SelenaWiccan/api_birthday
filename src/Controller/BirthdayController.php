<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Entity\Birthday;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

class BirthdayController extends AbstractController
{
    /**
     * @Route("/birthdays", name="birthday_list", methods={"GET"})
     */
    public function list(ManagerRegistry $doctrine): JsonResponse
    {
        $birthdays = $doctrine->getRepository(Birthday::class)->findAll();

        $normalizers = [new ObjectNormalizer(), new ArrayDenormalizer(), new DateTimeNormalizer(['format' => 'Y-m-d'])];
        $encoder = new JsonEncoder();
        $serializer = new Serializer($normalizers, [$encoder]);

        $json = $serializer->serialize($birthdays, 'json');

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }



    /**
     * @Route("/birthdays/{id}", name="birthday_show", methods={"GET"})
     */
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $birthday = $doctrine->getRepository(Birthday::class)->find($id);

        if (!$birthday) {
            throw $this->createNotFoundException('Birthday not found');
        }

        $serializer = $this->get('serializer');
        $json = $serializer->serialize($birthday, 'json');

        return new JsonResponse($json);
    }




    /**
     * @Route("/birthdays/create", name="birthday_create", methods={"POST"})
     */
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['name']) || empty($data['birthday'])) {
            return new Response('Invalid data', Response::HTTP_BAD_REQUEST);
        }

        $birthday = new Birthday();

        $birthday->setName($data['name']);
        $birthday->setBirthday(new \DateTime($data['birthday']));

        $entityManager = $doctrine->getManager();
        $entityManager->persist($birthday);
        $entityManager->flush();

        return new JsonResponse($birthday, Response::HTTP_CREATED);
    }

    /**
     * @Route("/birthdays/{id}/update", name="birthday_update", methods={"PUT"})
     */
    public function update(Request $request, int $id, ManagerRegistry $doctrine): Response
    {
        $birthday = $doctrine->getRepository(Birthday::class)->find($id);

        if (!$birthday) {
            return new Response('Birthday not found', Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data['name']) || empty($data['birthday'])) {
            return new Response('Invalid data', Response::HTTP_BAD_REQUEST);
        }

        $birthday->setName($data['name']);
        $birthday->setBirthday(new \DateTime($data['birthday']));

        $entityManager = $doctrine->getManager();
        $entityManager->flush();

        return new JsonResponse($birthday, Response::HTTP_OK);
    }



    /**
     * @Route("/birthdays/{id}", name="birthday_delete", methods={"DELETE"})
     */
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $birthday = $doctrine->getRepository(Birthday::class)->find($id);

        if (!$birthday) {
            return new Response('Birthday not found', Response::HTTP_NOT_FOUND);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($birthday);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }


}
