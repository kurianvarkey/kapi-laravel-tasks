<?php

namespace Tests\Unit;

use Tests\TestCase;
use Kapi\Models\User;
use Kapi\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_user_contact()
    {
        Contact::factory([
            'user_id' => User::factory()->create()->id,
            'landline' => fake()->phoneNumber(),
            'mobile' => fake()->phoneNumber(),
        ])->create();

        $this->assertEquals(1, Contact::all()->count());
    }

    /** @test */
    public function it_can_update_user_contact()
    {
        Contact::factory([
            'landline' => '015151515',
            'mobile' => '0777777',
        ])->create();

        $contact = Contact::first();

        $this->assertEquals(1, $contact->count());

        $contact->landline = '0161616161';
        $contact->mobile = '079999999';

        $contact->update();

        $contact = Contact::first();

        $this->assertEquals('0161616161', $contact->landline);
        $this->assertEquals('079999999', $contact->mobile);
    }

    /** @test */
    public function it_can_delete_user_contact()
    {
        Contact::factory()->count(1)->create();

        $contact = Contact::first();

        $this->assertEquals(1, $contact->count());

        $contact->delete();

        $this->assertEquals(0, Contact::all()->count());
    }

    /** @test */
    public function it_can_fetche_all_user_contact()
    {
        Contact::factory()->count(3)->create();

        $this->assertEquals(3, Contact::all()->count());
    }
}
