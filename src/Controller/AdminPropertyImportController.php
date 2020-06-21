<?php

namespace App\Controller;

use App\Database\DatabaseManagerInterface;
use App\Database\QueryBuilder;
use App\DataMapper\EntityMapper;
use App\Entity\Property;
use App\Entity\PropertyType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AdminPropertyImportController extends AbstractController
{
    protected HttpClientInterface $client;

    protected DatabaseManagerInterface $databaseManager;

    public function __construct(DatabaseManagerInterface $databaseManager)
    {
        $this->databaseManager = $databaseManager;
        $this->client = HttpClient::create();
    }

    /**
     * @return Response
     * @throws \ReflectionException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function import(): Response
    {
        do {
            $currentPage = $result['current_page'] ?? 0;
            $result = $this->getData($currentPage + 1);
            $this->storeData($result['data']);
        } while ($currentPage <= $result['last_page']);

        return $this->redirect('/admin/properties');
    }

    /**
     * @param $number
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function getData($number): array
    {
        $response = $this->client->request('GET', 'http://trialapi.craig.mtcdevserver.com/api/properties', [
            'query' => [
                'api_key' => $_ENV['PROPERTY_API_KEY'],
                'page' => [
                    'number' => $number,
                    'size' => 50
                ]
            ]
        ]);

        return $response->toArray();
    }

    /**
     * @param $properties
     * @throws \ReflectionException
     */
    protected function storeData($properties): void
    {
        foreach ($properties as $property) {
            $propertyType = EntityMapper::map(new PropertyType, null, $property['property_type']);

            if (empty($this->databaseManager->findOne($propertyType))) {
                $this->databaseManager->save($propertyType, [
                    'type' => QueryBuilder::INSERT
                ]);
            }

            $property = EntityMapper::map(new Property(), null, $property);

            if (null !== $result = $this->databaseManager->findOne($property, "uuid = '{$property->getUuid()}'")) {
                $property->setId($result->getId());
            }

            $this->databaseManager->save($property);
        }
    }
}
