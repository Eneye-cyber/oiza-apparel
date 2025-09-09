<?php

namespace Database\Seeders;

use App\Models\Products\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Log;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Atiku material (10 Yards)',
                'category_id' => 20, // Fabrics > Ankara > Atiku
                'description' => 'Premium Atiku fabric, perfect for traditional attire and special occasions. Aso ebi Qty available.',
                'cover_media' => 'atiku-butter.jpg',
                'main_color' => 'Butter/Beige',
                'price' => 85000.00,
                'is_featured' => true,
                'tags' => 'Atiku Fabric,Atiku Material,Nigerian Fabric,African Fabric,Fabric Material,Fashion Fabric',
                'meta_keywords' => 'High Quality, Premium Quality Atiku Fabric, Affordable Price, Luxurious Fabric, Elegance, Comfortable, Versatile, Handcrafted, Durable',
                'meta_description' => 'Discover our premium Atiku fabric, perfect for traditional attire and special occasions. Aso ebi Qty available.',
            ],
            [
                'name' => 'Hollantex gold (Premium Quality Cotton 6 Yards)',
                'category_id' => 1, // Fabrics
                'description' => 'Premium quality Hollantex gold cotton fabric, ideal for elegant outfits and special occasions.',
                'cover_media' => 'hollandex.jpg',
                'main_color' => 'Gold',
                'price' => 28000.00,
                'is_featured' => true,
                'tags' => 'Hollantex,Cotton,Fabric,Gold,Nigerian,African',
                'meta_keywords' => 'Premium Hollantex, Gold Cotton Fabric, High Quality, Elegant, Durable, African Fashion',
                'meta_description' => 'Explore our premium Hollantex gold cotton fabric, perfect for creating elegant outfits for special occasions.',
            ],
            [
                'name' => 'High Quality Dutch Holland Vlisco (6 Yards)',
                'category_id' => 1, // Fabrics
                'description' => 'High-quality Dutch Holland Vlisco fabric, known for its vibrant patterns and durability, perfect for stylish outfits.',
                'cover_media' => 'holland.jpg',
                'main_color' => null, // Not specified, so null
                'price' => 17000.00,
                'is_featured' => true,
                'tags' => 'Vlisco,Holland,Dutch,Fabric,African,High Quality',
                'meta_keywords' => 'Dutch Holland Vlisco, High Quality Fabric, Vibrant Patterns, Durable, African Fashion',
                'meta_description' => 'Discover high-quality Dutch Holland Vlisco fabric, ideal for vibrant and stylish African outfits.',
            ],
            [
                'name' => 'Blue Isi-agu Traditional Fabric (Per Yard)',
                'category_id' => 4, // Fabrics > Ankara > African Prints
                'description' => 'Authentic Nigerian Isi-agu fabric with traditional lion head motifs, perfect for ceremonial attire.',
                'cover_media' => 'Blue-Isi-agu-Traditional-Fabric-Per-Yard.png.webp',
                'main_color' => 'Blue',
                'price' => 19.99,
                'is_featured' => false, // Not specified, default to false
                'tags' => 'isi-agu,nigerian,traditional,lion-motif,ceremonial',
                'meta_keywords' => 'Isi-agu Fabric, Nigerian Traditional, Lion Motif, Ceremonial, Blue Fabric',
                'meta_description' => 'Shop authentic Nigerian Isi-agu fabric with traditional lion head motifs, ideal for ceremonial attire.',
            ],
            [
                'name' => 'Premium African Wax Print (6 Yards)',
                'category_id' => 4, // Fabrics > Ankara > African Prints
                'description' => 'Vibrant wax print fabric with intricate patterns, suitable for dresses, skirts, and headwraps.',
                'cover_media' => 'wrapper.webp',
                'main_color' => null, // Not specified, so null
                'price' => 89.99,
                'is_featured' => true,
                'tags' => 'wax-print,ankara,african,vibrant,dresses',
                'meta_keywords' => 'African Wax Print, Vibrant Fabric, Ankara, Dresses, Headwraps, Durable',
                'meta_description' => 'Discover vibrant African wax print fabric, perfect for dresses, skirts, and headwraps.',
            ],
            [
                'name' => 'Handwoven Kente Stole',
                'category_id' => 6, // Fabrics > Ankara > Kente
                'description' => 'Genuine Ghanaian Kente cloth stole with symbolic patterns, ideal for special occasions.',
                'cover_media' => 'scarf.jpg',
                'main_color' => null, // Not specified, so null
                'price' => 45.50,
                'is_featured' => true,
                'tags' => 'kente,ghanaian,stole,symbolic,occasion',
                'meta_keywords' => 'Kente Stole, Ghanaian Fabric, Symbolic Patterns, Handwoven, Special Occasions',
                'meta_description' => 'Shop genuine Ghanaian Kente cloth stole with symbolic patterns, perfect for special occasions.',
            ],
            [
                'name' => 'Yoruba Adire Eleko Tie-Dye',
                'category_id' => 16, // Fabrics > Adire > Cotton Adire
                'description' => 'Indigo-dyed cotton fabric with traditional Yoruba resist-dye patterns.',
                'cover_media' => 'square-fabric.webp',
                'main_color' => 'Indigo',
                'price' => 32.75,
                'is_featured' => false, // Not specified, default to false
                'tags' => 'adire,yoruba,tie-dye,indigo,cotton',
                'meta_keywords' => 'Yoruba Adire, Tie-Dye Fabric, Indigo Cotton, Traditional Patterns, Handcrafted',
                'meta_description' => 'Explore indigo-dyed Yoruba Adire fabric with traditional resist-dye patterns, perfect for unique outfits.',
            ],
            [
                'name' => 'Aso Oke Fila (Traditional Cap)',
                'category_id' => 35, // Accessories > Aso-oke Fila Cap
                'description' => 'Handwoven Yoruba men\'s cap made from premium Aso Oke fabric.',
                'cover_media' => 'cap.jpg',
                'main_color' => null, // Not specified, so null
                'price' => 28.00,
                'is_featured' => false, // Not specified, default to false
                'tags' => 'aso-oke,yoruba,cap,handwoven,men',
                'meta_keywords' => 'Aso Oke Cap, Yoruba Traditional, Handwoven, Men\'s Fashion, Premium Fabric',
                'meta_description' => 'Shop handwoven Yoruba Aso Oke fila cap, ideal for traditional men\'s fashion.',
            ],
            [
                'name' => 'Nigerian Lace Material (3 Yards)',
                'category_id' => 18, // Fabrics > Lace
                'description' => 'Elegant lace fabric perfect for bridal and formal outfits.',
                'cover_media' => 'white-lace.webp',
                'main_color' => 'White',
                'price' => 65.25,
                'is_featured' => false, // Not specified, default to false
                'tags' => 'lace,nigerian,bridal,formal,elegant',
                'meta_keywords' => 'Nigerian Lace, Bridal Fabric, Formal Wear, Elegant Lace, High Quality',
                'meta_description' => 'Discover elegant Nigerian lace fabric, perfect for bridal and formal outfits.',
            ],
            [
                'name' => 'Malian Bogolan Mudcloth',
                'category_id' => 17, // Fabrics > Adire > Mali Indigo
                'description' => 'Traditional Bamana mud-dyed cotton with geometric patterns.',
                'cover_media' => 'brown-fabric.jpg',
                'main_color' => 'Brown',
                'price' => 54.99,
                'is_featured' => false, // Not specified, default to false
                'tags' => 'bogolan,malian,mudcloth,geometric,cotton',
                'meta_keywords' => 'Malian Bogolan, Mudcloth Fabric, Geometric Patterns, Cotton, Traditional',
                'meta_description' => 'Shop traditional Malian Bogolan mudcloth with geometric patterns, perfect for unique designs.',
            ],
            [
                'name' => 'East African Kitenge Bundle',
                'category_id' => 4, // Fabrics > Ankara > African Prints
                'description' => 'Colorful Swahili coastal fabric bundle (3 pieces, 6 yards each).',
                'cover_media' => 'african_print_blue.jpg',
                'main_color' => 'Blue',
                'price' => 72.50,
                'is_featured' => false, // Not specified, default to false
                'tags' => 'kitenge,east-african,swahili,vibrant,bundle',
                'meta_keywords' => 'Kitenge Fabric, East African, Swahili, Vibrant Colors, Fabric Bundle',
                'meta_description' => 'Explore colorful East African Kitenge fabric bundle, perfect for vibrant outfits.',
            ],
            [
                'name' => 'Boubou Dress Ankara African Inspired Kaftan',
                'category_id' => 28, // Ready to Wear > Women > Short Gown
                'description' => 'Boubou dress made from 100% cotton Ankara fabric with Aso Oke accents in green and white, available in sizes Small, Medium, and Large.',
                'cover_media' => 'https://i.etsystatic.com/26355969/r/il/fa8d00/5950870756/il_340x270.5950870756_olds.jpg',
                'main_color' => 'Green/White',
                'price' => 87.92,
                'is_featured' => false, // Not specified, default to false
                'tags' => 'boubou,ankara,kaftan,cotton,aso-oke,women,green,white',
                'meta_keywords' => 'Boubou Dress, Ankara Kaftan, Cotton, Aso Oke, Women\'s Fashion, Green, White',
                'meta_description' => 'Shop Boubou dress made from 100% cotton Ankara with Aso Oke accents, perfect for stylish women.',
            ],
            [
                'name' => 'Kimono Top Trousers Two-piece Set Ready to wear outfits African Ankara Print 100% Cotton Size 12 or 14 Black Brown Yellow',
                'category_id' => 28, // Ready to Wear > Women > Short Gown
                'description' => 'Two-piece kimono top and trousers set made from 100% cotton African Ankara print, available in sizes 12 and 14, in black, brown, and yellow.',
                'cover_media' => 'https://i.etsystatic.com/26355969/r/il/104872/5935405850/il_340x270.5935405850_kbzk.jpg',
                'main_color' => 'Black/Brown/Yellow',
                'price' => 117.24,
                'is_featured' => true,
                'tags' => 'kimono,trousers,ankara,cotton,two-piece,women,black,brown,yellow',
                'meta_keywords' => 'Kimono Set, Ankara Print, Cotton, Two-Piece, Women\'s Fashion, Black, Brown, Yellow',
                'meta_description' => 'Discover two-piece kimono top and trousers set in African Ankara print, perfect for stylish women.',
            ],
            [
                'name' => 'Kimono Top Trousers Two-piece Set Ready to wear outfits African Ankara Print',
                'category_id' => 28, // Ready to Wear > Women > Short Gown
                'description' => 'Two-piece kimono top and trousers set made from 100% cotton African Ankara print, available in sizes 12, 14, 16, and 18, in orange, black, and brown.',
                'cover_media' => 'https://i.etsystatic.com/26355969/r/il/41d4aa/6818103647/il_340x270.6818103647_ktc3.jpg',
                'main_color' => 'Orange/Black/Brown',
                'price' => 117.24,
                'is_featured' => false, // Not specified, default to false
                'tags' => 'kimono,trousers,ankara,cotton,two-piece,women,orange,black,brown',
                'meta_keywords' => 'Kimono Set, Ankara Print, Cotton, Two-Piece, Women\'s Fashion, Orange, Black, Brown',
                'meta_description' => 'Shop two-piece kimono top and trousers set in African Ankara print, ideal for vibrant fashion.',
            ],
            [
                'name' => 'Boubou Dress Ankara African Inspired Kaftan 100% Cotton',
                'category_id' => 28, // Ready to Wear > Women > Short Gown
                'description' => 'Boubou dress made from 100% cotton Ankara fabric with vibrant green, pink, blue, and yellow colors, available in sizes Small, Medium, and Large.',
                'cover_media' => 'https://i.etsystatic.com/26355969/r/il/32190b/6817696567/il_340x270.6817696567_5fl6.jpg',
                'main_color' => 'Green/Pink/Blue/Yellow',
                'price' => 78.15,
                'is_featured' => true,
                'tags' => 'boubou,ankara,kaftan,cotton,women,green,pink,blue,yellow',
                'meta_keywords' => 'Boubou Dress, Ankara Kaftan, Cotton, Women\'s Fashion, Green, Pink, Blue, Yellow',
                'meta_description' => 'Discover Boubou dress in vibrant Ankara fabric, perfect for women seeking colorful African fashion.',
            ],
        ];

        $categorySlugs = [
            1 => 'fabrics',
            4 => 'fabrics/ankara/african-prints',
            6 => 'fabrics/ankara/kente',
            16 => 'fabrics/adire/cotton-adire',
            17 => 'fabrics/adire/mali-indigo',
            18 => 'fabrics/lace',
            19 => 'fabrics/senator',
            20 => 'fabrics/atiku',
            28 => 'ready-to-wear/contemporary-african-womens-fashion/short-gown',
            35 => 'accessories/aso-oke-fila-cap',
        ];

        $baseTime = Carbon::now()->subDays(9); // Start 9 days ago
        $insertData = [];

        foreach ($products as $index => $productData) {
            $slug = Str::slug($productData['name']);
            $category_id = $productData['category_id'];
            $full_slug_path = $categorySlugs[$category_id] . '/' . $slug;

            // Convert non-URL cover_media to full URL using asset()
            $coverImage = $productData['cover_media'];
            if (Str::startsWith($coverImage, ['http://', 'https://'])) {
                // Download external image and store locally
                $coverImage = ImageHelper::downloadAndStoreImage($coverImage, $slug, 'products/covers', env('APP_DISK', 'local'));
                Log::info($coverImage);
                // Log::info(asset($coverImage));
            }
                Log::info(['raw' => $coverImage]);

            // convert to full URL using asset()
            // $coverImage = asset($coverImage);


            $insertData[] = [
                'name' => $productData['name'],
                'slug' => $slug,
                'full_slug_path' => $full_slug_path,
                'category_id' => $category_id,
                'description' => $productData['description'],
                'cover_media' => $coverImage,
                'media' => null,
                'meta_title' => $productData['name'],
                'meta_description' => Str::limit($productData['meta_description'] ? $productData['meta_description'] : $productData['description'], 160),
                'meta_keywords' => isset($productData['meta_keywords'])  ? json_encode(explode(',', $productData['meta_keywords'])) : null,
                'tags' => isset($productData['tags'])  ? json_encode(explode(',', $productData['tags'])) : null,
                'is_active' => true,
                'price' => $productData['price'],
                'discount_price' => null,
                'is_featured' => $productData['is_featured'] ?? false,
                'created_at' => $baseTime->copy()->addHours($index),
                'updated_at' => $baseTime->copy()->addHours($index),
            ];
        }

        Product::insert($insertData);
    }
}
