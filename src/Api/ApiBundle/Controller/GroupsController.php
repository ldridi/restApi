<?php

namespace Api\ApiBundle\Controller;

use Api\ApiBundle\Entity\Groups;
use Api\ApiBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GroupsController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function getGroupsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $groups = $em->getRepository('ApiBundle:Groups')->findAll();

        $formatted = [];

        foreach ($groups as $group){
            $formatted[] = [
                'id' => $group->getId(),
                'nom' => $group->getNom()
            ];
        }

        return new JsonResponse($formatted);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function showGroupAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $group = $em->getRepository('ApiBundle:Groups')->find($request->get('id'));

        if(!$group) {
            return new JsonResponse('group not found');
        }

        $formatted[] = [
            'id' => $group->getId(),
            'nom' => $group->getNom()
        ];

        return new JsonResponse($formatted);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateGroupAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $group = $em->getRepository('ApiBundle:Groups')->find($request->get('id'));

        if (!$group) {
            return new JsonResponse('group not found');
        }

        $data = json_decode($request->getContent());

        $group->setNom($data->nom);

        $em->flush();

        return new JsonResponse('group updated');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createGroupAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $group = new Groups();

        $data = json_decode($request->getContent());

        $group->setNom($data->nom);

        $em->persist($group);
        $em->flush();

        return new JsonResponse('group Created');
    }
}
