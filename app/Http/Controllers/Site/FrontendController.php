<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Time;
use App\Models\User;
use App\Models\Booking;
use App\Models\Prescription;
//use App\Mail\AppointmentMail;

class FrontendController extends Controller
{

    public function index()
    {
    	date_default_timezone_set('Asia/Yerevan');

    	// TODO I dont like this approach. Add to this index action also filtering mechanism
//    	if(request('date')){
//            $doctors = $this->findDoctorsBasedOnDate(request('date'));
//            return view('welcome',compact('doctors'));
//        }

    	$date = request('date') ?? date('Y-m-d');

    	// Appointments available today
        $appointments_today = Appointment
            ::with('doctor')
            ->where('date', $date)
            ->get();

        return view('site.pages.home',compact('appointments_today'));
    }

    public function show_appointments_of_doctor_for_date ($doctorId, $date)
    {
        $appointment = Appointment::where('user_id', $doctorId)->where('date', $date)->first();
        $times = Time::where('appointment_id', $appointment->id)->where('status', 0)->get();
        $doctor = User::where('id',$doctorId)->first();
        $doctor_id = $doctorId;

        return view('site.pages.appointment',compact(['times', 'date', 'doctor']));
    }

//    public function findDoctorsBasedOnDate($date)
//    {
//        $appointments = Appointment::where('date', $date)->get();
//        return $appointments;
//    }

    public function bookAnAppointment(Request $request)
    {
        date_default_timezone_set('Asia/Yerevan');

        $request->validate(['time'=>'required']);

        $check = $this->checkBookingTimeInterval();

        if($check){
            return redirect()->back()->with('message', 'You have already booked an appointment. Please wait to make next appointment');
        }

        Booking::create([
            'user_id'=> auth()->user()->id,
            'doctor_id'=> $request->doctorId,
            'time'=> $request->time,
            'date'=> $request->date,
            'status'=>0
        ]);

        Time
            ::where('appointment_id', $request->appointmentId)
            ->where('time', $request->time)
            ->update(['status' => 1]);

        //send email notification
        $doctorName = User::where('id',$request->doctorId)->first();
        $mailData = [
            'name'=>auth()->user()->name,
            'time'=>$request->time,
            'date'=>$request->date,
            'doctorName' => $doctorName->name

        ];
        try{
           // \Mail::to(auth()->user()->email)->send(new AppointmentMail($mailData));

        }catch(\Exception $e){

        }

        return redirect()->back()->with('message','Your appointment was booked');
    }

    public function checkBookingTimeInterval()
    {
        // Only 1 booking in 1 day
        return Booking
            ::orderby('id', 'DESC')
            ->where('user_id', auth()->id())
            ->whereDate('created_at', date('Y-m-d'))
            ->exists();
    }

    public function myBookings()
    {
        $appointments = Booking::latest()->where('user_id',auth()->user()->id)->get();
        return view('booking.index',compact('appointments'));
    }

    public function myPrescription()
    {
        $prescriptions = Prescription::where('user_id',auth()->user()->id)->get();
        return view('my-prescription',compact('prescriptions'));
    }

    public function doctorToday(Request $request)
    {
        $doctors = Appointment::with('doctor')->whereDate('date',date('Y-m-d'))->get();
        return $doctors;
    }

    public function findDoctors(Request $request)
    {
        $doctors = Appointment::with('doctor')->whereDate('date',$request->date)->get();
        return $doctors;
    }

















}
