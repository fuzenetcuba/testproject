<?php

namespace BackendBundle\Twig;

use \Symfony\Component\Intl\Intl;

class LanguageExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('language', array($this, 'languageFilter')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('language', array($this, 'languageFilter')),
        );
    }

    public function languageFilter($languageCode, $locale = "en")
    {
        $c = Intl::getLanguageBundle()->getLanguageNames($locale);

        $lang = array_key_exists($languageCode, $c) ? $c[$languageCode] : $languageCode;

        return ucfirst($lang);
    }


    public function getName()
    {
        return 'language_extension';
    }

}

?>
