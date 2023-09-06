<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Kapi\Models\Task;
use Kapi\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array
     */
    private $header = [];

    /**
     * @var array
     */
    private $data = [];

    /**
     * end_point
     *
     * @var string
     */
    private $end_point = '1.0/tasks';

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        $this->header = [
            'Authorization' => 'Bearer ' . $user->api_key,
            'Accept' => 'application/json',
        ];

        $this->data = [
            'title' => 'Test task',
            'description' => 'Task description',
            'start_date' => '2023-01-01 10:00',
            'end_date' => '2023-01-01 10:30',
        ];
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    public function it_can_create_a_task_for_user_from_api()
    {
        $this->withHeaders($this->header)
            ->post(
                $this->end_point,
                $this->data
            )->assertStatus(201)
            ->assertJson([
                'status' => 1,
            ]);
    }

    /** @test */
    public function it_can_update_and_delete_task_by_id_for_user_from_api()
    {
        $response = $this->withHeaders($this->header)
        ->post(
            $this->end_point,
            $this->data
        )->assertStatus(201)
            ->assertJson([
                'status' => 1,
            ]);

        //get item
        $id = $response->json("data.task.id");
        $this->data['title'] = 'Test task new';

        $this->withHeaders($this->header)
        ->put(
            $this->end_point . '/' . $id,
            $this->data
        )->assertStatus(200);

        $this->withHeaders($this->header)
        ->get(
            $this->end_point . '/' . $id,
            []
        )->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->where('data.task.id', $id)
                    ->where('data.task.title', 'Test task new')
                    ->etc()
            );

        //delete item
        $this->withHeaders($this->header)
        ->delete(
            $this->end_point . '/' . $id
        )->assertStatus(200);

        $this->withHeaders($this->header)
        ->get(
            $this->end_point . '/' . $id,
            []
        )->assertStatus(400)
            ->assertJson([
                'status' => 0,
                'code' => 400,
            ]);
    }    

    /** @test */
    public function it_can_list_tasks_from_api()
    {
        Task::factory()->count(100)->create();

        // get tasks
        // expecting task count as 15 in the first page and total as 100
        $this->withHeaders($this->header)
        ->get(
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
            )->assertJsonCount(15, 'data.tasks');
    }
}
