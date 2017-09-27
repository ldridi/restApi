<?php

namespace Api\ApiBundle\Controller;

use Api\ApiBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function getUsersAction()
    {
        $users = $this->container->get('api')->users();

        return new JsonResponse($users);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function showUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ApiBundle:Users')->find($request->get('id'));

        if(!$user) {
            return new JsonResponse('user not found');
        }

        $formatted[] = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'actif' => $user->getActif(),
            'dateCreation' => $user->getDateCreation()->format("d-m-Y"),
            'group' => $em->getRepository('ApiBundle:Groups')->find($user->getGroups())->getNom()
        ];

        return new JsonResponse($formatted);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ApiBundle:Users')->find($request->get('id'));

        if (!$user) {
            return new JsonResponse('user not found');
        }

        if (!$user->getActif()) {
            return new JsonResponse('user not actif');
        }

        $data = json_decode($request->getContent());

        $user->setEmail($data->email);
        $user->setNom($data->nom);
        $user->setPrenom($data->prenom);
        $user->setActif($data->actif);

        $em->flush();

        return new JsonResponse('User updated');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new Users();

        $data = json_decode($request->getContent());

        $user->setEmail($data->email);
        $user->setNom($data->nom);
        $user->setPrenom($data->prenom);
        $user->setActif($data->actif);
        $user->setGroups($em->getRepository('ApiBundle:Groups')->find($data->groups));

        $em->persist($user);
        $em->flush();

        return new JsonResponse('User Created');
    }
}
