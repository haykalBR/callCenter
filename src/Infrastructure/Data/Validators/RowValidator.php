<?php


namespace App\Infrastructure\Data\Validators;


use Symfony\Component\Validator\ConstraintViolationList;

class RowValidator
{
    /**
     * @param ConstraintViolationList $violationList
     * @return string
     */
      public function validate(ConstraintViolationList $violationList){
          $violationStr='';
          foreach ($violationList as $violation){
                $violationStr.="property:{$violation->getPropertyPath()} message:{$violation->getMessage()}  ";
          }
          return $violationStr;
      }
}