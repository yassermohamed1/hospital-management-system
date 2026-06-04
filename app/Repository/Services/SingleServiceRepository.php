<?php


namespace App\Repository\Services;

use Illuminate\Http\Request;


use App\Models\Service;

class SingleServiceRepository implements \App\Interfaces\Services\SingleServiceRepositoryInterface
{

    public function index()
    {
        $services = Service::all();
        return view('Dashboard.Services.Single Service.index', compact('services'));
    }

    public function store(Request $request)
    {
        try {
            $SingleService = new Service();
            $SingleService->price = $request->price;
            $SingleService->description = $request->description;
            $SingleService->status = 1;
            $SingleService->save();

            // store trans
            $SingleService->name = $request->name;
            $SingleService->save();
            return redirect()->route('Service.index')->with('add');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {

            $SingleService = Service::findOrFail($request->id);
            $SingleService->price = $request->price;
            $SingleService->description = $request->description;
            $SingleService->status = $request->status;
            $SingleService->save();

            // store trans
            $SingleService->name = $request->name;
            $SingleService->save();

            return redirect()->route('Service.index')->with('edit');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        Service::destroy($request->id);

        return redirect()->route('Service.index')->with('delete');
    }
}
