<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CouponsController extends Controller
{
    public function createCoupon(Request $request)
    {
        $couponData = $request->all();
        $couponData['Employee_id'] = auth()->user()->id;
        $coupons = Coupon::create($couponData);
        return response()->json([
            'newCoupon' => $coupons,
        ]);
    }
    public function exportPDF(Request $request, $coupon_id)
    {
        $coupon = Coupon::findOrFail($coupon_id);
        $pdf = Pdf::loadHTML($this->generatePdfHtml($coupon))
            ->setPaper('a4', 'portrait');

        // Generate a temporary file path to store the PDF
        // $pdfPath = tempnam(sys_get_temp_dir(), 'users');
        // Save the PDF to the temporary file path
        $pdfFileName = $request->input('PdfName');
        $pdf->save('Pdfs/' . $pdfFileName . '.pdf', 'public');

        // Create a response to download the PDF file
        $response = new BinaryFileResponse('storage/Pdfs/' . $pdfFileName . '.pdf');

        // Set the appropriate headers for downloading the PDF file
        $response->setContentDisposition('attachment', $pdfFileName . '.pdf');

        // Return the response
        return $response;
    }
    private function generatePdfHtml($coupon)
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
                    <th style="padding: 10px;">Driver ID</th>
                    <th style="padding: 10px;">Employee ID</th>
                    <th style="padding: 10px;">Fuel Station Name</th>
                    <th style="padding: 10px;">Region</th>
                    <th style="padding: 10px;">City</th>
                </tr>
                <tr>
                    <td style="padding: 10px;">' . $coupon->driver_id . '</td>
                    <td style="padding: 10px;">' . $coupon->Employee_id . '</td>
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
