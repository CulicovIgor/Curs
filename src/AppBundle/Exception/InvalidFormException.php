<?php
namespace AppBundle\Exception;

use Symfony\Component\Form\Form;

class InvalidFormException extends \RuntimeException
{
    protected $form;

    public function __construct($message, $form = null)
    {
        parent::__construct($message);
        $this->form = $form;
    }

    /**
     * @return Form|null
     */
    public function getForm()
    {
        return $this->form;
    }
}