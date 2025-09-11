<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContainerMaster;
use App\Models\OutboundMaster;
use App\Models\WmsPackingDetail;
use App\Models\WmsPickingDetail;
use App\Models\Palletization;
use App\Models\DeliveryMaster;
use App\Models\DeliveryDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoiceNumber;


class DeliveryController extends Controller
{
        public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('delivery_code',Auth::user()->store_id);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }
  public function create()
{
    $containers = ContainerMaster::store()->get();
    $orders = OutboundMaster::select('id','doc_number')->Store()->get();
    $cartons = WmsPackingDetail::store()->get();
    
    $pallets  = Palletization::store()->get();
   $notPackedItems = WmsPickingDetail::with('item')
        ->get()
        ->filter(function($detail){
            $packedQty = WmsPackingDetail::where('item_id', $detail->item_id)
                ->whereHas('packingMaster', function($q) use ($detail){
                    $q->where('picking_master_id', $detail->picking_master_id);
                })
                ->sum('qty');

            return $packedQty < $detail->qty; 
        });
    return view('delivery.create',['invoice_no'=>$this->invoice_no()], compact('containers','orders','cartons','notPackedItems','pallets'));
}

public function store(Request $request)
{
    $request->validate([
        'delivery_reference' => 'required|string|max:255',
         'loading_type' => 'required|string|max:255',
        'details'            => 'required|array|min:1',
        'details.*.order_id' => 'required|integer',
        'details.*.type'     => 'required|string',
        'details.*.qty'      => 'required',
    ]);

    DB::beginTransaction();
    try {
        // Save master
        $master = DeliveryMaster::create([
            'delivery_reference' => $request->delivery_reference,
            'loading_type'       => $request->loading_type,
            'container_number'   => $request->container_number,
            'vehicle_no'         => $request->vehicle_no,
            'container_id'       => $request->container_id,
            'driver'             => $request->driver,
            'mobile_no'          => $request->mobile_no,
            'id_number'          => $request->id_number,
            'status'             => 1,
            'store_id'           => auth()->user()->store_id ?? 1, // adjust as needed
            'user_id'            => auth()->id(),
        ]);

        // Save details
        foreach ($request->details as $detail) {
                 DeliveryDetail::create([
                'delivery_master_id' => $master->id,
                'order_id'           => $detail['order_id'],
                'doc_number'         => $detail['doc_number'] ?? null,
                'type'               => $detail['type'],
                'carton_barcode'     => $detail['carton_barcode'] ?? null,
                'item_id'            => $detail['item_id'] ?? null,
                'pallet_id'          => $detail['pallet_id'] ?? null,
                'qty'                => $detail['qty'],
                'status'             => 1,
                'store_id'           => auth()->user()->store_id ?? 1,
                'user_id'            => auth()->id(),
            ]);
        }
        InvoiceNumber::updateinvoiceNumber('delivery_code', Auth::user()->store_id);

        DB::commit();
        return redirect()->route('delivery.index')->with('success', 'Delivery created successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error: '.$e->getMessage());
    }
}


public function index()
{
    $deliveries = DeliveryMaster::with('details')
        ->store()
        ->latest()
        ->get();

    return view('delivery.index', compact('deliveries'));
}
public function edit($id)
{
    $delivery = DeliveryMaster::with('details')->findOrFail($id);

    if ($delivery->status != 1) {
        return redirect()->route('delivery.index')
            ->with('error', 'Only status 1 deliveries can be edited.');
    }

    // Load reference data like in create()
    $containers = ContainerMaster::store()->get();
    $orders     = OutboundMaster::select('id','doc_number')->store()->get();
    $cartons    = WmsPackingDetail::store()->get();
    $pallets    = Palletization::store()->get();

    $notPackedItems = WmsPickingDetail::with('item')
        ->get()
        ->filter(function($detail){
            $packedQty = WmsPackingDetail::where('item_id', $detail->item_id)
                ->whereHas('packingMaster', function($q) use ($detail){
                    $q->where('picking_master_id', $detail->picking_master_id);
                })
                ->sum('qty');

            return $packedQty < $detail->qty; 
        });

    return view('delivery.edit', compact(
        'delivery','containers','orders','cartons','pallets','notPackedItems'
    ));
}

public function update(Request $request, $id)
{
    $request->validate([
        'delivery_reference' => 'required|string|max:255',
        'loading_type' => 'required|string|max:255',
        'details'            => 'nullable|array',
        'details.*.order_id' => 'required_with:details|integer',
        'details.*.type'     => 'required_with:details|string',
        'details.*.qty'      => 'required_with:details',
    ]);

    $delivery = DeliveryMaster::with('details')->findOrFail($id);

    if ($delivery->status != 1) {
        return redirect()->route('delivery.index')
            ->with('error', 'Only status 1 deliveries can be updated.');
    }

    DB::beginTransaction();
    try {
        // Update master
        $delivery->update([
            'delivery_reference' => $request->delivery_reference,
             'loading_type'       => $request->loading_type,
            'container_number'   => $request->container_number,
            'vehicle_no'         => $request->vehicle_no,
            'container_id'       => $request->container_id,
            'driver'             => $request->driver,
            'mobile_no'          => $request->mobile_no,
            'id_number'          => $request->id_number,
        ]);

        // Update details if provided
        if ($request->has('details')) {
            // Option 1: delete all old details and re-insert
            $delivery->details()->delete();

            foreach ($request->details as $detail) {
                    DeliveryDetail::create([
                    'delivery_master_id' => $delivery->id,
                    'order_id'           => $detail['order_id'],
                    'doc_number'         => $detail['doc_number'] ?? null,
                    'type'               => $detail['type'],
                    'carton_barcode'     => $detail['carton_barcode'] ?? null,
                    'item_id'            => $detail['item_id'] ?? null,
                    'pallet_id'          => $detail['pallet_id'] ?? null,
                    'qty'                => $detail['qty'],
                    'status'             => 1,
                    'store_id'           => auth()->user()->store_id ?? 1,
                    'user_id'            => auth()->id(),
                ]);
            }
        }

        DB::commit();
        return redirect()->route('delivery.index')->with('success', 'Delivery updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error: '.$e->getMessage());
    }
}

public function destroy($id)
{
    $delivery = DeliveryMaster::findOrFail($id);
    $delivery->delete();

    return redirect()->route('delivery.index')->with('success', 'Delivery deleted successfully.');
}

public function view($id)
{
    $delivery = DeliveryMaster::with('details')->findOrFail($id);
    return view('delivery.view', compact('delivery'));
}

public function complete($id)
{
    $delivery = DeliveryMaster::with('details')->findOrFail($id);

    if ($delivery->status != 1) {
        return redirect()->route('delivery.index')
            ->with('error', 'Only status 1 deliveries can be completed.');
    }

    return view('delivery.complete', compact('delivery'));
}

public function markComplete($id)
{
    $delivery = DeliveryMaster::findOrFail($id);

    if ($delivery->status != 1) {
        return redirect()->route('delivery.index')
            ->with('error', 'Only status 1 deliveries can be marked complete.');
    }

    $delivery->update(['status' => 2]);

    return redirect()->route('delivery.index')->with('success', 'Delivery marked as completed.');
}


}
