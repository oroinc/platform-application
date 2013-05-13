<?php

namespace Acme\Bundle\TestsBundle\Pages;

interface Entity
{
    /**
     * Save entity
     *
     * @return mixed
     */
    public function save();

    /**
     * Close entity
     *
     * @return mixed
     */
    public function close();
}
