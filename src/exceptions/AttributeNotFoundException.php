<?php
namespace micetm\conditionsBase\exceptions;

class AttributeNotFoundException extends \Exception
{
    private $attribute;

    public function __construct($attribute, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->attribute = $attribute;
        parent::__construct($message, $code, $previous);
    }

    public function getAttribute()
    {
        return $this->attribute;
    }
}
