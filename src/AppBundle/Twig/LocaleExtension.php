<?php

namespace AppBundle\Twig;

use Symfony\Component\Intl\Intl;
/**
 * This Twig extension takes the list of codes of the locales (languages)
 * enabled in the application and returns an array with the name of each
 * locale written in its own language (e.g. English, Français, Español, etc.)
 *
 * @author Julien ITARD <julienitard@gmail.com>
 */
class LocaleExtension extends \Twig_Extension
{
    private $locales;
    public function __construct($locales)
    {
        $this->locales = $locales;
    }
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('locales', array($this, 'getLocales')),
        );
    }
    public function getLocales()
    {
        $localeCodes = explode('|', $this->locales);
        foreach ($localeCodes as $localeCode) {
            $locales[] = array('code' => $localeCode, 'name' => Intl::getLocaleBundle()->getLocaleName($localeCode, $localeCode));
        }
        return $locales;
    }
    // the name of the Twig extension must be unique in the application
    public function getName()
    {
        return 'app.locale_extension';
    }
}