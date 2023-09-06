<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Kapi\Models\JobTitle;

class JobTitleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_job_title()
    {
        JobTitle::factory([
            'job_title' => 'Admin',
        ])->create();

        $this->assertEquals(1, JobTitle::all()->count());
    }

    /** @test */
    public function it_can_update_job_title()
    {
        JobTitle::factory([
            'job_title' => 'Admin',
        ])->create();

        $jobTitle = JobTitle::JobTitle('Admin')->first();

        $this->assertEquals(1, $jobTitle->count());

        $jobTitle->job_title = 'Admin New';

        $jobTitle->update();

        $jobTitle = JobTitle::JobTitle('Admin New')->first();

        $this->assertEquals(1, $jobTitle->count());
    }

    /** @test */
    public function it_can_delete_job_title()
    {
        JobTitle::factory([
            'job_title' => 'Admin',
        ])->create();

        $jobTitle = JobTitle::JobTitle('Admin')->first();

        $this->assertEquals(1, $jobTitle->count());

        $jobTitle->delete();

        $this->assertEquals(0, JobTitle::all()->count());
    }

    /** @test */
    public function it_can_fetche_all_job_titles()
    {
        JobTitle::factory()->count(3)->create();

        $this->assertEquals(3, JobTitle::all()->count());
    }
}
