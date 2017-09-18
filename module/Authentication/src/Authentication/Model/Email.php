<?php
namespace Authentication\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator;

class Email
{
    private $email;

    public function exchangeArray($data)
    {
        $this->email = (!empty($data['username'])) ? $data['username'] : null;
    }

    public function isValid()
    {
        $email = new Input('email');
        $email->getValidatorChain()
            ->addValidator(new Validator\EmailAddress());

        $data = array(
            'email' => $this->email,
        );
        $inputFilter = new InputFilter();
        $inputFilter->add($email)
            ->setData($data);

        return $inputFilter->isValid();
    }

    public function getEmail()
    {
        return $this->email;
    }

}