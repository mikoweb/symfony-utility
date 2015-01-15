<?php

/*
 * This file is part of the vSymfo package.
 *
 * website: www.vision-web.pl
 * (c) Rafał Mikołajun <rafal@vision-web.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace vSymfo\Core\Interfaces;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interfejs, który musi implemetować Tłumacza
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Interfaces
 */
interface TranslatableInterface
{
    /**
     * Ustaw obiekt tłumacza
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator);

    /**
     * Dostęp do obiektu tłumacza
     * @return TranslatorInterface
     */
    public function getTranslator();

    /**
     * Zwraca paramentr domain, używany w metodzie trans.
     * @return string
     */
    public function getTransDomain();

    /**
     * Translates the given message.
     *
     * @param string      $id         The message id (may also be an object that can be cast to string)
     * @param array       $parameters An array of parameters for the message
     * @param string|null $locale     The locale or null to use the default
     *
     * @return string The translated string
     */
    public function trans($id, array $parameters = array(), $locale = null);
}
