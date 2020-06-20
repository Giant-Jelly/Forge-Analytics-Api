<?php

namespace App\Controller;

use App\ApiError\ApiError;
use App\Entity\OrderNote;
use App\Response\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/orderNotes", name="orderNotes_")
 * Class OrderNoteController
 * @package App\Controller
 */
class OrderNoteController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        $encoders = [new JsonEncoder()];

        $dateCallback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            return $innerObject instanceof \DateTime ? $innerObject->format(\DateTime::ISO8601) : '';
        };

        $relationshipCallback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            return ['id' => $innerObject->getId()];
        };

        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'createdAt' => $dateCallback,
                'order' => $relationshipCallback,
                'author' => $relationshipCallback
            ]
        ];

        $normalizer = new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext);

        $this->serializer = new Serializer([$normalizer], $encoders);
    }

    /**
     * @Route("/total", name="total")
     * @return Response
     */
    public function totalOrderNotes(): Response
    {
        $totalOrderNotes = $this->em->getRepository(OrderNote::class)->count([]);

        return new ApiResponse($totalOrderNotes);
    }

    /**
     * @Route("/orderNotes", name="orderNotes")
     * @param Request $request
     * @return Response
     * @throws ExceptionInterface
     */
    public function orderNotes(Request $request): Response
    {
        $orderNotes = $this->em->getRepository(OrderNote::class)->findBy([]);
        $params = explode(',', $request->get('params'));
        $params[] = 'id';
        $params[] = 'text';
        $params[] = 'author';
        $params[] = 'order';

        $data = [];

        foreach ($orderNotes as $orderNote) {
            $data[] = $this->serializer->normalize($orderNote, null, [AbstractNormalizer::ATTRIBUTES => $params]);
        }

        return new ApiResponse($data);
    }

    /**
     * @Route("/{orderNote}", name="orderNote")
     * @param Request $request
     * @param OrderNote|null $orderNote
     * @return Response
     * @throws ExceptionInterface
     */
    public function orderNote(Request $request, OrderNote $orderNote = null): Response
    {
        if (!$orderNote) {
            $errors[] = (new ApiError("Order Log Item not found, or no Order Log Item ID submitted", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        $params = explode(',', $request->get('params'));
        $params[] = 'id';
        $params[] = 'message';
        $params[] = 'order';

        $data = $this->serializer->normalize($orderNote, null, [AbstractNormalizer::ATTRIBUTES => $params]);

        return new ApiResponse($data);
    }
}