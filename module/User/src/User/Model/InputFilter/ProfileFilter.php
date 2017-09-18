<?php
namespace User\Model\InputFilter;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

/**
 * Filter that encapsulates all filtering and validation that
 * applies to Profile entities data.
 */
class ProfileFilter implements InputFilterAwareInterface
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

            $inputFilter->add($factory->createInput(
                array(
                    'name' => 'userId',
                    'required' => true,
                    'validators' => array(
                        array(
                            'name' => 'Digits',
                        ),
                    ),
                )
            ));
            $inputFilter->add($factory->createInput(
                array(
                    'name' => 'firstName',
                    'required' => false,
                    'validators' => array(
                        array(
                            'name' => 'AlNum',
                        ),
                    ),
                )
            ));
            $inputFilter->add($factory->createInput(
                array(
                    'name' => 'lastName',
                    'required' => false,
                    'validators' => array(
                        array(
                            'name' => 'AlNum',
                        ),
                    ),
                )
            ));
            $inputFilter->add($factory->createInput(
                array(
                    'name' => 'countryCode',
                    'required' => true,
                    'validators' => array(
                        array(
                            'name' => 'NotEmpty',
                        ),
                    ),
                )
            ));
            $inputFilter->add($factory->createInput(
                array(
                    'name' => 'city',
                    'required' => false,
                    'validators' => array(
                        array(
                            'name' => 'AlNum',
                        ),
                    ),
                )
            ));
            $inputFilter->add($factory->createInput(
                array(
                    'name' => 'birthDate',
                    'required' => false,
                    'validators' => array(
                        array(
                            'name' => 'Date',
                            'options' => array(
                                'format' => 'Y-m-d',
                            ),
                        ),
                    ),
                )
            ));
            $inputFilter->add($factory->createInput(
                array(
                    'name' => 'localeCode',
                    'required' => true,
                    'validators' => array(
                        array(
                            'name' => 'NotEmpty',
                        ),
                    ),
                )
            ));
            $inputFilter->add($factory->createInput(
                array(
                    'name' => 'cv',
                    'required' => false,
                    'validators' => array(
                        array(
                            'name' => 'Uri',
                        ),
                    ),
                )
            ));
            $inputFilter->add($factory->createInput(
                array(
                    'name' => 'linkedIn',
                    'required' => true,
                    'validators' => array(
                        array(
                            'name' => 'Uri',
                        ),
                    ),
                )
            ));
            $this->_inputFilter = $inputFilter;
        }
 
        return $this->_inputFilter;
    }
}
