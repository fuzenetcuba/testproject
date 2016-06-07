<?php

namespace BackendBundle\Twig;


class Base64Extension extends \Twig_Extension {

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('base64', array($this, 'base64Filter')),
        );
    }

    function base64Filter($blobimage) {
        $base64Image = base64_encode(stream_get_contents($blobimage));

        return $base64Image;
    }

    public function getName() {
        return 'base64_extension';
    }

}

?>
