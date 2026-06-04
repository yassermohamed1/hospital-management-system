<?php

namespace App\Interfaces\Doctors;

use Illuminate\Http\Request;

interface DoctorRepositoryInterface
{
    // get Doctor
    public function index();

    // create Doctor
    public function create();

    // store Doctor
    public function store(Request $request);

    // update Doctor
    public function update(Request $request);

    // destroy Doctor
    public function destroy(Request $request);

    // destroy Doctor
    public function edit(int $id);

    // update_password
    public function update_password(Request $request);

    // update_status
    public function update_status(Request $request);
}
