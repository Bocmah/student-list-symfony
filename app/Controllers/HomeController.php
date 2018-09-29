<?php

namespace StudentList\Controllers;

use StudentList\Database\StudentDataGateway;
use StudentList\Helpers\Pager;
use StudentList\AuthManager;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends BaseController
{
    /**
     * @var Pager
     */
    protected $pager;

    /**
     * @var StudentDataGateway
     */
    protected $studentDataGateway;

    /**
     * @var AuthManager
     */
    protected $authManager;

    /**
     * Create a new HomeController instance
     *
     * @param Pager $pager
     * @param StudentDataGateway $studentDataGateway
     * @param AuthManager $authManager
     */
    public function __construct(
        Pager $pager,
        StudentDataGateway $studentDataGateway,
        AuthManager $authManager
    ) {
        $this->pager = $pager;
        $this->studentDataGateway = $studentDataGateway;
        $this->authManager = $authManager;
    }

    /**
     * Show full student list or search results.
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $order = $request->query->get("order", "exam_score");
        $direction = $request->query->get("direction", "DESC");
        $page = $request->query->get("page", 1);
        $notify = $request->query->get("notify");
        $isAuth = $this->authManager->checkIfAuthorized();
        $perPage = $this->pager->getPerPage();
        $offset = $this->pager->calculatePositioning($page);

        $search = $request->query->get("search");

        if ($search) {
            $students = $this->studentDataGateway->searchStudents(
                $search,
                $offset,
                $perPage,
                $order,
                $direction
            );

            $rowCount = $this->studentDataGateway->countSearchRows($search);
        } else {
            $students = $this->studentDataGateway->getStudents(
                $offset,
                $perPage,
                $order,
                $direction
            );

            $rowCount = $this->studentDataGateway->countTableRows();
        }
        
        $totalPages = $this->pager->calculateTotalPages($rowCount);
        $start = $this->pager->calculateStartingPoint($page);
        $end = $this->pager->calculateEndingPoint($page, $totalPages);

        $params = compact(
            "totalPages",
            "start",
            "end",
            "students",
            "order",
            "direction",
            "search",
            "page",
            "notify",
            "isAuth"
        );

        return $this->render("home.view.php", $params);
    }
}