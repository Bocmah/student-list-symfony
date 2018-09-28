<?php

namespace StudentList\Controllers;

use Symfony\Component\HttpFoundation\Request;
use StudentList\Database\StudentDataGateway;
use StudentList\Entities\Student;
use StudentList\Validators\StudentValidatorInterface;

class ProfileController extends BaseController
{
    /**
     * @var StudentDataGateway
     */
    protected $studentDataGateway;

    /**
     * @var StudentValidatorInterface
     */
    protected $studentValidator;

    public function __construct(
        StudentDataGateway $studentDataGateway,
        StudentValidatorInterface $studentValidator
    ) {
        $this->studentDataGateway = $studentDataGateway;
        $this->studentValidator = $studentValidator;
    }

    /**
     * Show authorized user's profile
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $studentData = $this->studentDataGateway->getStudentByHash($request->cookies->get("hash"));
        $params["values"] = $studentData;

        return $this->render("profile.view.php", $params);
    }

    /**
     * Show profile edit page
     *
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request)
    {
        $studentData = $this->studentDataGateway->getStudentByHash($request->cookies->get("hash"));
        $params["values"] = $studentData;

        return $this->render("register.view.php", $params);
    }

    /**
     * Update authorized user's profile
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $values = [];

        $values["name"] = trim($request->request->get("name", ""));
        $values["surname"] = trim($request->request->get("surname", ""));
        $values["birth_year"] = $request->request->getInt("birth_year");
        $values["gender"] = $request->request->get("gender", "");
        $values["group_number"] = trim($request->request->get("group_number", ""));
        $values["exam_score"] = $request->request->getInt("exam_score");
        $values["email"] = trim($request->request->get("email", ""));
        $values["residence"] = $request->request->get("residence", "");

        $student = new Student(
            $values["name"],
            $values["surname"],
            $values["group_number"],
            $values["email"],
            $values["exam_score"],
            $values["birth_year"],
            $values["gender"],
            $values["residence"]
        );

        $errors = $this->studentValidator->validate($student);

        if (empty($errors)) {
            $student->setHash($request->cookies->get("hash"));
            $this->studentDataGateway->updateStudent($student);

            return $this->redirect("/?notify=1");
        } else {
            // Re-render the form passing $errors and $values arrays
            $params = compact("values", "errors");

            return $this->render("register.view.php", $params);
        }
    }
}