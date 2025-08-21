<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::truncate();
        $categories = [
            [
                'name' => 'Fabrics',
                'slug' => 'fabrics',
                'description' => 'High-quality fabrics for various uses',
                'is_active' => true,
                'order' => 1,
                'subcategories' => [
                    [
                        'name' => 'Ankara',
                        'slug' => 'ankara',
                        'description' => 'Vibrant African wax prints and patterns',
                        'subcategories' => [
                            ['name' => 'Antiquity', 'slug' => 'antiquity', 'description' => 'Classic vintage-inspired Ankara designs'],
                            ['name' => 'African Prints', 'slug' => 'african-prints', 'description' => 'Traditional and modern African pattern fabrics'],
                            ['name' => 'Imperial Ankara', 'slug' => 'imperial-ankara', 'description' => 'Premium quality luxury Ankara fabrics'],
                            [
                                'name' => 'Kente',
                                'slug' => 'kente',
                                'description' => 'Traditional Ghanaian woven cloth patterns',
                                'subcategories' => [
                                    ['name' => 'Kente Wax Gold', 'slug' => 'kente-wax-gold', 'description' => 'Gold-accented Kente print fabrics'],
                                    ['name' => 'Togo Hitarget Kente', 'slug' => 'togo-hitarget-kente', 'description' => 'Premium Togo-style Kente designs']
                                ]
                            ],
                            ['name' => 'Made 2 Match', 'slug' => 'made-2-match', 'description' => 'Coordinating fabric sets for outfits'],
                            ['name' => 'Outstanding', 'slug' => 'outstanding', 'description' => 'Exceptional quality standout fabrics'],
                            [
                                'name' => 'Phoenix Hitarget',
                                'slug' => 'phoenix-hitarget',
                                'description' => 'Premium Phoenix brand fabric collections',
                                'subcategories' => [
                                    ['name' => 'Hitarget Kente Wax', 'slug' => 'hitarget-kente-wax', 'description' => 'High-quality Kente wax prints'],
                                    ['name' => 'Hightartex Ankara', 'slug' => 'hightartex-ankara', 'description' => 'Superior texture Ankara fabrics']
                                ]
                            ],
                            ['name' => 'Satin', 'slug' => 'satin', 'description' => 'Luxurious smooth and shiny satin fabrics'],
                        ]
                    ],
                    [
                        'name' => 'Adire',
                        'slug' => 'adire',
                        'description' => 'Traditional Nigerian tie-dye and indigo fabrics',
                        'subcategories' => [
                            ['name' => 'Cotton Adire', 'slug' => 'cotton-adire', 'description' => '100% cotton traditional Adire textiles'],
                            ['name' => 'Mali Indigo', 'slug' => 'mali-indigo', 'description' => 'Authentic West African indigo-dyed fabrics']
                        ]
                    ],
                    [
                        'name' => 'Lace',
                        'slug' => 'lace',
                        'description' => 'Elegant lace fabrics for special occasions',
                        'subcategories' => [
                            ['name' => 'Beaded Lace', 'slug' => 'beaded-lace', 'description' => 'Lace fabrics with intricate bead embellishments'],
                            ['name' => 'French Lace', 'slug' => 'french-lace', 'description' => 'Fine quality French lace for bridal wear'],
                            ['name' => 'voile', 'slug' => 'voile', 'description' => 'Lightweight semi-transparent fabric'],
                            [
                                'name' => 'Damask',
                                'slug' => 'damask',
                                'description' => 'Reversible patterned fabric for formal wear',
                                'subcategories' => [
                                    ['name' => 'Exclusive European Damask', 'slug' => 'exclusive-european-damask-damask', 'description' => 'Premium European imported damask fabrics']
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'Senator',
                        'slug' => 'senator',
                        'description' => 'Premium fabrics for traditional senator outfits',
                    ],
                    [
                        'name' => 'Atiku',
                        'slug' => 'atiku',
                        'description' => 'Traditional Nigerian men\'s wear fabrics',
                    ]
                ]
            ],
            [
                'name' => 'Ready to Wear',
                'slug' => 'ready-to-wear',
                'description' => 'Ready-made clothing',
                'is_active' => true,
                'order' => 2,
                'subcategories' => [
                    [
                        'name' => 'Women',
                        'slug' => 'contemporary-african-womens-fashion',
                        'description' => 'Modern African fashion for women',
                        'subcategories' => [
                            ['name' => 'Short Gown', 'slug' => 'short-gown', 'description' => 'Casual and elegant short African dresses']
                        ]
                    ],
                    ['name' => 'Boys', 'slug' => 'boys', 'description' => 'African traditional wear for boys'],
                    ['name' => 'Girls', 'slug' => 'girls', 'description' => 'Beautiful African outfits for girls'],
                    [
                        'name' => 'Men',
                        'slug' => 'african-mens-fashion',
                        'description' => 'Traditional and contemporary African menswear',
                        'subcategories' => [
                            ['name' => 'Agbada', 'slug' => 'agbada', 'description' => 'Traditional flowing gown for special occasions'],
                            ['name' => 'Readymade Senator', 'slug' => 'readymade-senator', 'description' => 'Ready-to-wear senator outfits for men']
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Fashion accessories',
                'is_active' => true,
                'order' => 3,
                'subcategories' => [
                    ['name' => 'Aso-oke Fila Cap', 'slug' => 'aso-oke-fila-cap', 'description' => 'Traditional Yoruba cap made from Aso-oke fabric']
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $this->createCategoryWithChildren($categoryData);
        }
    }

    /**
     * Recursively create categories with their children
     */
    private function createCategoryWithChildren(array $categoryData, ?int $parentId = null): void
    {
        if (!isset($categoryData['name']) || !isset($categoryData['slug'])) {
            throw new \Exception('Category name and slug are required.');
        }
        // Create the main category
        $category = Category::create([
            'name' => $categoryData['name'],
            'slug' => $categoryData['slug'],
            'parent_id' => $parentId,
            'description' => $categoryData['description'] ?? null,
            'is_active' => $categoryData['is_active'] ?? true,
            'order' => $categoryData['order'] ?? 0,
        ]);

        // Create subcategories if they exist
        if (isset($categoryData['subcategories'])) {
            foreach ($categoryData['subcategories'] as $subcategoryData) {
                $this->createCategoryWithChildren($subcategoryData, $category->id);
            }
        }
    }
}
