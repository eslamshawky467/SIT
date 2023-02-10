<?php
namespace App\Repository;
use Illuminate\Http\Request;
interface CompanyRepositryInterface {
    public function index();
    public function store(Request $request);
    public function update(Request $request);
    public function destroy(Request $request);
}
?>
