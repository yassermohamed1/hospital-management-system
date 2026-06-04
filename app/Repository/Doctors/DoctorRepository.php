<?php

namespace App\Repository\Doctors;

use App\Interfaces\Doctors\DoctorRepositoryInterface;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Image;
use App\Models\Section;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorRepository implements DoctorRepositoryInterface
{
    use UploadTrait;

    public function index()
    {
        $doctors = Doctor::with('doctorappointments', 'section')->get();

        return view('Dashboard.Doctors.index', compact('doctors'));
    }

    public function create()
    {
        $sections = Section::all();
        $appointments = Appointment::all();
        return view('Dashboard.Doctors.add', compact('sections', 'appointments'));
    }


    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $doctor = new Doctor();

            $doctor->email = $request->email;
            $doctor->password = Hash::make($request->password);
            $doctor->section_id = $request->section_id;
            $doctor->phone = $request->phone;
            $doctor->status = 1;

            $doctor->save();

            // Store translation
            $doctor->name = $request->name;
            $doctor->save();

            // Insert pivot table
            $doctor->doctorappointments()->attach($request->appointments);

            // Upload image
            $this->verifyAndStoreImage(
                $request,
                'photo',
                'doctors',
                'upload_image',
                $doctor->id,
                Doctor::class
            );

            DB::commit();

            session()->flash('add');

            return redirect()->route('Doctors.create');
        } catch (\Exception $e) {

            DB::rollback();

            return redirect()
                ->back()
                ->withErrors([
                    'error' => $e->getMessage()
                ]);
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        try {

            $doctor = Doctor::findorfail($request->id);

            $doctor->email = $request->email;
            $doctor->section_id = $request->section_id;
            $doctor->phone = $request->phone;
            $doctor->save();
            // store trans
            $doctor->name = $request->name;
            $doctor->save();

            // update pivot tABLE
            $doctor->doctorappointments()->sync($request->appointments);

            // update photo
            if ($request->has('photo')) {
                // Delete old photo
                if ($doctor->image) {
                    $old_img = $doctor->image->filename;
                    $this->Delete_attachment('upload_image', 'doctors/' . $old_img, $request->id);
                }

                //Upload img
                $this->verifyAndStoreImage($request, 'photo', 'doctors', 'upload_image', $request->id, 'App\Models\Doctor');
            }

            DB::commit();
            session()->flash('edit');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->page_id == 1) {

            if ($request->filename) {

                $this->Delete_attachment('upload_image', 'doctors/' . $request->filename, $request->id, $request->filename);
            }
            Doctor::destroy($request->id);
            session()->flash('delete');
            return redirect()->route('Doctors.index');
        }


        //---------------------------------------------------------------

        else {

            // delete selector doctor
            $delete_select_id = explode(",", $request->delete_select_id);
            foreach ($delete_select_id as $ids_doctors) {
                $doctor = Doctor::findorfail($ids_doctors);
                if ($doctor->image) {
                    $this->Delete_attachment('upload_image', 'doctors/' . $doctor->image->filename, $ids_doctors, $doctor->image->filename);
                }
            }

            Doctor::destroy($delete_select_id);
            session()->flash('delete');
            return redirect()->route('Doctors.index');
        }
    }


    public function edit(int $id)
    {
        $sections = Section::all();
        $appointments = Appointment::all();
        $doctor = Doctor::findorfail($id);
        return view('Dashboard.Doctors.edit', compact('sections', 'appointments', 'doctor'));
    }

    public function update_password(Request $request)
    {
        try {
            $doctor = Doctor::findorfail($request->id);
            $doctor->update([
                'password' => Hash::make($request->password)
            ]);

            session()->flash('edit');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update_status(Request $request)
    {
        try {
            $doctor = Doctor::findorfail($request->id);
            $doctor->update([
                'status' => $request->status
            ]);

            session()->flash('edit');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
