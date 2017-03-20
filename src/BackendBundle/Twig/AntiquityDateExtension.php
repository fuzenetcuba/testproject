<?php

namespace BackendBundle\Twig;


use Symfony\Component\Translation\TranslatorInterface;

class AntiquityDateExtension extends \Twig_Extension
{
    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('antiquity_date', array($this, 'antiquityDateFilter')),
        );
    }

    function antiquityDateFilter(\DateTime $date)
    {
        $interval = $date->diff(new \DateTime('NOW'));

        $intervalText = '';

        if ($interval->y > 0) {
            $intervalText = $interval->y == 1 ?
                $this->translator->trans("+y+ year ago", array("+y+" => $interval->y), 'alertbackend') :
                $this->translator->trans("+y+ years ago", array("+y+" => $interval->y), 'alertbackend');
        } else {
            if ($interval->m > 0) {
                $intervalText = $interval->m == 1 ?
                    $this->translator->trans("+m+ month ago", array("+m+" => $interval->m), 'alertbackend') :
                    $this->translator->trans("+m+ months ago", array("+m+" => $interval->m), 'alertbackend');
            } else {
                if ($interval->d > 0) {
                    $intervalText = $interval->d == 1 ?
                        $this->translator->trans("+d+ day ago", array("+d+" => $interval->d), 'alertbackend') :
                        $this->translator->trans("+d+ days ago", array("+d+" => $interval->d), 'alertbackend');
                } else {
                    if ($interval->h > 0) {
                        $intervalText = $interval->h == 1 ?
                            $this->translator->trans("+h+ hour ago", array("+h+" => $interval->h), 'alertbackend') :
                            $this->translator->trans("+h+ hours ago", array("+h+" => $interval->h), 'alertbackend');
                    } else {
                        if ($interval->i > 0) {
                            $intervalText = $interval->m == 1 ?
                                $this->translator->trans("+i+ minute ago", array("+i+" => $interval->i), 'alertbackend') :
                                $this->translator->trans("+i+ minutes ago", array("+i+" => $interval->i), 'alertbackend');
                        } else {
                            $intervalText = $interval->s == 1 ?
                                $this->translator->trans("+s+ second ago", array("+s+" => $interval->s), 'alertbackend') :
                                $this->translator->trans("+s+ seconds ago", array("+s+" => $interval->s), 'alertbackend');
                        }
                    }
                }
            }
        }

        return $intervalText;
    }

    public function getName()
    {
        return 'antiquity_date_extension';
    }

}

?>
