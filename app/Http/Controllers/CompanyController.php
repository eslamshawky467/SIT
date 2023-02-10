<?php

namespace App\Http\Controllers;

use App\Repository\CompanyRepositryInterface;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    protected $Companies;

    public function __construct(CompanyRepositryInterface $Companies)
    {
        $this->Companies = $Companies;
    }

    public function index()
    {
        return $this->Companies->index();
    }
    public function store(Request $request)
    {
        return $this->Companies->store($request);
    }

    public function update(Request $request)
    {

        return $this->Companies->update($request);

    }


    public function destroy(Request $request)
    {
        return $this->Companies->destroy($request);
    }
}
