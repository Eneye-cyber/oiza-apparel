<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Models\Faq;
use Illuminate\Support\Facades\Log;

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
}