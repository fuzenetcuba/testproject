<?php

namespace BackendBundle\Twig;


class UnformatPhoneExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('unformat_phone', array($this, 'unformatPhoneFilter')),
        );
    }

    function unformatPhoneFilter($phone) {
        $search = array('(',')','[',']','{','}','-','_','+','\s','\r','\n',' ');
        return str_replace($search,'',$phone);
    }

    public function getName() {
        return 'unformat_phone_extension';
    }

}

?>
