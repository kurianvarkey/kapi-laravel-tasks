<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Kapi\Models\User;
use Kapi\Models\JobTitle;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array
     */
    private $data = [];

    /**
     * end_point
     *
     * @var string
     */
    private $end_point = '1.0/users';

    public function setUp(): void
    {
        parent::setUp();   

        // Run the DatabaseSeeder...
        $this->seed();

        $password = fake()->password(8, 30);
        $this->data = [
            'job_title_id' => JobTitle::inRandomOrder()->first()->id,
            'email' => 'kurian@kurian.com',
            'password' => $password,
            'password_confirmation' => $password,
            'first_name' => 'Kurian',
            'last_name' => 'Tester',
            'dob' => '2000-02-01',
            'landline' => '02151515',
            'mobile' => '0787787',
        ];
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    public function it_can_create_a_user_from_api()
    {
        $this->post(
            $this->end_point,
            $this->data
        )->assertStatus(201)
            ->assertJson([
                'status' => 1,
            ]);

        // if post the same, it should return the validation error
        $this->post(
            $this->end_point,
            $this->data
        )->assertStatus(400);
    }

    /** @test */
    public function it_can_get_user_by_id_from_api()
    {
        $response = $this->post(
            $this->end_point,
            $this->data
        )->assertStatus(201)
            ->assertJson([
                'status' => 1,
            ]);

        //get item
        $id = $response->json("data.user.id");
        $response = $this->get(
            $this->end_point . '/' . $id,
            []
        )->assertStatus(200)
            ->assertJsonPath('data.user.id', $id);
    }

    /** @test */
    public function it_can_update_user_by_id_from_api()
    {
        $response = $this->post(
            $this->end_point,
            $this->data
        )->assertStatus(201)
            ->assertJson([
                'status' => 1,
            ]);

        //get item
        $id = $response->json("data.user.id");
        $this->data['job_tile_id'] = 2;
        $this->data['first_name'] = 'Kurian 123';
        $this->put(
            $this->end_point . '/' . $id,
            $this->data
        )->assertStatus(200);

        $this->get(
            $this->end_point . '/' . $id,
            []
        )->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('data.user.id', $id)
                    ->where('data.user.first_name', 'Kurian 123')
                    ->etc()
            );
    }

    /** @test */
    public function it_can_delete_user_by_id_from_api()
    {
        $response = $this->post(
            $this->end_point,
            $this->data
        )->assertStatus(201)
            ->assertJson([
                'status' => 1,
            ]);

        //get item
        $id = $response->json("data.user.id");
        $this->delete(
            $this->end_point . '/' . $id
        )->assertStatus(200);

        $this->get(
            $this->end_point . '/' . $id,
            []
        )->assertStatus(400)
            ->assertJson([
                'status' => 0,
                'code' => 400,
            ]);
    }

    /** @test */
    public function it_can_list_users_from_api()
    {
        User::factory()->count(100)->create();

        // get users
        // expecting users count as 15 in the first page and total as 100
        $this->get(
            $this->end_point,
        )->assertStatus(200)
            ->assertJson([
                'status' => 1,
            ])->assertJson(
                function (AssertableJson $json) {
                    return $json->where('status', 1)
                        ->where('data.meta.total', 100)
                        ->etc();
                }
            )->assertJsonCount(15, 'data.users');
    }
}
