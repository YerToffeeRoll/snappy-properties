<?php

namespace App\Controller;

use App\DataMapper\EntityMapper;
use App\Entity\Property;
use App\Database\DatabaseManagerInterface;
use App\Entity\PropertyType;
use App\Processor\ImageProcessor;
use App\Validation\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPropertyController extends AbstractController
{
    protected DatabaseManagerInterface $databaseManager;

    public function __construct(DatabaseManagerInterface $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $limit = 30;
        $offset = $request->query->get('page', 1) * 30;

        $properties = $this->databaseManager->findMany(new Property, '', '', $limit, $offset);

        return $this->render('admin/properties/index.html.twig', [
            'properties' => $properties['data'],
            'totalPages' => ceil($properties['count'] / $limit),
            'currentPage' => $request->query->get('page', 1)
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \ReflectionException
     */
    public function create(Request $request): Response
    {
        $propertyTypes = $this->databaseManager->findMany(new PropertyType);

        if ($request->getMethod() === Request::METHOD_POST) {
            $property = EntityMapper::map(new Property, $request);

            if (Validation::validate($property)) {
                $image = ImageProcessor::Process($request->files->get('imageFull'));

                $property->setImageFull($image['imageFull']);
                $property->setImageThumbnail($image['imageThumbnail']);

                if ($this->databaseManager->save($property)) {
                    $this->addFlash('success', 'The property has been created');

                    return $this->redirect('/admin/properties');
                }

                $this->addFlash('errors', 'There was a problem saving the property.');
            }
        }

        return $this->render('admin/properties/form.html.twig', [
            'propertyTypes' => $propertyTypes['data'],
            'errors' => Validation::getErrors()
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws \ReflectionException
     */
    public function edit(Request $request, int $id): Response
    {
        $property = (new property)->setId($id);
        $property = $this->databaseManager->findOne($property);
        $propertyTypes = $this->databaseManager->findMany(new PropertyType);

        if ($request->getMethod() === Request::METHOD_POST) {
            $property = EntityMapper::map($property, $request);

            if (Validation::validate($property)) {
                $image = ImageProcessor::Process($request->files->get('imageFull'));

                $property->setImageFull($image['imageFull']);
                $property->setImageThumbnail($image['imageThumbnail']);

                if ($this->databaseManager->save($property)) {
                    $this->addFlash('success', 'The property has been updated');

                    return $this->redirect('/admin/properties');
                }

                $this->addFlash('errors', 'There was a problem saving the property.');
            }
        }

        return $this->render('admin/properties/form.html.twig', [
            'property' => $property,
            'propertyTypes' => $propertyTypes['data'],
            'errors' => Validation::getErrors()
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $property = (new property)->setId($id);
        $result = $this->databaseManager->delete($property);

        if ($result) {
            $this->addFlash('success', 'The property has been deleted');
        } else {
            $this->addFlash('errors', 'There was a problem deleting your property.');
        }

        return $this->redirect('/admin/properties');
    }
}
