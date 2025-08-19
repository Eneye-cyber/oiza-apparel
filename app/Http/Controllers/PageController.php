<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    //

    
    public function home(): View
    {
        $products = [
            [
                'image' => 'https://www.ankara.com.ng/wp-content/uploads/2025/08/Blue-Isi-agu-Traditional-Fabric-Per-Yard-600x450.png.webp',
                'name' => 'Blue Isi-agu Traditional Fabric (Per Yard)',
                'price' => '19.99',
                'description' => 'Authentic Nigerian Isi-agu fabric with traditional lion head motifs, perfect for ceremonial attire.'
            ],
            [
                'image' => 'https://img.kwcdn.com/product/fancy/0797f3ae-089e-4b73-b66e-ce22cdc4dcaa.jpg?imageView2/2/w/800/q/70/format/webp',
                'name' => 'Premium African Wax Print (6 Yards)',
                'price' => '89.99',
                'description' => 'Vibrant wax print fabric with intricate patterns, suitable for dresses, skirts, and headwraps.'
            ],
            [
                'image' => 'https://i.ebayimg.com/images/g/kO4AAOSwUMVaJDJC/s-l1600.jpg',
                'name' => 'Handwoven Kente Stole',
                'price' => '45.50',
                'description' => 'Genuine Ghanaian Kente cloth stole with symbolic patterns, ideal for special occasions.'
            ],
            [
                'image' => 'https://i0.wp.com/www.adireafricantextiles.com/wp-content/uploads/2023/03/AD360.jpg',
                'name' => 'Yoruba Adire Eleko Tie-Dye',
                'price' => '32.75',
                'description' => 'Indigo-dyed cotton fabric with traditional Yoruba resist-dye patterns.'
            ],
            [
                'image' => 'https://i.ebayimg.com/images/g/ifAAAOSwPnVik9yi/s-l1600.jpg',
                'name' => 'Aso Oke Fila (Traditional Cap)',
                'price' => '28.00',
                'description' => 'Handwoven Yoruba men\'s cap made from premium Aso Oke fabric.'
            ],
            [
                'image' => 'https://www.ankara.com.ng/wp-content/uploads/2025/08/Blue-Isi-agu-Traditional-Fabric-Per-Yard-600x450.png.webp',
                'name' => 'Nigerian Lace Material (3 Yards)',
                'price' => '65.25',
                'description' => 'Elegant lace fabric perfect for bridal and formal outfits.'
            ],
            [
                'image' => 'https://d17a17kld06uk8.cloudfront.net/products/KBU8S9B/PRVARNFK-default.jpg',
                'name' => 'Malian Bogolan Mudcloth',
                'price' => '54.99',
                'description' => 'Traditional Bamana mud-dyed cotton with geometric patterns.'
            ],
            [
                'image' => 'https://d21d281c1yd2en.cloudfront.net/media/product_images/f550411e-1924-4a8b-baf9-d16467fee527.jpeg',
                'name' => 'East African Kitenge Bundle',
                'price' => '72.50',
                'description' => 'Colorful Swahili coastal fabric bundle (3 pieces, 6 yards each).'
            ]
        ];

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

        return view('pages.home', compact('products', 'faq'));
    }

    public function shop(): View
    {
        $products = collect([
            [
                'image' => 'https://www.ankara.com.ng/wp-content/uploads/2025/08/Blue-Isi-agu-Traditional-Fabric-Per-Yard-600x450.png.webp',
                'name' => 'Blue Isi-agu Traditional Fabric (Per Yard)',
                'price' => '19.99',
                'description' => 'Authentic Nigerian Isi-agu fabric with traditional lion head motifs, perfect for ceremonial attire.'
            ],
            [
                'image' => 'https://img.kwcdn.com/product/fancy/0797f3ae-089e-4b73-b66e-ce22cdc4dcaa.jpg?imageView2/2/w/800/q/70/format/webp',
                'name' => 'Premium African Wax Print (6 Yards)',
                'price' => '89.99',
                'description' => 'Vibrant wax print fabric with intricate patterns, suitable for dresses, skirts, and headwraps.'
            ],
            [
                'image' => 'https://i.ebayimg.com/images/g/kO4AAOSwUMVaJDJC/s-l1600.jpg',
                'name' => 'Handwoven Kente Stole',
                'price' => '45.50',
                'description' => 'Genuine Ghanaian Kente cloth stole with symbolic patterns, ideal for special occasions.'
            ],
            [
                'image' => 'https://i0.wp.com/www.adireafricantextiles.com/wp-content/uploads/2023/03/AD360.jpg',
                'name' => 'Yoruba Adire Eleko Tie-Dye',
                'price' => '32.75',
                'description' => 'Indigo-dyed cotton fabric with traditional Yoruba resist-dye patterns.'
            ],
            [
                'image' => 'https://i.ebayimg.com/images/g/ifAAAOSwPnVik9yi/s-l1600.jpg',
                'name' => 'Aso Oke Fila (Traditional Cap)',
                'price' => '28.00',
                'description' => 'Handwoven Yoruba men\'s cap made from premium Aso Oke fabric.'
            ],
            [
                'image' => 'https://www.ankara.com.ng/wp-content/uploads/2025/08/Blue-Isi-agu-Traditional-Fabric-Per-Yard-600x450.png.webp',
                'name' => 'Nigerian Lace Material (3 Yards)',
                'price' => '65.25',
                'description' => 'Elegant lace fabric perfect for bridal and formal outfits.'
            ],
            [
                'image' => 'https://d17a17kld06uk8.cloudfront.net/products/KBU8S9B/PRVARNFK-default.jpg',
                'name' => 'Malian Bogolan Mudcloth',
                'price' => '54.99',
                'description' => 'Traditional Bamana mud-dyed cotton with geometric patterns.'
            ],
            [
                'image' => 'https://d21d281c1yd2en.cloudfront.net/media/product_images/f550411e-1924-4a8b-baf9-d16467fee527.jpeg',
                'name' => 'East African Kitenge Bundle',
                'price' => '72.50',
                'description' => 'Colorful Swahili coastal fabric bundle (3 pieces, 6 yards each).'
            ]
        ]);

        $categories = [
            [
                'name' => 'Fabrics',
                'subcategories' => [
                    ['name' => 'Prints'],
                    ['name' => 'Solids'],
                    ['name' => 'Embroidered'],
                ],
            ],
            [
                'name' => 'Ready to wear',
                'subcategories' => [
                    ['name' => 'Dresses'],
                    ['name' => 'Tops'],
                    ['name' => 'Pants'],
                    ['name' => 'Jackets'],
                ],
            ],
            [
                'name' => 'Accessories',
                'subcategories' => [
                    ['name' => 'Scarves'],
                    ['name' => 'Bags'],
                ],
            ],
        ];
        return view('pages.shop.index', compact('products', 'categories'));
    }

    public function show(Request $request, $category, $subcategory = null, $product = null)
    {
        // Fetch all categories for the sidebar
        // $categories = Category::whereNull('parent_id')
        //     ->with(['subcategories' => fn($query) => $query->select('name', 'slug')])
        //     ->select('name', 'slug')
        //     ->get()
        //     ->map(function ($category) {
        //         return [
        //             'name' => $category->name,
        //             'subcategories' => $category->subcategories->map(fn($sub) => [
        //                 'name' => $sub->name,
        //                 'slug' => $sub->slug,
        //             ])->toArray(),
        //         ];
        //     })->toArray();

        $category = [
                'name' => 'Fabrics',
                'subcategories' => [
                    ['name' => 'Prints'],
                    ['name' => 'Solids'],
                    ['name' => 'Embroidered'],
                ],
            ];

        // Validate category
        // $categoryModel = Category::where('slug', $category)
        //     ->whereNull('parent_id')
        //     ->firstOrFail();

        // Handle product details page
        if ($product || ($subcategory && is_null($product))) {
            // $productModel = Product::where('slug', $product)
            //     ->where('category_id', $categoryModel->id)
            //     ->when($subcategory, fn($query) => $query->whereHas('subcategory', fn($q) => $q->where('slug', $subcategory)))
            //     ->firstOrFail();
            $categories = [
            [
                'name' => 'Fabrics',
                'subcategories' => [
                    ['name' => 'Prints'],
                    ['name' => 'Solids'],
                    ['name' => 'Embroidered'],
                ],
            ],
            [
                'name' => 'Ready to wear',
                'subcategories' => [
                    ['name' => 'Dresses'],
                    ['name' => 'Tops'],
                    ['name' => 'Pants'],
                    ['name' => 'Jackets'],
                ],
            ],
            [
                'name' => 'Accessories',
                'subcategories' => [
                    ['name' => 'Scarves'],
                    ['name' => 'Bags'],
                ],
            ],
        ];
            $productModel = collect([
                'name' => 'Ankara Floral Fabric',
                'slug' => 'ankara-floral-fabric',
                'price' => 29.99,
                'image' => 'https://www.ankara.com.ng/wp-content/uploads/2025/08/Blue-Isi-agu-Traditional-Fabric-Per-Yard-600x450.png.webp',
                'description' => 'Vibrant Ankara fabric with floral patterns, perfect for custom designs.',
                'tags' => array(
                    "Floral Ankara fabric",
                    "African floral print fabric",
                    "Ankara wax print fabric",
                    "100% cotton Ankara fabric",
                    "African print fabric for dresses",
                    "Nigerian floral Ankara",
                    "Vibrant floral African fabric",
                    "Ankara material for fashion",
                    "Bold floral Ankara prints",
                    "Trendy Ankara fabric designs",
                    "Unique floral African patterns",
                    "African clothing material",
                    "Asoebi fabric for weddings",
                    "Guarantee wax Ankara print",
                    "6 yards Ankara fabric",
                    "Quality African wax fabric",
                    "Ankara fabric for women",
                    "African style fabric",
                    "Handmade Ankara floral print",
                    "Custom Ankara fabric"
                ),
                'category' => collect(['name' => 'Fabrics', 'slug' => 'fabrics']),
                'subcategory' => collect(['name' => 'Prints', 'slug' => 'prints']),
            ]);
            $tags_string = implode(", ", $productModel['tags']);
            return view('pages.shop.product', compact('productModel', 'categories', 'tags_string'));
        }

        // Handle category or subcategory listing
        // $query = Product::where('category_id', $categoryModel->id);

        // if ($subcategory) {
        //     $subcategoryModel = Category::where('slug', $subcategory)
        //         ->where('parent_id', $categoryModel->id)
        //         ->firstOrFail();
        //     $query->where('subcategory_id', $subcategoryModel->id);
        // }

        // Apply filters and sorting
        // if ($request->has('color')) {
        //     $query->where('color', $request->input('color'));
        // }

        // if ($request->has('min_price') && is_numeric($request->input('min_price'))) {
        //     $query->where('price', '>=', $request->input('min_price'));
        // }

        // if ($request->has('max_price') && is_numeric($request->input('max_price'))) {
        //     $query->where('price', '<=', $request->input('max_price'));
        // }

        // $sort = $request->input('sort-by', 'default');
        // switch ($sort) {
        //     case 'price-asc':
        //         $query->orderBy('price', 'asc');
        //         break;
        //     case 'price-desc':
        //         $query->orderBy('price', 'desc');
        //         break;
        //     case 'name-asc':
        //         $query->orderBy('name', 'asc');
        //         break;
        //     case 'name-desc':
        //         $query->orderBy('name', 'desc');
        //         break;
        //     default:
        //         // Default sorting (e.g., by created_at or ID)
        //         $query->orderBy('id', 'desc');
        // }

        // Paginate results
        // $products = $query->paginate(12)->appends($request->query());

        // Return shop index view
         $products = collect([
            [
                'image' => 'https://www.ankara.com.ng/wp-content/uploads/2025/08/Blue-Isi-agu-Traditional-Fabric-Per-Yard-600x450.png.webp',
                'name' => 'Blue Isi-agu Traditional Fabric (Per Yard)',
                'price' => '19.99',
                'description' => 'Authentic Nigerian Isi-agu fabric with traditional lion head motifs, perfect for ceremonial attire.'
            ],
            [
                'image' => 'https://img.kwcdn.com/product/fancy/0797f3ae-089e-4b73-b66e-ce22cdc4dcaa.jpg?imageView2/2/w/800/q/70/format/webp',
                'name' => 'Premium African Wax Print (6 Yards)',
                'price' => '89.99',
                'description' => 'Vibrant wax print fabric with intricate patterns, suitable for dresses, skirts, and headwraps.'
            ],
            [
                'image' => 'https://i.ebayimg.com/images/g/kO4AAOSwUMVaJDJC/s-l1600.jpg',
                'name' => 'Handwoven Kente Stole',
                'price' => '45.50',
                'description' => 'Genuine Ghanaian Kente cloth stole with symbolic patterns, ideal for special occasions.'
            ],
            [
                'image' => 'https://i0.wp.com/www.adireafricantextiles.com/wp-content/uploads/2023/03/AD360.jpg',
                'name' => 'Yoruba Adire Eleko Tie-Dye',
                'price' => '32.75',
                'description' => 'Indigo-dyed cotton fabric with traditional Yoruba resist-dye patterns.'
            ],
            [
                'image' => 'https://i.ebayimg.com/images/g/ifAAAOSwPnVik9yi/s-l1600.jpg',
                'name' => 'Aso Oke Fila (Traditional Cap)',
                'price' => '28.00',
                'description' => 'Handwoven Yoruba men\'s cap made from premium Aso Oke fabric.'
            ],
            [
                'image' => 'https://www.ankara.com.ng/wp-content/uploads/2025/08/Blue-Isi-agu-Traditional-Fabric-Per-Yard-600x450.png.webp',
                'name' => 'Nigerian Lace Material (3 Yards)',
                'price' => '65.25',
                'description' => 'Elegant lace fabric perfect for bridal and formal outfits.'
            ],
            [
                'image' => 'https://d17a17kld06uk8.cloudfront.net/products/KBU8S9B/PRVARNFK-default.jpg',
                'name' => 'Malian Bogolan Mudcloth',
                'price' => '54.99',
                'description' => 'Traditional Bamana mud-dyed cotton with geometric patterns.'
            ],
            [
                'image' => 'https://d21d281c1yd2en.cloudfront.net/media/product_images/f550411e-1924-4a8b-baf9-d16467fee527.jpeg',
                'name' => 'East African Kitenge Bundle',
                'price' => '72.50',
                'description' => 'Colorful Swahili coastal fabric bundle (3 pieces, 6 yards each).'
            ]
        ]);
        $categoryModel = collect(['name' => 'Fabrics', 'slug' => 'fabrics']);
        $subcategoryModel = $subcategory ? collect(['name' => $subcategory, 'slug' => $subcategory]) : null ;
        $page_title = $subcategoryModel ? $subcategoryModel['name'] : $categoryModel['name'];
        return view('pages.shop.category', compact('products', 'category', 'page_title'));
    }
}
