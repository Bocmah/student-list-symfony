<?php

namespace StudentList\Helpers;

class BinToHexHash implements HashInterface
{
    /**
     * @var int
     */
    protected $length = 32;

    /**
     * Generate hash
     *
     * @return string
     */
    public function generate()
    {
        return bin2hex(random_bytes($this->length));
    }

    /**
     * Length setter
     *
     * @param int $length
     */
    public function setLength(int $length)
    {
        $this->length = $length;
    }
}