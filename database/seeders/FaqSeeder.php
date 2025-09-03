<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use carbon\Carbon;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = Carbon::now();
        $faqs = [
            [
                'question' => "What’s the Minimum Order Quantity for wholesale?",
                'answer' => "MoQ is 50 pieces for international orders and 20 pieces for orders within Nigeria.",
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'question' => "Can I select multiple designs?",
                'answer' => "Yes, you can select as many designs as possible to make up your Minimum Order Quantity.",
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'question' => "How much will my landing cost be for bulk purchase?",
                'answer' => "Depending on current shipping and fabric price selected, average wholesale landing cost per 6 yards fabric to your country could be as low as $19 – $25.",
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'question' => "How do I become a wholesaler?",
                'answer' => "Register via this link: https://samplelink/my-account. Please send your email to sample@mail.com.ng or WhatsApp +2347012345678 requesting to be converted to a wholesaler.",
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Faq::insert($faqs);
    }
}
