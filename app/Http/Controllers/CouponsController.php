<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CouponsController extends Controller
{
    public function createCoupon(Request $request)
    {
        // $previousCoupon = Coupon::where('driver_id',  $request->input('driver_id'))
        //     ->where('status',  'unpaid')
        //     ->first();
        $couponData = $request->all();
        $couponData['Employee_id'] = auth()->user()->id;
        $coupons = Coupon::create($couponData);
        return response()->json([
            'newCoupon' => $coupons,
        ]);
    }
    public function show($driver_id)
    {
        $coupons = Coupon::where('driver_id', $driver_id)->get(['id', 'driver_id', 'Employee_id', 'vehicle_number']);
        return response()->json([
            'coupons' => $coupons,
        ]);
    }
    public function update(Request $request, $coupon_id)
    {
        try {
            $coupon = Coupon::findOrFail($coupon_id);
            if ($coupon) {
                $coupon->update($request->all());
                return response()->json([
                    'message' => 'Coupon Status Change successfully',
                    'new Coupon' => $coupon,
                ], 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Coupon Not Found',
            ], 404);
        }
    }
    public function destroy($coupon_id)
    {
        try {
            $coupon = Coupon::findOrFail($coupon_id);
            if ($coupon) {
                $coupon->delete();
                return response()->json([
                    'message' => 'Coupon Deleted successfully',
                ]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Coupon Not Found',
            ], 404);
        }
    }
    public function exportPDF(Request $request)
    {
        try {
            $coupon = Coupon::findOrFail($request->input('id'));
            $driver = User::where('id', $coupon->driver_id)->first();
            $pdf = Pdf::loadHTML($this->generatePdfHtml($coupon, $driver))
                ->setPaper('a5', 'landscape');
            // Generate a temporary file path to store the PDF
            $pdf->save('Pdfs/' . $driver->name . '.pdf', 'public');

            // Create a response to download the PDF file
            $response = new BinaryFileResponse('storage/Pdfs/' . $driver->name . '.pdf');

            // Set the appropriate headers for downloading the PDF file
            $response->setContentDisposition('attachment', $driver->name . '.pdf');

            // Return the response
            return $response;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e,
            ], 404);
        }
    }
    private function generatePdfHtml($coupon, $driver)
    {
        // Generate HTML content for the PDF
        $html = '
    <div style="width: 100%; text-align: center; font-family: Arial, sans-serif;">
        <h1 style="font-size: 24px; margin-bottom: 20px;">Fuel Coupon</h1>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; overflow-wrap: break-word;">
                <tr style="background-color: #333; color: #fff;">
                    <th style="padding: 10px;">Coupon ID</th>
                    <th style="padding: 10px;">Vehicle Number</th>
                    <th style="padding: 10px;">Filling Date & Time</th>
                    <th style="padding: 10px;">Current Tank</th>
                </tr>
                <tr>
                    <td style="padding: 10px;">' . $coupon->id . '</td>
                    <td style="padding: 10px;">' . $coupon->vehicle_number . '</td>
                    <td style="padding: 10px;">' . $coupon->filling_datetime . '</td>
                    <td style="padding: 10px;">' . $coupon->Car_currently_tank . '</td>
                </tr>
            </table>
        </div>
        <br>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; overflow-wrap: break-word;">
                <tr style="background-color: #333; color: #fff;">
                    <th style="padding: 10px;">Fuel Quantity</th>
                    <th style="padding: 10px;">Global Fuel Price</th>
                    <th style="padding: 10px;">Fuel Quantity Price</th>
                    <th style="padding: 10px;">Currency</th>
                </tr>
                <tr>
                    <td style="padding: 10px;">' . $coupon->Coupon_fuel_quantity . '</td>
                    <td style="padding: 10px;">' . $coupon->Global_fuel_price . '</td>
                    <td style="padding: 10px;">' . $coupon->Coupon_fuel_quantity_price . '</td>
                    <td style="padding: 10px;">' . $coupon->currency . '</td>
                </tr>
            </table>
        </div>
        <br>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; overflow-wrap: break-word;">
                <tr style="background-color: #333; color: #fff;">
                    <th style="padding: 10px;">Driver Name</th>
                    <th style="padding: 10px;">Employee Name</th>
                    <th style="padding: 10px;">Fuel Station Name</th>
                    <th style="padding: 10px;">Region</th>
                    <th style="padding: 10px;">City</th>
                </tr>
                <tr>
                    <td style="padding: 10px;">' . $driver->name  . '</td>
                    <td style="padding: 10px;">' . auth()->user()->name . '</td>
                    <td style="padding: 10px;">' . $coupon->Fuel_station_name . '</td>
                    <td style="padding: 10px;">' . $coupon->Region . '</td>
                    <td style="padding: 10px;">' . $coupon->City . '</td>
                </tr>
            </table>
        </div>
    </div>';

        return $html;
    }
}
