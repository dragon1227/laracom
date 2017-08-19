<?php

namespace Tests;

use App\Addresses\Address;
use App\Categories\Category;
use App\Couriers\Courier;
use App\Couriers\Repositories\CourierRepository;
use App\Employees\Employee;
use App\Customers\Customer;
use App\OrderStatuses\OrderStatus;
use App\OrderStatuses\Repositories\OrderStatusRepository;
use App\PaymentMethods\PaymentMethod;
use App\PaymentMethods\Repositories\PaymentMethodRepository;
use App\Products\Product;
use App\Provinces\Province;
use Gloudemans\Shoppingcart\Cart;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory as Faker;
use App\Cities\City;
use App\Countries\Country;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations, DatabaseTransactions;

    protected $faker;
    protected $employee;
    protected $customer;
    protected $address;
    protected $product;
    protected $category;
    protected $country;
    protected $province;
    protected $city;
    protected $courier;
    protected $orderStatus;
    protected $paymentMethod;
    protected $cart;

    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();

        $this->faker = Faker::create();
        $this->employee = factory(Employee::class)->create();
        $this->product = factory(Product::class)->create();
        $this->category = factory(Category::class)->create();
        $this->customer = factory(Customer::class)->create();

        $courierData = [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'url' => $this->faker->sentence,
            'is_free' => 1,
            'status' => 1
        ];

        $courierRepo = new CourierRepository(new Courier);
        $this->courier = $courierRepo->createCourier($courierData);

        $orderStatusData = [
            'name' => $this->faker->name,
            'color' => $this->faker->word
        ];

        $orderStatusRepo = new OrderStatusRepository(new OrderStatus);
        $this->orderStatus = $orderStatusRepo->createOrderStatus($orderStatusData);

        $paymentMethodData = [
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->paragraph,
            'account_id' => $this->faker->word,
            'client_id' => $this->faker->word,
            'client_secret' => $this->faker->word
        ];

        $payment = new PaymentMethodRepository(new PaymentMethod);
        $this->paymentMethod = $payment->createPaymentMethod($paymentMethodData);

        $session = $this->app->make('session');
        $events = $this->app->make('events');
        $this->cart = new Cart($session, $events);
    }

    public function tearDown()
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }
}
