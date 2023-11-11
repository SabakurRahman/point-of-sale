<?php

namespace App\Models;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentGroupResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function createAppointment(StoreAppointmentRequest $request, $store_id)
    {
        $createAppointment='';
        $startDateTime = Carbon::parse($request->input('date_time'));

        $category = Category::query()->find($request->category_id);

        $service_category_id = $category->serviceCategory->id;


        $product = Product::query()->find($request->product_id);
        $amount = $product->price;

        $duration = $product->duration;

        $endTime = $startDateTime->copy()->addMinutes($duration);

        $appointment = self::query()->where('service_category_id', $service_category_id)->whereBetween('date_time', [$startDateTime, $endTime])->first();

        if ($appointment) {
            $status = false;
            $status_message = 'The slot is not available, try another time';
        } else {
            $createAppointment= self::query()->create($this->prepareAppointmentData($request, $service_category_id, $amount, $store_id));
            $status = true;
            $status_message = 'Booking confirmed';
        }

        return [
            'data'     => $createAppointment,
            'status' => $status,
            'status_message' => $status_message
        ];

    }
    private function prepareAppointmentData(StoreAppointmentRequest $request, $service_category_id, $amount, $store_id): array
    {
        $advance = $request->has('advance') ? $request->input('advance') : 0;
        $due = $amount - $advance;

        return [
            'category_id'   => $request->input('category_id'),
            'date_time'     => $request->input('date_time'),
            'name'          => $request->input('name'),
            'email'         => $request->input('email'),
            'phone'         => $request->input('phone'),
            'message'       => $request->input('message'),
            'store_id'      => $store_id,
            'service_category_id' => $service_category_id,
            'created_by'    => Auth::check() ? Auth::id() : null,
            'amount'        => $amount,
            'product_id'    => $request->product_id,
            'advance'       => $advance, // Store advance separately
            'due'           => $due,     // Store due separately
        ];
    }


    public function allAppiontmentDetails($store_id)
    {
        return self::query()->with(['category', 'category.serviceCategory'])->where('store_id', $store_id)->get();

    }

    public function updateAppoinment(UpdateAppointmentRequest $request,$store_id, Appointment $appointment)
    {
        $advance = $request->input('advance') ?? $appointment->advance;
        $amount  = $appointment->amount;
        $due = $amount - $advance;

        $appointment_data = [
            'category_id' => $request->input('category_id') ?? $appointment->name,
            'store_id'    => $store_id,
            'date_time'   => $request->input('date_time') ?? $appointment->date_time,
            'amount'      => $request->input('amount') ?? $appointment->amount,
            'name'        => $request->input('name') ?? $appointment->name,
            'email'       => $request->input('email') ?? $appointment->email,
            'phone'       => $request->input('phone') ?? $appointment->phone,
            'created_by'  => Auth::check()?Auth::id():null,
            'message'     => $request->input('message') ?? $appointment->message,
            'advance'     => $advance,
            'due'         => $due,
        ];
        return $appointment->update($appointment_data);


    }

    public function deleteAppointment(Appointment $appointment)
    {
        return $appointment->delete();
    }

    public function getAppointmentDashboardData($store_id)
    {


        return self::query()->where('store_id', $store_id)->with('category', 'category.serviceCategory', 'product')->get();
    }

    public function getAppointmentMonthlyData($store_id, Request $request)
    {

        $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
        $end_date = Carbon::parse($request->input('end_date'))->endOfDay();

        return self::query()->where('store_id', $store_id)->with('product')->whereBetween('date_time', [$start_date, $end_date])->get();
//      dd($appointmentMonthlyData);
    }

    /**
     * @param $store_id
     * @param Request $request
     * @param $service_categories
     * @return array
     */

    final public function getByDateAppointment(int $store_id, Request $request,  Collection|null $service_categories): array
    {
        $date = Carbon::parse($request->input('start_date'))->startOfDay();
        $groupedAppointments = self::query()->where('store_id', $store_id)
            ->whereDate('date_time', $date)
            ->get();
        $prepared_data = [];
        $slots = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00'];
        foreach ($slots as $slot) {
            $slot_start_time = Carbon::parse($request->input('start_date') . $slot);
            $slot_end_time = Carbon::parse($request->input('start_date') . $slot)->addMinutes(59);
            $temp_data[$slot] = [];
            foreach ($service_categories as $service_category) {
                $slot_appointment = $groupedAppointments->where('service_category_id', $service_category->id)->whereBetween('date_time', [$slot_start_time, $slot_end_time])->sortBy('date_time');
                if (count($slot_appointment) > 0) {
                    $temp_appointment = AppointmentGroupResource::collection($slot_appointment)->toArray($request);
                    $temp_data[$slot] = array_merge($temp_data[$slot], $temp_appointment);
                } else {
                    $temp_appointment = null;
                    $temp_data[$slot][] = null;
                }
            }
            $prepared_data = array_merge($prepared_data, $temp_data);
            $temp_data = [];
        }
        return $prepared_data;
    }


    public function service_category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function findAppointment(mixed $appointment_id, mixed $amount)
    {
        $appointment = self::query()->find($appointment_id);
        if($appointment){
            if($appointment->due != 0){
                $advance = $appointment->advance+$amount;
                $due = $appointment->amount-$advance;
            }
            else{
                $advance=$amount;
                $due = $appointment->amount-$advance;
            }

            $appointment->update($this->updateData($due,$advance));
            Transaction::query()->create($this->prepairAppointmentTransactionData($amount,$appointment_id));
        }

        return $appointment;

    }

    private function updateData($due,$advance)
    {
        return [
            "due"       => $due,
            "advance"   => $advance
        ];
    }

    private function prepairAppointmentTransactionData(mixed $amount,mixed $appointment_id)
    {
        return [
            'amount'=>$amount,
            'status'=>1,
            'type'  =>2,
            'appointment_id'=>$appointment_id

        ];
    }


  public function transactions()
  {
      return $this->hasMany(Transaction::class);
  }

    public function appointmentDetailsById(array|string|null $appointmentId)
    {
        return self::query()->with(['category', 'category.serviceCategory'])->where('id',$appointmentId)->first();
    }

    public function findAppointmentById(Request $request,$store_id)
    {

        $appointment = self::query()->find($request->appointment_id);
        if($appointment){
            if($appointment->due != 0){
                $advance = $appointment->advance+$request->amount;
                $due = $appointment->amount-$advance;
            }
            else{
                $advance=$request->amount;
                $due = $appointment->amount-$advance;
            }

            $appointment->update($this->updateData($due,$advance));
            Transaction::query()->create($this->prepairAppointmentTransactionsData($request,$store_id));

            return $appointment;


    }


}

    private function prepairAppointmentTransactionsData(Request $request,$store_id)
    {
        return [
            'amount'            =>$request->amount,
            'status'            =>1,
            'type'              =>2,
            'store_id'          =>$store_id,
            'appointment_id'    =>$request->appointment_id,
            'account_no'        =>$request->sender_no,
            'trxId'             =>$request->trx_no,
            'payment_method_id' =>$request->payment_method_id,


        ];

    }
}
