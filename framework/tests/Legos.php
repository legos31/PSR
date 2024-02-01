<?php

namespace Framework\Tests;


class Legos
{
 public function __construct(private Depend $depend)
 {
 }

    /**
     * @return Depend
     */
    public function getDepend(): Depend
    {
        return $this->depend;
    }


}