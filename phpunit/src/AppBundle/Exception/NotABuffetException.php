<?php
/**
 * Created by PhpStorm.
 * User: icamara
 * Date: 14/08/18
 * Time: 13:15
 */

namespace AppBundle\Exception;


class NotABuffetException extends \Exception
{

    protected $message = 'Please do not mix carnivorous and non-carnivorous, it will be a massacre!';
}