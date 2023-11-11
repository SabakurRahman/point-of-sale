<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function showPaymentPage(Request $request)
    {
        $amount = $request->query('amount');
        $appointmentId = $request->query('appointment_id');

        $appointDetails = (new Appointment())->appointmentDetailsById($appointmentId);
        return view('payment_page.payment', compact('amount', 'appointmentId','appointDetails'));
    }

    public function processPayment(Request $request)
    {

        $appionment = (new Appointment())->findAppointment($request->appointment_id,$request->amount);
        if($appionment){
            return redirect()->route('success')->with(['amount' => $request->input('amount'), 'appointmentId' => $request->input('appointment_id')]);
        }
        else{
            return redirect()->route('failed')->with(['amount' => $request->input('amount'), 'appointmentId' => $request->input('appointment_id')]);
        }

    }

    public function showSuccessPage()
    {
        return view('payment_page.success');
    }
    public function showFailedPage()
    {
        return view('payment_page.failed');
    }
}
