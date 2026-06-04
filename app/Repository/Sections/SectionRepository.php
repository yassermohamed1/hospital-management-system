<?php

namespace App\Repository\Sections;

use App\Interfaces\Sections\SectionRepositoryInterface;
use App\Models\Doctor;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionRepository implements SectionRepositoryInterface
{

    public function index()
    {
        $sections = Section::all();
        return view('Dashboard.Sections.index', compact('sections'));
    }

    public function store(Request $request)
    {
        Section::create([
            'name' => $request->input('name'),
        ]);
        return redirect()
            ->route('Sections.index')
            ->with('add', 'تمت الإضافة بنجاح');
    }

    public function update(Request $request)
    {
        $section = Section::findOrFail($request->id);
        $section->update([
            'name' => $request->input('name'),
        ]);
        session()->flash('edit');
        return redirect()->route('Sections.index');
    }


    public function destroy(Request $request)
    {
        Section::findOrFail($request->id)->delete();
        session()->flash('delete');
        return redirect()->route('Sections.index');
    }

    public function show(int $id)
    {
        $doctors = Section::findOrFail($id)->doctors;
        $section = Section::findOrFail($id);
        return view('Dashboard.Sections.show_doctors', compact('doctors', 'section'));
    }
}
