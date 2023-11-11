<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppointmentCalenderResource;
use App\Http\Resources\AppointmentDashboard;
use App\Http\Resources\AppointmentDashboardResource;
use App\Http\Resources\AppointmentDetailsResource;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($store_id)
    {
        //
        $allappiontment = (new Appointment())->allAppiontmentDetails($store_id);

        $formattedData = AppointmentResource::collection($allappiontment);
        return response()->json([
            'success' => true,
            'message' => 'Get All Appointment',
            'data' => $formattedData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request, $store_id)
    {
        $data='';
        try {
            Log::info('APPOINTMENT_DATA', ['data', $request->all()]);
            $appointment = (new Appointment())->createAppointment($request, $store_id);
            $success = $appointment['status'];
            $message = $appointment['status_message'];
            $data      = $appointment['data'];
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed' . $throwable->getMessage();
            Log::info('APPOINTMENT_FAILED', ['ERROR', $throwable]);
        }
        return response()->json([
            'data'      => $data,
            'success'   => $success,
            'message'   => $message,

        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($store_id,Appointment $appointment)
    {

        $appointment->load('transactions');
        $formattedData = new AppointmentDetailsResource($appointment);
        return response()->json([
            'success' => true,
            'message' => 'Get Appointment Details',
            'data' => $formattedData,
        ]);


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request,$store_id, Appointment $appointment)
    {
        //

        try {
            (new Appointment())->updateAppoinment($request,$store_id, $appointment);
            $success = true;
            $message = 'Appointment updated successfully';
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed! ' . $throwable->getMessage();
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($store_id,Appointment $appointment)
    {
        //
        try {
            (new Appointment())->deleteAppointment($appointment);
            $success = true;
            $message = 'Deleted Successfully';
        } catch (\Throwable $throwable) {
            $success = false;
            $message = 'Failed! ' . $throwable;
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function appointment_dashboard($store_id){

        $appointmentData = (new Appointment())->getAppointmentDashboardData($store_id);
       //dd($appointmentData);

        $formateData=AppointmentDashboardResource::collection($appointmentData);
        return response()->json([
            'success'=>true,
            'message'=>"Appointment Dashboard Data",
            'data'   =>  $formateData



        ]);

    }

 public function appointment_calender(Request $request, $store_id) //by_month
 {
     $appointmentData = (new Appointment())->getAppointmentMonthlyData($store_id,$request);
     //dd($appointmentData);

     $formateData=AppointmentCalenderResource::collection($appointmentData);
     return response()->json([
         'success'=>true,
         'message'=>"Appointment Calender Data",
         'data'   =>  $formateData



     ]);

 }


 public function by_date_appointment(Request $request,int $store_id){

     $service_categories = ServiceCategory::query()->where('store_id', $store_id)->select('id', 'name', 'status')->where('status', 1)->get();
     $groupedAppointments= (new Appointment())->getByDateAppointment($store_id, $request, $service_categories);


     return response()->json([
         'success'=>true,
         'message'=>"Appointment GroupBy Date",
         'header' =>$service_categories->pluck('name')->toArray(),
         'data'   => $groupedAppointments



     ]);
}


      public function savePaymentData(Request $request,$store_id)
      {
          $appointment = (new Appointment())->findAppointmentById($request,$store_id);
          return response()->json([
              'success'=>true,
              'message'=>"Payment Successfully",
              'data'   => $appointment



          ]);

      }

}
