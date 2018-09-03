<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\Album;
use App\Entity\Song;
use Carbon\Carbon;
use App\Utils\TokenGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $token;

    public function __construct(TokenGenerator $token)
    {
        $this->token = $token;
    }

    public function load(ObjectManager $manager)
    {

        $artist = new Artist;
        $album = new Album;
        $song = new Song;
        
        // load the json file
        $json = json_decode(file_get_contents(__DIR__.'/artist-albums.json'));
        
        foreach($json as $artistRow) 
        {
            $artist->setName($artistRow->name);
            $artist->setToken($this->token::generate(6));
            $manager->persist($artist);
            $manager->flush();
            

            foreach($artistRow->albums as $albumRow) 
            {
                $album->setArtist($artist);
                $album->setTitle($albumRow->title);
                $album->setCover($albumRow->cover);
                $album->setDescription($albumRow->description);
                $album->setToken($this->token::generate(6));
                $manager->persist($album);
                $manager->flush();
                $manager->detach($album);

                foreach($albumRow->songs as $songRow)
                {
                    $song->setAlbum($album);
                    $song->setTitle($songRow->title);
                    $song->setLength($this->getSeconds($songRow->length));
                    $manager->merge($song);
                }
                    $manager->flush();
            }
            $manager->detach($artist);
        }
    }

    public function getSeconds($duration)
    {
        $durationArr = explode(':', $duration);
        
        $seconds = (intval($durationArr[0]) * 60 + intval($durationArr[1]));

        return $seconds;
    }
}   