<?php
namespace User\Model\InputFilter;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

/**
 * Filter that encapsulates all filtering and validation that
 * applies to User entities data.
 */
class UserExperienceFilter implements InputFilterAwareInterface
{
    /**
     * @var InputFilterInterface
     */
    protected $_inputFilter;

    /**
     * @see InputFilterAwareInterface
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
     * @see InputFilterAwareInterface
     */
    public function getInputFilter()
    {
        if (!$this->_inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            // @todo Add filters/validators

            $this->_inputFilter = $inputFilter;
        }
 
        return $this->_inputFilter;
    }
}
