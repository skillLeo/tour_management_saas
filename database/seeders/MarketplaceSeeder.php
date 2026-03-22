<?php

namespace Database\Seeders;

use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Product;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Models\VendorInfo;
use Botble\Slug\Facades\SlugHelper;
use Illuminate\Support\Arr;

class MarketplaceSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('stores');

        Customer::query()->where('is_vendor', 1)->update(['is_vendor' => 0]);
        Store::query()->truncate();
        VendorInfo::query()->truncate();

        $names = ['John Smith', 'Jane Doe', 'Robert Johnson', 'Emily Davis', 'Michael Wilson', 'Sarah Brown', 'David Miller', 'Jessica Taylor', 'James Anderson', 'Amanda Thomas'];
        $phones = ['+11234567890', '+12345678901', '+13456789012', '+14567890123', '+15678901234', '+16789012345', '+17890123456', '+18901234567', '+19012345678', '+10123456789'];

        $vendors = [];
        $vendorIndex = 0;
        foreach (Customer::query()->get() as $customer) {
            $customer->is_vendor = $customer->id == 1 ? 0 : ($customer->id == 2 ? 1 : rand(0, 1));
            $customer->vendor_verified_at = $customer->is_vendor ? now() : null;
            $customer->save();

            if ($customer->is_vendor) {
                $vendors[] = $customer->id;

                $vendorInfo = new VendorInfo();
                $vendorInfo->bank_info = [
                    'name' => Arr::random(['Bank of America', 'Chase Bank', 'Wells Fargo', 'Citibank', 'US Bank']),
                    'number' => $phones[$vendorIndex % count($phones)],
                    'full_name' => $names[$vendorIndex % count($names)],
                    'description' => Arr::random(['Business Account', 'Savings Account', 'Checking Account', 'Commercial Account']),
                ];
                $vendorInfo->customer_id = $customer->id;
                $vendorInfo->save();

                $vendorIndex++;
            }
        }

        $storeNames = [
            'GoPro',
            'Global Office',
            'Young Shop',
            'Global Store',
            "Robert's Store",
            'Stouffer',
            'StarKist',
            'Old El Paso',
            'Tyson',
        ];

        $emails = ['contact@gopro.test', 'info@globaloffice.test', 'hello@youngshop.test', 'support@globalstore.test', 'robert@store.test', 'info@stouffer.test', 'hello@starkist.test', 'contact@oldelpaso.test', 'support@tyson.test'];
        $countries = ['US', 'CA', 'GB', 'AU', 'DE', 'FR', 'JP', 'KR', 'SG'];
        $states = ['California', 'Texas', 'Florida', 'New York', 'Illinois', 'Pennsylvania', 'Ohio', 'Georgia', 'Michigan'];
        $cities = ['Los Angeles', 'Houston', 'Miami', 'New York', 'Chicago', 'Philadelphia', 'Columbus', 'Atlanta', 'Detroit'];
        $addresses = ['123 Main Street', '456 Oak Avenue', '789 Pine Road', '321 Maple Drive', '654 Cedar Lane', '987 Elm Street', '147 Birch Way', '258 Walnut Court', '369 Cherry Boulevard'];
        $descriptions = [
            'Your trusted source for quality products.',
            'Delivering excellence since day one.',
            'Quality products at competitive prices.',
            'Customer satisfaction is our priority.',
            'Premium products for discerning customers.',
        ];
        $contents = [
            'Welcome to our store! We offer a wide selection of high-quality products at competitive prices. Our team is dedicated to providing excellent customer service and ensuring your satisfaction with every purchase.',
            'We are committed to bringing you the best products from around the world. With years of experience in the industry, we understand what our customers need and strive to exceed expectations.',
            'Our mission is to provide quality products that enhance your lifestyle. We carefully select each item in our catalog to ensure it meets our high standards of quality and value.',
        ];
        $verificationNotes = [
            'Verified business with valid documentation',
            'Established vendor with proven track record',
            'Successfully completed verification process',
            'Authentic products and reliable service confirmed',
            'Verified through official business registration',
        ];

        for ($i = 0; $i < count($vendors); $i++) {
            $isVerified = rand(0, 100) < 60;

            $storeData = [
                'name' => $storeNames[$i] ?? 'Store ' . ($i + 1),
                'email' => $emails[$i] ?? 'store' . ($i + 1) . '@example.test',
                'phone' => $phones[$i % count($phones)],
                'logo' => 'stores/' . ($i + 1) . '.png',
                'country' => $countries[$i % count($countries)],
                'state' => $states[$i % count($states)],
                'city' => $cities[$i % count($cities)],
                'address' => $addresses[$i % count($addresses)],
                'customer_id' => $vendors[$i],
                'description' => Arr::random($descriptions),
                'content' => Arr::random($contents),
                'is_verified' => $isVerified,
            ];

            if ($isVerified) {
                $storeData['verified_at'] = now()->subDays(rand(1, 180));
                $storeData['verification_note'] = Arr::random($verificationNotes);
            }

            $store = Store::query()->create($storeData);

            SlugHelper::createSlug($store);

            MetaBox::saveMetaBoxData($store, 'social_links', [
                'facebook' => 'botble',
                'twitter' => 'botble',
            ]);
        }

        foreach (Product::query()->where('is_variation', 0)->get() as $product) {
            $product->store_id = Store::query()->inRandomOrder()->value('id');
            $product->save();
        }
    }
}
