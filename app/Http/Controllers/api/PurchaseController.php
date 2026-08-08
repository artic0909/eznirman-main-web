<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaterialCode;
use App\Models\Unit;
use App\Models\MaterialPurchase;
use App\Models\UnauthorizedPurchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function createData()
    {
        $user = Auth::user()->load('site');
        $site = $user->site;
        
        $materialCodes = MaterialCode::all();
        $units = Unit::where('status', 'active')->get();
        
        return response()->json([
            'site' => $site,
            'materialCodes' => $materialCodes,
            'units' => $units
        ], 200);
    }

    public function index(Request $request)
    {
        $authorizedPurchases = MaterialPurchase::with(['site', 'materialCode', 'unit'])
                    ->where('user_id', Auth::id())
                    ->get()
                    ->map(function ($item) {
                        $item->purchase_type = 'authorized';
                        $item->unique_id_display = $item->material_unique_id;
                        return $item;
                    });

        $unauthorizedPurchases = UnauthorizedPurchase::with(['site'])
                        ->where('user_id', Auth::id())
                        ->get()
                        ->map(function ($item) {
                            $item->purchase_type = 'unauthorized';
                            $item->unique_id_display = $item->unauthorized_unique_id;
                            $item->materialCode = null;
                            $item->quantity = null;
                            $item->unit = null;
                            return $item;
                        });

        $allPurchases = $authorizedPurchases->concat($unauthorizedPurchases)
                                            ->sortByDesc('purchase_date')
                                            ->values();

        return response()->json([
            'purchases' => $allPurchases
        ], 200);
    }
    
    public function store(Request $request)
    {
        $isAuthorized = $request->input('purchase_type') === 'authorized';

        $rules = [
            'working_site_id' => 'required|exists:working_sites,id',
            'purchase_date' => $isAuthorized ? 'required|date' : 'required|date|after_or_equal:today',
            'product_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'invoice_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'note' => 'nullable|string'
        ];

        if ($isAuthorized) {
            $rules = array_merge($rules, [
                'material_code_id' => 'required|exists:material_codes,id',
                'party_name' => 'required|string|max:255',
                'invoice_no' => 'required|string|max:255',
                'quantity' => 'required|numeric',
                'unit_id' => 'required|exists:units,id',
                'rate' => 'required|numeric',
                'gst_amount' => 'nullable|numeric',
            ]);
        }

        $validator = Validator::make($request->all(), $rules, [
            'purchase_date.after_or_equal' => 'you cant choose past date',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except(['purchase_type']);

        if ($request->hasFile('invoice_file')) {
            $file = $request->file('invoice_file');
            $extension = strtolower($file->getClientOriginalExtension());
            
            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                try {
                    $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                    $image = $manager->decode($file->getRealPath());
                    
                    // Compress and encode as JPEG with 75% quality
                    $encoded = $image->encode(new \Intervention\Image\Encoders\JpegEncoder(75));
                    
                    $filename = uniqid() . '_' . time() . '.jpg';
                    $path = 'invoices/' . $filename;
                    
                    \Illuminate\Support\Facades\Storage::disk('public')->put($path, (string) $encoded);
                    $data['invoice_file'] = $path;
                } catch (\Exception $e) {
                    // Fallback if compression fails
                    $data['invoice_file'] = $file->store('invoices', 'public');
                }
            } else {
                // PDF or other formats store normally
                $data['invoice_file'] = $file->store('invoices', 'public');
            }
        }

        if ($isAuthorized) {
            $data['user_id'] = Auth::id();
            $data['created_by'] = Auth::id();
            $data['type'] = 'user';
            MaterialPurchase::create($data);
        } else {
            $data['user_id'] = Auth::id();
            UnauthorizedPurchase::create($data);

            $wallet = \App\Models\Wallet::firstOrCreate(
                ['user_id' => Auth::id()],
                ['current_balance' => 0]
            );

            $newBalance = $wallet->current_balance - $data['amount'];
            $wallet->update(['current_balance' => $newBalance]);

            \App\Models\Transaction::create([
                'wallet_id' => $wallet->id,
                'date' => $data['purchase_date'],
                'amount' => $data['amount'],
                'note' => 'Unauthorized Purchase: ' . $data['product_name'],
                'type' => 'debit',
                'balance_after' => $newBalance,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Purchase recorded successfully.'
        ], 200);
    }

    /*
    public function update(Request $request, $id)
    {
        $type = $request->input('purchase_type'); // matches the app payload
        
        $rules = [
            'working_site_id' => 'required|exists:working_sites,id',
            'purchase_date' => 'required|date',
            'product_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'invoice_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'note' => 'nullable|string'
        ];

        if ($type !== 'unauthorized') {
            $rules = array_merge($rules, [
                'material_code_id' => 'required|exists:material_codes,id',
                'party_name' => 'required|string|max:255',
                'invoice_no' => 'required|string|max:255',
                'quantity' => 'required|numeric',
                'unit_id' => 'required|exists:units,id',
                'rate' => 'required|numeric',
                'gst_amount' => 'nullable|numeric',
            ]);
        }

        $request->validate($rules);
        $data = $request->except(['purchase_type', 'type', '_method', '_token']);

        if ($type === 'unauthorized') {
            $purchase = UnauthorizedPurchase::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();
        } else {
            $purchase = MaterialPurchase::where('id', $id)
                            ->where('created_by', Auth::id())
                            ->where('type', 'user')
                            ->firstOrFail();
        }

        if ($request->hasFile('invoice_file')) {
            if ($purchase->invoice_file) {
                Storage::disk('public')->delete($purchase->invoice_file);
            }
            $data['invoice_file'] = $request->file('invoice_file')->store('invoices', 'public');
        }

        $purchase->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Purchase updated successfully.',
            'purchase' => $purchase
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->query('type'); // Passed in query param

        if ($type === 'unauthorized') {
            $purchase = UnauthorizedPurchase::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();
        } else {
            $purchase = MaterialPurchase::where('id', $id)
                            ->where('created_by', Auth::id())
                            ->where('type', 'user')
                            ->firstOrFail();
        }

        if ($purchase->invoice_file) {
            Storage::disk('public')->delete($purchase->invoice_file);
        }
        $purchase->delete();

        return response()->json([
            'success' => true,
            'message' => 'Purchase deleted successfully.'
        ], 200);
    }
    */
}
