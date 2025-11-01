<?php

namespace App\Http\Controllers;

use App\Mail\Enquiries\EnquiryConfirmationMail;
use App\Mail\Enquiries\EnquiryNotificationMail;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Models\Faq;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index(): View
    {

        $faq = null;
        try {
            $faq = Faq::where('is_active', true)->get();
        } catch (\Throwable $th) {
            Log::error('Error fetching FAQs for home page', [
                'controller' => 'PageController',
                'method' => 'home',
                'error' => $th->getMessage(),
            ]);
            $faq = collect();
        }
        return view('pages.contact', compact('faq'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Example: Send email (configure mail settings in .env)
        Mail::raw(
            "Name: {$validated['name']}\nEmail: {$validated['email']}\nMessage: {$validated['message']}",
            function ($message) use ($validated) {
                $message->to('alaoeneye@gmail.com')
                        ->subject('New Contact Form Submission');
            }
        );

        return redirect()->route('contact')->with('success', 'Your message has been sent successfully!');
    }

     public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }   
        try {
            //code...
            $enquiry = Enquiry::create($request->all());
            Mail::to($enquiry->email)->queue(new EnquiryConfirmationMail($enquiry));
            Mail::to(config('mail.admin_email'))->queue(new EnquiryNotificationMail($enquiry));
            return redirect()->back()->with('success', 'Thank you for your enquiry. We will get back to you soon!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
            
        }

    }
}