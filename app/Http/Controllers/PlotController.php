<?php

namespace App\Http\Controllers;

use App\Models\PlotShare;
use App\Models\Plot;
use App\Models\Lease;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PlotController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;

        if ($user->role === 'admin' || $user->role === 'warden') {
            $plots = Plot::with('owner')->get();
        } else {
            $plots = Plot::where('status', 'available')
                ->orWhere('user_id', $userId)
                ->orWhereHas('shares', function($query) use ($userId) {
                    $query->where('shared_with', $userId)
                          ->where('status', 'accepted');
                })->get();
        }

        return view('plots.index', compact('plots'));
    }

    public function show(Plot $plot)
    {
        $userId = auth()->id();
        $userRole = auth()->user()->role;

        $isPartner = $plot->shares()->where('shared_with', $userId)
                          ->where('status', 'accepted')->exists();

        $isAllowed = ($plot->user_id == $userId) || 
                     $isPartner || 
                     in_array($userRole, ['warden', 'admin']) || 
                     ($plot->status == 'available');

        if (!$isAllowed) {
            abort(403, 'You do not have permission to access this plot.');
        }

        return view('plots.show', compact('plot'));
    }

    public function create()
    {
        return view('plots.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'plot_number' => 'required|unique:plots',
            'area_sqm'    => 'required|numeric',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/plots'), $imageName);
            $data['image'] = 'images/plots/' . $imageName;
        }

        Plot::create($data);

        return redirect()->route('plots.index')->with('success', 'Plot created successfully!');
    }

    public function edit(Plot $plot)
    {
        return view('plots.edit', compact('plot'));
    }

    public function update(Request $request, Plot $plot)
    {
        $validated = $request->validate([
            'plot_number'       => 'required|unique:plots,plot_number,' . $plot->id,
            'area_sqm'          => 'required|numeric',
            'soil_quality'      => 'required|in:excellent,good,fair,poor',
            'sunlight_exposure' => 'required|integer|between:0,100',
            'status'            => 'required|in:available,rented,maintenance,pending',
        ]);

        $plot->update($validated);

        return redirect()->route('plots.index')->with('success', 'Plot updated successfully');
    }

    public function destroy(Plot $plot)
    {
        if ($plot->image && file_exists(public_path($plot->image))) {
            unlink(public_path($plot->image));
        }

        $plot->delete();
        return redirect()->route('plots.index')->with('success', 'Plot deleted successfully!');
    }

    public function rent(Plot $plot)
{
    if ($plot->status !== 'available') {
        return back()->with('error', 'Plot is already taken or pending!');
    }


    $plot->update([
        'status' => 'pending',
        'user_id' => auth()->id(),
    ]);

    \App\Models\Lease::create([
        'plot_id' => $plot->id,
        'user_id' => auth()->id(),
        'start_date' => now(),
        'end_date' => now()->addYear(),
        'status' => 'active', // جربي دي لو الداتابيز مش قابلة pending // هتبدأ بندنج لحد ما الأدمن يوافق
    ]);

    return redirect()->route('plots.index')->with('success', 'Rental request sent successfully!');
}
public function approveLease($id)
{
  
    $plot = Plot::findOrFail($id);
    $plot->update(['status' => 'rented']);


    \App\Models\Lease::create([
        'plot_id'    => $plot->id,
        'user_id'    => $plot->user_id,
        'start_date' => now(),
        'end_date'   => now()->addYear(),
        'status'     => 'active',
    ]);

    // 3. حسبة الفاتورة
    $totalAmount = $plot->area_sqm * 10; // السعر الإجمالي

    // البحث عن الشركاء المقبولين فقط لهذه الأرض
    $partners = \App\Models\PlotShare::where('plot_id', $plot->id)
                ->where('status', 'accepted')
                ->get();


    $totalPeople = 1 + $partners->count();
    $splitAmount = $totalAmount / $totalPeople;

 
    \App\Models\Invoice::create([
        'user_id'  => $plot->user_id,
        'plot_id'  => $plot->id,
        'amount'   => $splitAmount,
        'status'   => 'unpaid',
        'due_date' => now()->addDays(7),
    ]);

  
    foreach ($partners as $partner) {
        \App\Models\Invoice::create([
            'user_id'  => $partner->shared_with, // معرف الشريك
            'plot_id'  => $plot->id,
            'amount'   => $splitAmount,
            'status'   => 'unpaid',
            'due_date' => now()->addDays(7),
        ]);
    }

    return redirect()->back()->with('success', "Approved! Invoice split among $totalPeople member(s).");
}

    public function pendingRequests()
    {
        if (auth()->user()->role !== 'admin') { 
            abort(403);
        }

        $pendingPlots = Plot::where('status', 'pending')->with('owner')->get();
        return view('plots.pending_requests', compact('pendingPlots'));
    }

    public function rejectLease($id)
    {
        $plot = Plot::findOrFail($id);
        
        Lease::where('plot_id', $plot->id)->where('status', 'pending')->delete();

        $plot->update([
            'status' => 'available',
            'user_id' => null,
        ]);

        return redirect()->back()->with('error', 'Lease request rejected.');
    }


   // غيري الاسم من plantPage للاسم ده:

public function plant(Request $request, Plot $plot)

{

    // 1. التأكد من البيانات

    $request->validate([

        'seed_id' => 'required|exists:seeds,id',

    ]);




    $plot->seed_id = $request->seed_id;

    $plot->planting_date = now();

    $plot->save();



    return redirect()->route('plots.show', $plot->id)->with('success', 'Planting started!');

}




    public function terminateLease($id)
    {
        $plot = Plot::findOrFail($id);
        $plot->update([
            'user_id' => null,
            'status' => 'available',
        ]);
        return back()->with('success', 'Lease terminated.');
    }

    

    public function renewLease($id)
    {
        $plot = Plot::findOrFail($id);
        $newEndDate = now()->addYear();
        $plot->update(['lease_end' => $newEndDate]);
        return back()->with('success', 'Lease renewed!');
    }

    

    
public function showPlantForm(Plot $plot)

{

    // بنجيب كل الـ Seeds (المحاصيل) اللي الأدمن ضافها

    $seeds = \App\Models\Seed::all();

    return view('plots.plant', compact('plot', 'seeds'));

}



  public function plantPage(Plot $plot)
{
    $userId = auth()->id();

    $isAuthorized = ($plot->user_id == $userId) ||
                    $plot->shares()
                          ->where('shared_with', $userId)
                          ->where('status', 'accepted')
                          ->exists();

    if (!$isAuthorized) {
        abort(403, 'Unauthorized access.');
    }

    $seeds = \App\Models\Seed::all();

    return view('plots.plant', compact('plot', 'seeds'));
}

public function harvest($id)
{
    $plot = \App\Models\Plot::findOrFail($id);

    // حماية: صاحب الأرض بس هو اللي يحصد
    if ($plot->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'You can only harvest your own plots!');
    }

    // اللوجيك ببساطة: تصفير البذرة عشان الأرض تفضى
    $plot->update([
        'seed_id' => null
    ]);

    return redirect()->back()->with('success', 'Harvest completed! The plot is now ready for new seeds.');
}




}