<?php


namespace App\Interfaces\Services;

use Illuminate\Http\Request;

interface SingleServiceRepositoryInterface
{

    // Get SingleServices
    public function index();

    // store SingleServices
    public function store(Request $request);

    // update SingleServices
    public function update(Request $request);

    // destroy SingleServices
    public function destroy(Request $request);
}
