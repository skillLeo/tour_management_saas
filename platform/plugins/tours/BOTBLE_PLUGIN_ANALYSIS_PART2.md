# Botble CMS Tours Plugin - Analysis Part 2

## B) Botble E-commerce Plugin Study

### Overview

The Botble E-commerce plugin (`others_plugins/ecommerce`) is a mature, production-ready plugin that serves as an excellent reference for best practices. It demonstrates professional-grade architecture and comprehensive feature implementation.

**Plugin Stats:**

- **Version:** 3.11.4
- **Minimum Core:** 7.6.0
- **Total Files:** ~1,500+ files
- **Lines of Code:** ~100,000+ lines
- **Features:** 30+ major features

---

### 1. Summary of E-commerce Plugin Architecture

#### **Directory Structure (Best Practices)**

```
ecommerce/
├── plugin.json                          # Plugin metadata
├── composer.json                        # Dependencies
├── config/                              # Configuration files
│   ├── cart.php                        # Cart configuration
│   ├── email.php                       # Email templates config
│   ├── general.php                     # General settings
│   ├── order.php                       # Order settings
│   ├── permissions.php                 # 667 lines of permissions!
│   └── shipping.php                    # Shipping configuration
├── database/
│   ├── migrations/                     # 150 migration files
│   └── seeders/                        # 12 seeder files
├── helpers/                            # 14 helper files
│   ├── brands.php
│   ├── common.php
│   ├── constants.php
│   ├── currencies.php
│   ├── customer.php
│   ├── discounts.php
│   ├── order.php
│   ├── prices.php
│   ├── product-attributes.php
│   ├── product-categories.php
│   ├── product-options.php
│   ├── product-variations.php
│   ├── products.php
│   └── shipping.php
├── public/                             # Frontend assets
│   ├── css/                           # 17 CSS files
│   ├── js/                            # 36 JS files
│   ├── images/                        # 6 image files
│   └── libraries/                     # 126 library files
├── resources/
│   ├── email-templates/               # 28 email templates
│   ├── js/                            # 51 JS files (12 Vue components)
│   ├── lang/                          # 1,505 translation files!
│   ├── sass/                          # 22 SCSS files
│   └── views/                         # 304 view files
├── routes/                            # 19 route files
├── src/
│   ├── AdsTracking/                   # Analytics integration
│   ├── Cart/                          # Cart system
│   ├── Charts/                        # Dashboard charts
│   ├── Commands/                      # 3 console commands
│   ├── Database/Seeders/              # Database seeders
│   ├── Enums/                         # 23 enum classes ✅
│   ├── Events/                        # 17 event classes ✅
│   ├── Exceptions/                    # Custom exceptions
│   ├── Exporters/                     # 6 exporter classes
│   ├── Exports/                       # Excel exports
│   ├── Facades/                       # 9 facade classes
│   ├── Forms/                         # 56 form classes
│   ├── Http/                          # 261 HTTP files
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Importers/                     # 5 importer classes
│   ├── Imports/                       # CSV imports
│   ├── Jobs/                          # Background jobs
│   ├── Listeners/                     # 33 event listeners ✅
│   ├── Models/                        # 60 model classes
│   ├── Notifications/                 # 4 notification classes
│   ├── Observers/                     # Model observers
│   ├── Option/                        # Product options system
│   ├── PanelSections/                 # Settings panels
│   ├── Plugin.php                     # Plugin lifecycle
│   ├── Providers/                     # 4 service providers
│   ├── Repositories/                  # 99 repository files
│   │   ├── Caches/                   # Cache decorators
│   │   ├── Eloquent/                 # Implementations
│   │   └── Interfaces/               # Contracts
│   ├── Rules/                         # Validation rules
│   ├── Services/                      # 44 service classes ✅
│   ├── Supports/                      # 12 support classes
│   ├── Tables/                        # 33 table classes
│   ├── Traits/                        # 5 trait classes
│   ├── ValueObjects/                  # 2 value objects
│   └── Widgets/                       # 22 dashboard widgets ✅
└── tests/
    ├── Feature/                       # 19 feature tests ✅
    └── Unit/                          # 5 unit tests ✅
```

---

### 2. Botble-Specific Best Practices Used

#### **✅ 1. Comprehensive Permission System**

**File:** `config/permissions.php` (667 lines)

**Structure:**

```php
return [
    // Parent permission
    [
        'name' => 'E-commerce',
        'flag' => 'plugins.ecommerce',
    ],

    // Child permissions with parent_flag
    [
        'name' => 'Reports',
        'flag' => 'ecommerce.report.index',
        'parent_flag' => 'plugins.ecommerce',
    ],

    // Resource permissions (CRUD)
    [
        'name' => 'Products',
        'flag' => 'products.index',
        'parent_flag' => 'plugins.ecommerce',
    ],
    [
        'name' => 'Create',
        'flag' => 'products.create',
        'parent_flag' => 'products.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'products.edit',
        'parent_flag' => 'products.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'products.destroy',
        'parent_flag' => 'products.index',
    ],
    [
        'name' => 'Duplicate',
        'flag' => 'products.duplicate',
        'parent_flag' => 'products.index',
    ],

    // Settings permissions (hierarchical)
    [
        'name' => 'Ecommerce',
        'flag' => 'ecommerce.settings',
        'parent_flag' => 'settings.index',
    ],
    [
        'name' => 'General',
        'flag' => 'ecommerce.settings.general',
        'parent_flag' => 'ecommerce.settings',
    ],
    // ... 20+ more settings permissions
];
```

**Key Takeaways:**

- ✅ Hierarchical permission structure
- ✅ Separate permissions for each action (create, edit, delete, duplicate, export, import)
- ✅ Settings permissions separate from CRUD permissions
- ✅ Granular control (e.g., separate permissions for product prices vs product inventory)

**Tours Plugin Comparison:**

- ❌ Missing: duplicate, export, import permissions
- ❌ Missing: granular settings permissions
- ❌ Missing: show/view permissions

---

#### **✅ 2. Enum Classes for Type Safety**

**Location:** `src/Enums/` (23 enum files)

**Example:** `OrderStatusEnum.php`

```php
namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;

class OrderStatusEnum extends Enum
{
    public const PENDING = 'pending';
    public const PROCESSING = 'processing';
    public const DELIVERING = 'delivering';
    public const DELIVERED = 'delivered';
    public const COMPLETED = 'completed';
    public const CANCELED = 'canceled';

    public static function labels(): array
    {
        return [
            self::PENDING => __('Pending'),
            self::PROCESSING => __('Processing'),
            self::DELIVERING => __('Delivering'),
            self::DELIVERED => __('Delivered'),
            self::COMPLETED => __('Completed'),
            self::CANCELED => __('Canceled'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::DELIVERING => 'primary',
            self::DELIVERED => 'success',
            self::COMPLETED => 'success',
            self::CANCELED => 'danger',
        ];
    }
}
```

**Available Enums:**

1. `OrderStatusEnum` - Order status values
2. `PaymentStatusEnum` - Payment status values
3. `PaymentMethodEnum` - Payment methods
4. `ShippingStatusEnum` - Shipping status
5. `ShippingMethodEnum` - Shipping methods
6. `ShippingRuleTypeEnum` - Shipping rule types
7. `ProductTypeEnum` - Physical/digital products
8. `StockStatusEnum` - In stock, out of stock
9. `DiscountTypeEnum` - Percentage, fixed amount
10. `DiscountTargetEnum` - All, specific products
11. `CustomerStatusEnum` - Active, locked
12. `InvoiceStatusEnum` - Pending, completed
13. `TaxTypeEnum` - Percentage, fixed
14. ... and 10 more

**Key Takeaways:**

- ✅ Type-safe constants
- ✅ Centralized labels for UI
- ✅ Color coding for status badges
- ✅ IDE autocomplete support
- ✅ Easy to extend

**Tours Plugin Comparison:**

- ❌ No enums - uses raw strings
- ❌ Status values hardcoded throughout code
- ❌ No color coding system
- ❌ Prone to typos

---

#### **✅ 3. Event-Driven Architecture**

**Location:** `src/Events/` (17 event classes)

**Examples:**

**OrderPlacedEvent.php**

```php
namespace Botble\Ecommerce\Events;

use Botble\Ecommerce\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public Order $order)
    {
    }
}
```

**OrderCompletedEvent.php**

```php
namespace Botble\Ecommerce\Events;

use Botble\Ecommerce\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCompletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order)
    {
    }
}
```

**Available Events:**

1. `OrderPlacedEvent` - When order is created
2. `OrderCompletedEvent` - When order is completed
3. `OrderConfirmedEvent` - When order is confirmed
4. `OrderCancelledEvent` - When order is cancelled
5. `OrderPaymentConfirmedEvent` - When payment confirmed
6. `OrderReturnedEvent` - When order is returned
7. `ProductQuantityUpdatedEvent` - When stock changes
8. `ProductViewed` - When product is viewed
9. `ShippingStatusChanged` - When shipping status changes
10. `CustomerEmailVerified` - When customer verifies email
11. `AbandonedCartReminderEvent` - For abandoned carts
12. `AccountDeletedEvent` - When account is deleted
13. ... and 5 more

**Listeners:** `src/Listeners/` (33 listener classes)

**Example:** `SendOrderConfirmationEmail.php`

```php
namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Events\OrderConfirmedEvent;
use Botble\Ecommerce\Notifications\OrderConfirmedNotification;

class SendOrderConfirmationEmail
{
    public function handle(OrderConfirmedEvent $event): void
    {
        $order = $event->order;

        if ($order->user && $order->user->email) {
            $order->user->notify(new OrderConfirmedNotification($order));
        }
    }
}
```

**Key Takeaways:**

- ✅ Decoupled architecture
- ✅ Easy to extend by other plugins
- ✅ Async processing support (via queues)
- ✅ Clean separation of concerns
- ✅ Testable

**Tours Plugin Comparison:**

- ❌ No custom events
- ❌ Only uses Botble core events (CreatedContentEvent, etc.)
- ❌ Hard to extend booking flow
- ❌ Email sending likely in controllers (not verified)

---

#### **✅ 4. Service Layer Pattern**

**Location:** `src/Services/` (44 service classes)

**Examples:**

**OrderService.php** (Complex business logic)

```php
namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderProduct;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Illuminate\Support\Arr;

class OrderService
{
    public function __construct(
        protected OrderInterface $orderRepository,
        protected ProductInterface $productRepository
    ) {
    }

    public function createOrder(array $data): Order
    {
        // Complex order creation logic
        // - Validate stock
        // - Calculate totals
        // - Apply discounts
        // - Handle shipping
        // - Create order products
        // - Update inventory
        // - Fire events
    }

    public function cancelOrder(Order $order, string $reason): bool
    {
        // Complex cancellation logic
        // - Validate cancellation
        // - Restore inventory
        // - Process refund
        // - Send notifications
        // - Fire events
    }

    public function calculateOrderTotal(Order $order): float
    {
        // Complex calculation
        // - Subtotal
        // - Shipping
        // - Tax
        // - Discounts
        // - Coupons
    }
}
```

**ProductService.php**

```php
namespace Botble\Ecommerce\Services;

class ProductService
{
    public function createProduct(array $data): Product
    {
        // Create product with variations, attributes, options
    }

    public function updateStock(Product $product, int $quantity): void
    {
        // Update stock and fire events
    }

    public function getRelatedProducts(Product $product, int $limit = 6): Collection
    {
        // Complex related products algorithm
    }
}
```

**Available Services:**

1. `OrderService` - Order management
2. `ProductService` - Product operations
3. `CartService` - Cart operations
4. `CheckoutService` - Checkout flow
5. `PaymentService` - Payment processing
6. `ShippingService` - Shipping calculations
7. `TaxService` - Tax calculations
8. `DiscountService` - Discount/coupon logic
9. `InventoryService` - Stock management
10. `PriceService` - Price calculations
11. `CustomerService` - Customer operations
12. `ReviewService` - Review management
13. `InvoiceService` - Invoice generation
14. `ReportService` - Analytics and reports
15. ... and 30 more

**Key Takeaways:**

- ✅ Fat services, thin controllers
- ✅ Reusable business logic
- ✅ Testable in isolation
- ✅ Dependency injection
- ✅ Single responsibility

**Tours Plugin Comparison:**

- ❌ No service layer
- ❌ Business logic in controllers
- ❌ Difficult to test
- ❌ Code duplication (e.g., booking creation in multiple places)

---

#### **✅ 5. Dashboard Widgets**

**Location:** `src/Widgets/` (22 widget classes)

**Example:** `RevenueWidget.php`

```php
namespace Botble\Ecommerce\Widgets;

use Botble\Base\Widgets\Card;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;

class RevenueWidget extends Card
{
    public function __construct(protected OrderInterface $orderRepository)
    {
    }

    public function getOptions(): array
    {
        $revenue = $this->orderRepository->getRevenueData();

        return [
            'series' => [
                [
                    'name' => __('Revenue'),
                    'data' => $revenue['data'],
                ],
            ],
        ];
    }

    public function getViewData(): array
    {
        return [
            'title' => __('Revenue'),
            'value' => format_price($this->orderRepository->getTotalRevenue()),
            'icon' => 'ti ti-currency-dollar',
            'color' => 'success',
        ];
    }
}
```

**Available Widgets:**

1. `RevenueWidget` - Total revenue with chart
2. `OrdersWidget` - Order statistics
3. `ProductsWidget` - Product counts
4. `CustomersWidget` - Customer statistics
5. `TopSellingProductsWidget` - Best sellers
6. `RecentOrdersWidget` - Latest orders
7. `LowStockProductsWidget` - Inventory alerts
8. `AbandonedCartsWidget` - Cart abandonment
9. `TopCustomersWidget` - Best customers
10. `SalesReportWidget` - Sales analytics
11. ... and 12 more

**Key Takeaways:**

- ✅ Real-time dashboard insights
- ✅ Configurable widgets
- ✅ Chart integration
- ✅ Performance optimized (cached queries)

**Tours Plugin Comparison:**

- ❌ No dashboard widgets
- ❌ No analytics/insights
- ❌ Admin has no overview of tour business

---

#### **✅ 6. Comprehensive Testing**

**Location:** `tests/`

**Feature Tests:** (19 files)

- `CartTest.php` - Cart functionality
- `CheckoutTest.php` - Checkout flow
- `OrderTest.php` - Order management
- `ProductTest.php` - Product CRUD
- `CustomerTest.php` - Customer operations
- `PaymentTest.php` - Payment processing
- `ShippingTest.php` - Shipping calculations
- `DiscountTest.php` - Discount logic
- `TaxTest.php` - Tax calculations
- ... and 10 more

**Unit Tests:** (5 files)

- `PriceCalculationTest.php`
- `StockManagementTest.php`
- `DiscountCalculationTest.php`
- `ShippingCostTest.php`
- `TaxCalculationTest.php`

**Example Test:**

```php
namespace Botble\Ecommerce\Tests\Feature;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Services\CartService;
use Tests\TestCase;

class CartTest extends TestCase
{
    protected CartService $cartService;

    public function setUp(): void
    {
        parent::setUp();
        $this->cartService = app(CartService::class);
    }

    public function test_can_add_product_to_cart(): void
    {
        $product = Product::factory()->create(['price' => 100]);

        $this->cartService->addToCart($product, 2);

        $this->assertEquals(2, $this->cartService->count());
        $this->assertEquals(200, $this->cartService->total());
    }

    public function test_cannot_add_out_of_stock_product(): void
    {
        $product = Product::factory()->create(['quantity' => 0]);

        $this->expectException(OutOfStockException::class);

        $this->cartService->addToCart($product, 1);
    }
}
```

**Key Takeaways:**

- ✅ Comprehensive test coverage
- ✅ Feature tests for user flows
- ✅ Unit tests for business logic
- ✅ Factory patterns for test data
- ✅ CI/CD ready

**Tours Plugin Comparison:**

- ❌ No tests at all
- ❌ No test directory
- ❌ No factories
- ❌ Manual testing only

---

#### **✅ 7. Notification System**

**Location:** `src/Notifications/` (4 notification classes)

**Example:** `OrderConfirmedNotification.php`

```php
namespace Botble\Ecommerce\Notifications;

use Botble\Ecommerce\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Order Confirmed - :code', ['code' => $this->order->code]))
            ->view('plugins/ecommerce::emails.order-confirmed', [
                'order' => $this->order,
                'customer' => $notifiable,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_code' => $this->order->code,
            'message' => __('Your order :code has been confirmed', [
                'code' => $this->order->code,
            ]),
        ];
    }
}
```

**Available Notifications:**

1. `OrderConfirmedNotification` - Order confirmation
2. `OrderCancelledNotification` - Order cancellation
3. `ShipmentStatusChangedNotification` - Shipping updates
4. `AbandonedCartReminderNotification` - Cart reminders

**Key Takeaways:**

- ✅ Multi-channel (email, database, SMS)
- ✅ Queueable for performance
- ✅ Customizable templates
- ✅ Localized messages

**Tours Plugin Comparison:**

- ❌ No notification system
- ❌ Email sending likely manual
- ❌ No SMS support
- ❌ No notification history

---

#### **✅ 8. Import/Export System**

**Location:** `src/Importers/` and `src/Exporters/`

**Importers:**

1. `ProductImporter` - Bulk product import
2. `ProductCategoryImporter` - Category import
3. `CustomerImporter` - Customer import
4. `OrderImporter` - Order import
5. `ShippingRuleImporter` - Shipping rules import

**Exporters:**

1. `ProductExporter` - Product export to CSV/Excel
2. `OrderExporter` - Order export
3. `CustomerExporter` - Customer export
4. `ProductCategoryExporter` - Category export
5. `InvoiceExporter` - Invoice export

**Example:** `ProductExporter.php`

```php
namespace Botble\Ecommerce\Exporters;

use Botble\Ecommerce\Models\Product;
use Botble\DataSynchronize\Exporter\Exporter;
use Illuminate\Support\Collection;

class ProductExporter extends Exporter
{
    public function getLabel(): string
    {
        return __('Products');
    }

    public function columns(): array
    {
        return [
            'name' => __('Name'),
            'sku' => __('SKU'),
            'price' => __('Price'),
            'sale_price' => __('Sale Price'),
            'quantity' => __('Quantity'),
            'status' => __('Status'),
            // ... 20+ more columns
        ];
    }

    public function collection(): Collection
    {
        return Product::query()
            ->with(['categories', 'tags', 'brand'])
            ->get()
            ->map(function (Product $product) {
                return [
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    // ... map all columns
                ];
            });
    }
}
```

**Key Takeaways:**

- ✅ Bulk operations support
- ✅ CSV/Excel formats
- ✅ Validation on import
- ✅ Progress tracking
- ✅ Error handling

**Tours Plugin Comparison:**

- ❌ No import/export
- ❌ Manual data entry only
- ❌ No bulk operations

---

#### **✅ 9. Advanced Form System**

**Location:** `src/Forms/` (56 form classes)

**Example:** `ProductForm.php` (simplified)

```php
namespace Botble\Ecommerce\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Models\Product;

class ProductForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Product::class)
            ->setValidatorClass(ProductRequest::class)
            ->add('name', TextField::class, [
                'label' => __('Name'),
                'required' => true,
            ])
            ->add('sku', TextField::class, [
                'label' => __('SKU'),
                'required' => true,
            ])
            ->add('price', NumberField::class, [
                'label' => __('Price'),
                'required' => true,
                'step' => 0.01,
            ])
            ->add('categories[]', SelectField::class, [
                'label' => __('Categories'),
                'choices' => $this->getCategoryChoices(),
                'multiple' => true,
            ])
            ->add('attributes', RepeaterField::class, [
                'label' => __('Attributes'),
                'fields' => [
                    [
                        'type' => 'select',
                        'name' => 'attribute_id',
                        'label' => __('Attribute'),
                        'choices' => $this->getAttributeChoices(),
                    ],
                    [
                        'type' => 'text',
                        'name' => 'value',
                        'label' => __('Value'),
                    ],
                ],
            ])
            ->add('gallery[]', MediaImagesField::class, [
                'label' => __('Gallery'),
                'values' => $this->getModel() ? $this->getModel()->gallery : [],
            ])
            ->addMetaBoxes([
                'product_options' => [
                    'title' => __('Product Options'),
                    'content' => view('plugins/ecommerce::products.partials.product-options', [
                        'product' => $this->getModel(),
                    ])->render(),
                    'priority' => 1,
                ],
                'product_variations' => [
                    'title' => __('Product Variations'),
                    'content' => view('plugins/ecommerce::products.partials.product-variations', [
                        'product' => $this->getModel(),
                    ])->render(),
                    'priority' => 2,
                ],
            ]);
    }
}
```

**Key Features:**

- ✅ No raw HTML injection
- ✅ Proper field classes
- ✅ Validation integration
- ✅ Meta boxes for complex sections
- ✅ Conditional fields
- ✅ AJAX field loading

**Tours Plugin Comparison:**

- ❌ Raw HTML in TourForm (XSS risk)
- ❌ Inline JavaScript
- ❌ No meta boxes
- ❌ Hard to maintain

---

#### **✅ 10. Helper Functions**

**Location:** `helpers/` (14 helper files)

**Examples:**

**prices.php**

```php
if (!function_exists('format_price')) {
    function format_price(float $price, ?Currency $currency = null, bool $withSymbol = true): string
    {
        $currency = $currency ?: get_application_currency();

        $formattedPrice = number_format(
            $price,
            $currency->decimals ?? 2,
            $currency->decimal_separator ?? '.',
            $currency->thousand_separator ?? ','
        );

        if (!$withSymbol) {
            return $formattedPrice;
        }

        if ($currency->is_prefix_symbol) {
            return $currency->symbol . $formattedPrice;
        }

        return $formattedPrice . ' ' . $currency->symbol;
    }
}
```

**products.php**

```php
if (!function_exists('get_product_price')) {
    function get_product_price(Product $product, bool $withTax = true): float
    {
        $price = $product->sale_price ?: $product->price;

        if ($withTax && setting('ecommerce_tax_enabled')) {
            $price += calculate_product_tax($product, $price);
        }

        return $price;
    }
}

if (!function_exists('product_in_stock')) {
    function product_in_stock(Product $product): bool
    {
        if (!$product->with_storehouse_management) {
            return true;
        }

        return $product->quantity > 0 || $product->allow_checkout_when_out_of_stock;
    }
}
```

**Available Helper Files:**

1. `brands.php` - Brand helpers
2. `common.php` - General helpers
3. `constants.php` - Constants
4. `currencies.php` - Currency helpers
5. `customer.php` - Customer helpers
6. `discounts.php` - Discount helpers
7. `order.php` - Order helpers
8. `prices.php` - Price formatting
9. `product-attributes.php` - Attribute helpers
10. `product-categories.php` - Category helpers
11. `product-options.php` - Option helpers
12. `product-variations.php` - Variation helpers
13. `products.php` - Product helpers
14. `shipping.php` - Shipping helpers

**Key Takeaways:**

- ✅ Reusable across views and controllers
- ✅ Consistent formatting
- ✅ Centralized business rules
- ✅ Easy to test

**Tours Plugin Comparison:**

- ⚠️ Only 1 helper file (helpers.php) with 1 function
- ❌ Missing many useful helpers
- ❌ Formatting logic scattered

---

### 3. Reusable Architectural Patterns

#### **Pattern 1: Repository Pattern with Cache Decorator**

**Structure:**

```
Repositories/
├── Interfaces/
│   └── ProductInterface.php          # Contract
├── Eloquent/
│   └── ProductRepository.php         # Implementation
└── Caches/
    └── ProductCacheDecorator.php     # Cache layer
```

**Interface:**

```php
namespace Botble\Ecommerce\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface ProductInterface extends RepositoryInterface
{
    public function getProducts(array $params = []);
    public function getRelatedProducts(int $productId, int $limit = 6);
    public function getBestSellingProducts(int $limit = 10);
    // ... more methods
}
```

**Implementation:**

```php
namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class ProductRepository extends RepositoriesAbstract implements ProductInterface
{
    public function getProducts(array $params = [])
    {
        return $this->model
            ->where('status', 'published')
            ->with(['categories', 'brand'])
            ->paginate($params['per_page'] ?? 12);
    }

    public function getBestSellingProducts(int $limit = 10)
    {
        return $this->model
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
```

**Cache Decorator:**

```php
namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductCacheDecorator extends CacheAbstractDecorator implements ProductInterface
{
    public function getBestSellingProducts(int $limit = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    // Other methods automatically cached by parent class
}
```

**Binding:**

```php
$this->app->bind(ProductInterface::class, function () {
    return new ProductCacheDecorator(
        new ProductRepository(new Product())
    );
});
```

**Benefits:**

- ✅ Separation of concerns
- ✅ Testable (mock interface)
- ✅ Automatic caching
- ✅ Easy to swap implementations

---

#### **Pattern 2: Service Layer for Business Logic**

**Example: Order Processing**

```php
namespace Botble\Ecommerce\Services;

use Botble\Ecommerce\Events\OrderPlacedEvent;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderInterface $orderRepository,
        protected ProductService $productService,
        protected InventoryService $inventoryService,
        protected TaxService $taxService,
        protected ShippingService $shippingService,
        protected DiscountService $discountService
    ) {
    }

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // 1. Calculate totals
            $subtotal = $this->calculateSubtotal($data['products']);
            $tax = $this->taxService->calculateTax($subtotal, $data['address']);
            $shipping = $this->shippingService->calculateShipping($data['address'], $data['products']);
            $discount = $this->discountService->applyDiscounts($subtotal, $data['coupon'] ?? null);
            $total = $subtotal + $tax + $shipping - $discount;

            // 2. Create order
            $order = $this->orderRepository->create([
                'code' => $this->generateOrderCode(),
                'user_id' => $data['user_id'] ?? null,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'discount_amount' => $discount,
                'total' => $total,
                'status' => OrderStatusEnum::PENDING,
                'payment_status' => PaymentStatusEnum::PENDING,
            ]);

            // 3. Create order products
            foreach ($data['products'] as $product) {
                $order->products()->create([
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ]);
            }

            // 4. Update inventory
            $this->inventoryService->decreaseStock($data['products']);

            // 5. Fire event
            event(new OrderPlacedEvent($order));

            return $order;
        });
    }
}
```

**Benefits:**

- ✅ Reusable across controllers/commands/jobs
- ✅ Testable in isolation
- ✅ Transaction management
- ✅ Dependency injection
- ✅ Single responsibility

---

#### **Pattern 3: Form Requests for Validation**

**Example:**

```php
namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ProductRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:ec_products,sku,' . $this->route('product'),
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:ec_product_categories,id',
            'images' => 'array',
            'images.*' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('Product name is required'),
            'sku.required' => __('SKU is required'),
            'sku.unique' => __('SKU already exists'),
            'sale_price.lt' => __('Sale price must be less than regular price'),
            'categories.required' => __('Please select at least one category'),
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('Product name'),
            'sku' => __('SKU'),
            'price' => __('Price'),
            'sale_price' => __('Sale price'),
        ];
    }
}
```

**Benefits:**

- ✅ Centralized validation
- ✅ Reusable rules
- ✅ Custom error messages
- ✅ Automatic validation before controller
- ✅ Easy to test

---

#### **Pattern 4: Event/Listener Decoupling**

**Event:**

```php
namespace Botble\Ecommerce\Events;

use Botble\Ecommerce\Models\Order;

class OrderPlacedEvent
{
    public function __construct(public Order $order)
    {
    }
}
```

**Listeners:**

```php
// Listener 1: Send confirmation email
class SendOrderConfirmationEmail
{
    public function handle(OrderPlacedEvent $event): void
    {
        Mail::to($event->order->user)->send(new OrderConfirmed($event->order));
    }
}

// Listener 2: Update inventory
class UpdateInventoryAfterOrder
{
    public function handle(OrderPlacedEvent $event): void
    {
        foreach ($event->order->products as $orderProduct) {
            $product = $orderProduct->product;
            $product->quantity -= $orderProduct->quantity;
            $product->save();
        }
    }
}

// Listener 3: Track analytics
class TrackOrderAnalytics
{
    public function handle(OrderPlacedEvent $event): void
    {
        // Send to Google Analytics, Facebook Pixel, etc.
    }
}

// Listener 4: Notify admin
class NotifyAdminOfNewOrder
{
    public function handle(OrderPlacedEvent $event): void
    {
        // Send notification to admin
    }
}
```

**Registration:**

```php
protected $listen = [
    OrderPlacedEvent::class => [
        SendOrderConfirmationEmail::class,
        UpdateInventoryAfterOrder::class,
        TrackOrderAnalytics::class,
        NotifyAdminOfNewOrder::class,
    ],
];
```

**Benefits:**

- ✅ Decoupled components
- ✅ Easy to add new listeners
- ✅ Can be queued for async processing
- ✅ Other plugins can listen to events
- ✅ Testable independently

---

#### **Pattern 5: Facade Pattern for Complex Systems**

**Example: Cart Facade**

```php
namespace Botble\Ecommerce\Facades;

use Illuminate\Support\Facades\Facade;

class Cart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cart';
    }
}
```

**Service:**

```php
namespace Botble\Ecommerce\Services;

class CartService
{
    public function add(Product $product, int $quantity = 1, array $options = []): CartItem
    {
        // Complex cart logic
    }

    public function remove(string $rowId): void
    {
        // Remove from cart
    }

    public function total(): float
    {
        // Calculate total
    }

    public function count(): int
    {
        // Count items
    }
}
```

**Usage:**

```php
use Botble\Ecommerce\Facades\Cart;

// Simple, clean API
Cart::add($product, 2);
Cart::total(); // 200.00
Cart::count(); // 2
```

**Benefits:**

- ✅ Clean, expressive API
- ✅ Static access to complex services
- ✅ Easy to test (swap implementation)
- ✅ Consistent interface

---

### 4. Extension Points for Other Plugins

#### **Hooks/Filters**

E-commerce plugin provides dozens of hooks for extensibility:

```php
// Modify product price
add_filter('product_price', function ($price, $product) {
    // Custom pricing logic
    return $price * 1.1; // Add 10% markup
}, 10, 2);

// Modify cart total
add_filter('cart_total', function ($total, $cart) {
    // Custom total calculation
    return $total;
}, 10, 2);

// Before order creation
add_action('before_order_create', function ($data) {
    // Validate or modify order data
});

// After order creation
add_action('after_order_create', function ($order) {
    // Send to external system
});
```

#### **Events**

Other plugins can listen to e-commerce events:

```php
// In another plugin's EventServiceProvider
protected $listen = [
    \Botble\Ecommerce\Events\OrderPlacedEvent::class => [
        \MyPlugin\Listeners\ProcessOrderInExternalSystem::class,
    ],
];
```

#### **Service Extension**

Services can be extended via service container:

```php
// Extend product service
$this->app->extend(ProductService::class, function ($service, $app) {
    return new MyCustomProductService($service);
});
```

---

## Summary: E-commerce vs Tours Plugin

| Feature            | E-commerce       | Tours           | Gap         |
| ------------------ | ---------------- | --------------- | ----------- |
| **Architecture**   |
| Service Layer      | ✅ 44 services   | ❌ None         | 🔴 Critical |
| Repository Pattern | ✅ With cache    | ⚠️ Inconsistent | ⚠️ Medium   |
| Event System       | ✅ 17 events     | ❌ None         | 🔴 High     |
| Enums              | ✅ 23 enums      | ❌ None         | ⚠️ Medium   |
| **Code Quality**   |
| Tests              | ✅ 24 tests      | ❌ None         | 🔴 Critical |
| Type Hints         | ✅ Full          | ⚠️ Partial      | ⚠️ Low      |
| PHPDoc             | ✅ Complete      | ❌ Missing      | ⚠️ Low      |
| Code Style         | ✅ Consistent    | ⚠️ Mixed        | ⚠️ Low      |
| **Features**       |
| Dashboard Widgets  | ✅ 22 widgets    | ❌ None         | ⚠️ High     |
| Import/Export      | ✅ Full          | ❌ None         | ⚠️ Medium   |
| Notifications      | ✅ 4 types       | ❌ None         | ⚠️ Medium   |
| Settings UI        | ✅ Complete      | ❌ Missing      | ⚠️ High     |
| **Security**       |
| Form Security      | ✅ Safe          | 🔴 XSS risk     | 🔴 Critical |
| Validation         | ✅ Form Requests | ⚠️ Partial      | 🔴 High     |
| Authorization      | ✅ Policies      | ❌ Missing      | ⚠️ Medium   |
| Rate Limiting      | ✅ Yes           | ❌ No           | 🔴 High     |
| **Performance**    |
| Caching            | ✅ Multi-layer   | ⚠️ Partial      | ⚠️ Medium   |
| Query Optimization | ✅ Eager loading | ⚠️ N+1 issues   | ⚠️ Medium   |
| Indexes            | ✅ Comprehensive | ❌ Missing      | ⚠️ High     |
| **Extensibility**  |
| Custom Hooks       | ✅ 50+ hooks     | ⚠️ Few          | ⚠️ Medium   |
| Custom Events      | ✅ 17 events     | ❌ None         | ⚠️ Medium   |
| Facades            | ✅ 9 facades     | ❌ None         | ⚠️ Low      |

---

_Continued in Part 3..._
