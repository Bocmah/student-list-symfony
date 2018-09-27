<?php

namespace StudentList\Validators;

use StudentList\Entities\Student;

interface StudentValidatorInterface
{
    public function validate(Student $student);
}