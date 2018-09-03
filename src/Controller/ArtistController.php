<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Artist;

class ArtistController extends AbstractController
{
    protected $em;
    
    public function __construct(EntityManagerInterface $em)
    {
    	$this->em = $em;
    }

    /**
     * @Route("/api/artist", name="artist")
     */
    public function getArtists()
    {
    	$artists = $this->em->getRepository(Artist::class)->neser();
    	
    }
}
