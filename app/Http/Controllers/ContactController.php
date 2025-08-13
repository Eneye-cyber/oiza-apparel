<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {

        $faq = collect([
            [
                'question' => "What’s the Minimum Order Quantity for wholesale?",
                'answer' => "MoQ is 50 pieces for international orders and 20 pieces for orders within Nigeria."
            ],
            [
                'question' => "Can I select multiple designs?",
                'answer' => "Yes, you can select as many designs as possible to make up your Minimum Order Quantity."
            ],
            [
                'question' => "How much will my landing cost be for bulk purchase?",
                'answer' => "Depending on current shipping and fabric price selected, average wholesale landing cost per 6 yards fabric to your country could be as low as $19 – $25."
            ],
            [
                'question' => "How do I become a wholesaler?",
                'answer' => "Register via this link: https://samplelink/my-account. Please send your email to sample@mail.com.ng or WhatsApp +2347012345678 requesting to be converted to a wholesaler."
            ],
        ]);
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
}