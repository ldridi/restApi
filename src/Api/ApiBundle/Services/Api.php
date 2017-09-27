<?php


namespace Api\ApiBundle\Services;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class Api
{

    protected $em;
    protected $container;

    /**
     * Api constructor.
     * @param EntityManager $em
     * @param Container $container
     */
    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;
    }


    public function users(){

        $users = $this->em->getRepository('ApiBundle:Users')->findAll();

        $formatted = [];

        foreach ($users as $user){
            $formatted[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'actif' => $user->getActif(),
                'dateCreation' => $user->getDateCreation()->format("d-m-Y"),
                'group' => $this->em->getRepository('ApiBundle:Groups')->find($user->getGroups())->getNom()
            ];
        }

        return $formatted;

    }


}