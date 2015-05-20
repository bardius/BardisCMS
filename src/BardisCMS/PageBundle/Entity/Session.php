<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BardisCMS\PageBundle\Entity\Session
 *
 * @ORM\Table(name="session")
 * @ORM\Entity
 */
class Session {

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     */
    protected $session_id;

    /**
     * @ORM\Column(type="blob")
     */
    protected $session_value;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    protected $session_time;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    protected $sess_lifetime;


    /**
     * Set session_id
     *
     * @param string $sessionId
     *
     * @return Session
     */
    public function setSessionId($sessionId) {
        $this->session_id = $sessionId;

        return $this;
    }

    /**
     * Get session_id
     *
     * @return string
     */
    public function getSessionId() {
        return $this->session_id;
    }

    /**
     * Set session_value
     *
     * @param string $sessionValue
     *
     * @return Session
     */
    public function setSessionValue($sessionValue) {
        $this->session_value = $sessionValue;

        return $this;
    }

    /**
     * Get session_value
     *
     * @return string
     */
    public function getSessionValue() {
        return $this->session_value;
    }

    /**
     * Set session_lifetime
     *
     * @param integer $sessionLifetime
     *
     * @return Session
     */
    public function setSessionLifetimeime($sessionLifetime) {
        $this->session_time = $sessionLifetime;

        return $this;
    }

    /**
     * Get session_lifetime
     *
     * @return integer
     */
    public function getSessionLifetime() {
        return $this->session_lifetime;
    }

}
