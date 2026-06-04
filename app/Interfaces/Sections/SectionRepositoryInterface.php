<?php

namespace App\Interfaces\Sections;

use Illuminate\Http\Request;

interface SectionRepositoryInterface
{
    // Get All Sections
    public function index();

    // Store Section
    public function store(Request $request);

    // Update Section
    public function update(Request $request);

    // Delete Section
    public function destroy(Request $request);

    // Show Section
    public function show(int $id);
}
