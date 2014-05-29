<?php

namespace vSymfo\Core\Library\Minify;

interface MinifyInterface
{
    /**
     *
     * @param string $html
     *
     * @return string minified html
     */
    public function minify($html);
}