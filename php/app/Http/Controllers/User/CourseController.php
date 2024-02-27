<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\AbstractAdminController;
use App\Http\Controllers\AbstractUserController;
use App\Services\CourseService;
use App\Services\TestService;
use Illuminate\Http\Request;

class CourseController extends AbstractUserController
{
    private CourseService $course;

    public function __construct(
        CourseService $course
    )
    {
        $this->course = $course;
        parent::__construct();
    }


    public function autocomplete(Request $request)
    {
        $result = $this->course->getAutocomplete();
        return response()->json($result);
    }

    public function autocompleteWithCompanyId(Request $request)
    {
        $company_id = $this->getCompanyId();
        return response()->json($this->course->getAutocompleteWithCompanyId($company_id));
    }
}
