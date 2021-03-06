<?php

declare(strict_types=1);

namespace AppBundle\Entity;


use AppBundle\Exception\DinosaursAreRunningRampantException;
use AppBundle\Exception\NotABuffetException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="enclosure")
 */
class Enclosure
{
    /**
     * @var Collection
     *@ORM\OneToMany(targetEntity="AppBundle\Entity\Dinosaur", mappedBy="enclosure", cascade={"persist"})
     */
    private $dinosaurs;

    /**
     * @var Collection|Security[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Security" mappedBy="enclosure" cascade={"persist"})
     */
    private $securities;

    public function __construct(bool $withBasicSecurity = false)
    {
        $this->dinosaurs = new ArrayCollection();
        $this->securities = new ArrayCollection();

        if($withBasicSecurity){
            $this->addSecurity(new Security('Fence', true, $this));
        }
    }

    public function getDinosaurs():Collection
    {
        return $this->dinosaurs;
    }

    public function addDinosaur(Dinosaur $dinosaur)
    {
        if(!$this->canAddDinosaur($dinosaur))
        {
            throw new NotABuffetException();
        }

        if(!$this->isSecurityActive())
        {
            throw new DinosaursAreRunningRampantException('Are you craaazy?!?');
        }

        $this->dinosaurs[] = $dinosaur;
    }

    public function addSecurity(Security $security)
    {
        $this->securities[] = $security;
    }

    public function canAddDinosaur(Dinosaur $dinosaur) : bool
    {
        return count($this->dinosaurs) === 0
              || $this->dinosaurs->first()->isCarnivorous() === $dinosaur->isCarnivorous();
    }

    public function isSecurityActive() : bool
    {
        foreach ($this->securities as $security){
            if($security->getIsActive())
            {
                return true;
            }
        }

        return false;
    }


}