<?php

namespace StudentList\Controllers;

use StudentList\AuthManager;
use StudentList\Helpers\HashInterface;
use StudentList\Validators\StudentValidatorInterface;
use StudentList\Database\StudentDataGateway;
use Symfony\Component\HttpFoundation\Request;
use StudentList\Entities\Student;

class RegisterController extends BaseController
{
    /**
     * @var StudentValidatorInterface
     */
    protected $studentValidator;

    /**
     * @var HashInterface
     */
    protected $hash;

    /**
     * @var StudentDataGateway
     */
    protected $studentDataGateway;

    /**
     * @var AuthManager
     */
    protected $authManager;

    /**
     * Create a new RegisterController instance
     *
     * @param StudentValidatorInterface $studentValidator
     * @param HashInterface $hash
     * @param StudentDataGateway $studentDataGateway
     * @param AuthManager $authManager
     */
    public function __construct(
        StudentValidatorInterface $studentValidator,
        HashInterface $hash,
        StudentDataGateway $studentDataGateway,
        AuthManager $authManager
    ) {
        $this->studentValidator = $studentValidator;
        $this->hash = $hash;
        $this->studentDataGateway = $studentDataGateway;
        $this->authManager = $authManager;
    }

    /**
     * Show register page
     *
     * @return mixed
     */
    public function index()
    {
        return $this->render("register.view.php");
    }

    /**
     * Register a new user
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
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
            $hash = $this->hash->generate();
            $student->setHash($hash);
            $this->studentDataGateway->insertStudent($student);
            $this->authManager->logIn($hash);

            return $this->redirect("/?notify=1", 303);
        } else {
            // Re-render the form passing $errors and $values arrays
            $params = compact("values", "errors");

            return $this->render("register.view.php", $params);
        }
    }
}