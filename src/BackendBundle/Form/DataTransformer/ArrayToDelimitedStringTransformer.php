<?php



namespace BackendBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
/**
 * Transforms between a delimited string (e.g. a string delimintaed
 * by commas) and an array.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class ArrayToDelimitedStringTransformer implements DataTransformerInterface
{
    private $delimiter;
    private $paddingLeft;
    private $paddingRight;
    /**
     * Constructor
     *
     * @param string $delimiter The delimiter to use when transforming from
     * a string to an array and vice-versa
     * @param integer Number of spaces before each transformed array element
     * @param integer Number of spaces after each transformed array element
     */
    public function __construct($delimiter = ',', $paddingLeft = 0, $paddingRight = 1)
    {
        $this->delimiter = $delimiter;
        $this->paddingLeft = $paddingLeft;
        $this->paddingRight = $paddingRight;
    }
    /**
     * Transforms an array into a delimited string
     *
     * @param array $array Array to transform
     *
     * @return string
     *
     * @throws TransformationFailedException If the given value is not an array
     */
    public function transform($array)
    {
        if (null === $array) {
            return '';
        }
        if (!is_array($array)) {
            throw new TransformationFailedException('Expected an array.');
        }
        foreach ($array as &$value) {
            $value = sprintf('%s%s%s',
                str_repeat(' ', $this->paddingLeft),
                $value,
                str_repeat(' ', $this->paddingRight)
            );
        }
        $string = trim(implode($this->delimiter, $array));

        return $string;
    }
    /**
     * Transforms a delimited string into an array
     *
     * @param string $string String to transform
     *
     * @return array
     *
     * @throws TransformationFailedException If the given value is not a string
     */
    public function reverseTransform($string)
    {
        if (null !== $string && !is_string($string)) {
            throw new TransformationFailedException('Expected a string.');
        }
        $string = trim($string);
        if (empty($string)) {
            return array();
        }
        $values = explode($this->delimiter, $string);
        if (0 === count($values)) {
            return array();
        }
        foreach ($values as &$value) {
            $value = trim($value);
        }
        return $values;
    }
}