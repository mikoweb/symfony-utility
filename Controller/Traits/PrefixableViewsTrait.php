<?php

/*
 * (c) Rafał Mikołajun <root@rmweb.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mikoweb\SymfonyUtility\Controller\Traits;

use Symfony\Component\HttpFoundation\Response;
use vSymfo\Core\Traits\DocumentableControllerTrait;

trait PrefixableViewsTrait
{
    /**
     * {@inheritdoc}
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        return parent::render($this->getViewPrefix() . $view, $parameters, $response);
    }

    /**
     * @return string
     */
    protected function getViewPrefix(): string
    {
        return '';
    }
}
