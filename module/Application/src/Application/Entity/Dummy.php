<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Dummy, Vorlage für eigene Entities
 * @package Application\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="dummytable")
 */
class Dummy
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    protected $kundennummer;

    /**
     * @var string
     *
     * @ORM\Column(name="client", type="string", length=100)
     */
    protected $client;

    /**
     * @var string
     * @see http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html#text
     *
     * @ORM\Column(name="adresse", type="text")
     */
    protected $adresse;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getKundennummer()
    {
        return $this->kundennummer;
    }

    /**
     * @param mixed $kundennummer
     */
    public function setKundennummer($kundennummer)
    {
        $this->kundennummer = $kundennummer;
    }

    /**
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }



}