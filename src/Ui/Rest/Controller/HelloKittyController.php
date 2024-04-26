<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace Ui\Rest\Controller;

use App\HelloKitty\Application\Command\CreateKitty\CreateKittyCommand;
use App\HelloKitty\Application\Command\RenameKitty\RenameKittyCommand;
use App\HelloKitty\Application\Query\FindAllKitties\FindAllKittiesQuery;
use App\HelloKitty\Application\Query\FindKitty\FindKittyQuery;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use Assert\Assertion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\UuidV4;

class HelloKittyController extends AbstractController
{
    #[Route('/hello-kitty', name: 'get_all_kitties', methods: ['GET'])]
    public function getAll(QueryBusInterface $bus, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize(
                $bus->dispatch(new FindAllKittiesQuery()),
                'json'
            ),
            json: true
        );
    }

    #[Route('/hello-kitty/{id}', name: 'get_one_kitty', methods: ['GET'])]
    public function getOne(QueryBusInterface $bus, SerializerInterface $serializer, string $id): JsonResponse
    {
        try {
            Assertion::uuid($id);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        $kitty = $bus->dispatch(new FindKittyQuery($id));

        if ($kitty === null) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(
            $serializer->serialize(
                $kitty,
                'json'
            ),
            json: true
        );
    }

    #[Route('/hello-kitty', name: 'create_kitty', methods: ['POST'])]
    public function create(Request $request, CommandBusInterface $bus): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            Assertion::isArray($data);
            Assertion::keyExists($data, 'name');
            Assertion::string($data['name']);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        $id = (string) UuidV4::v4();

        $bus->dispatch(new CreateKittyCommand(
            $id,
            $data['name']
        ));

        return new JsonResponse([
            'id' => $id
        ], Response::HTTP_CREATED);
    }

    #[Route('/hello-kitty/{id}', name: 'rename_kitty', methods: ['PATCH', 'PUT'])]
    public function rename(Request $request, CommandBusInterface $bus, string $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            Assertion::uuid($id);
            Assertion::isArray($data);
            Assertion::keyExists($data, 'name');
            Assertion::string($data['name']);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        $bus->dispatch(new RenameKittyCommand(
            $id,
            $data['name']
        ));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
