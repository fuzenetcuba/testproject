<?php


namespace BackendBundle\Namer;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class VichUploaderNamer implements NamerInterface
{

    /**
     * Creates a name for the file being uploaded.
     *
     * @param object $object The object the upload is attached to.
     * @param PropertyMapping $mapping The mapping to use to manipulate the given object.
     *
     * @return string The file name.
     */
    public function name($object, PropertyMapping $mapping)
    {
        $file = $mapping->getFile($object);
        $extension = $file->guessExtension();

        $entity = $mapping->getMappingName();
        $alt = "img";
        switch ($entity){
            case "business_image":
                $alt = $this->slugify($object->getName());
                echo "dsfgsdf";
                break;
            case "deal_image":
                $alt = $this->slugify($object->getName());
                break;
            case "user_image":
                $alt = $this->slugify($object->getFirstName() . "-" . $object->getLastName());
                break;
            case "setting_image":
                $alt = $this->slugify($object->getWebsiteBrand());
                break;
        }

        $badChars = array(' ',',','á','é','í','ó','ú','ü','$','#','/','(','@','¿','?','/','\\','!','~','"',"'",'´');

        return uniqid(substr($alt, 0, 50) . '_') . '.' . $extension;
    }

    public static function slugify($string){
        $string = iconv( "utf-8", "us-ascii//translit//ignore", $string ); // transliterate
        $string = str_replace( "'", "", $string );
        $string = preg_replace( "~[^\pL\d]+~u", "-", $string ); // replace non letter or non digits by "-"
        $string = preg_replace( "~[^-\w]+~", "", $string ); // remove unwanted characters
        $string = preg_replace( "~-+~", "-", $string ); // remove duplicate "-"
        $string = trim( $string, "-" ); // trim "-"
        $string = trim( $string ); // trim
        $string = mb_strtolower( $string, "utf-8" ); // lowercase
        $string = urlencode( $string ); // safe
        return $string;
    }
}