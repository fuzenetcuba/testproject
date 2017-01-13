<?php

namespace BackendBundle\Twig;


class JsonDecodeExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('json_decode', array($this, 'jsonDecodeFilter')),
        );
    }

    function jsonDecodeFilter($jsonEncoded) {
        return json_decode($jsonEncoded);
    }

    public function getName() {
        return 'json_decode_extension';
    }

}

?>
