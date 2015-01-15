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

namespace vSymfo\Core\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Translation\TranslatorInterface;
use vSymfo\Core\Interfaces\TranslatableInterface;

/**
 * Typ formularza, który implementuje Tłumacza
 * @author Rafał Mikołajun <rafal@vision-web.pl>
 * @package vSymfo Core
 * @subpackage Form
 */
abstract class TransTypeAbstract extends AbstractType implements TranslatableInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Ustaw obiekt tłumacza
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Dostęp do obiektu tłumacza
     * @return TranslatorInterface
     * @throws \Exception
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Translates the given message.
     *
     * @param string      $id         The message id (may also be an object that can be cast to string)
     * @param array       $parameters An array of parameters for the message
     * @param string|null $locale     The locale or null to use the default
     *
     * @return string The translated string
     */
    public function trans($id, array $parameters = array(), $locale = null)
    {
        if (!empty($this->translator)) {
            return $this->getTranslator()->trans($id, $parameters, $this->getTransDomain(), $locale);
        }

        return $id;
    }
}
