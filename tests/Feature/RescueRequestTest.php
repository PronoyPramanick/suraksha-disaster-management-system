<?php

namespace Tests\Feature;

use App\Models\RescueRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RescueRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_citizens_can_submit_and_track_their_rescue_requests(): void
    {
        $citizen = User::factory()->create(['role' => 'citizen']);

        $response = $this->actingAs($citizen)->post(route('rescue-requests.store'), [
            'contact_name' => 'Amina Rahman',
            'contact_phone' => '01700000000',
            'location' => 'Companiganj, Sylhet',
            'description' => 'Flood water is rising and our family is trapped.',
            'people_count' => 4,
            'urgency' => 'Critical',
        ]);

        $rescueRequest = RescueRequest::where('user_id', $citizen->id)->firstOrFail();

        $response->assertRedirect(route('rescue-requests.index'));
        $response->assertSessionHas('rescue_request_reference', $rescueRequest->id);
        $this->assertSame('Pending', $rescueRequest->status);

        $this->actingAs($citizen)
            ->get(route('rescue-requests.index'))
            ->assertOk()
            ->assertSee('#'.$rescueRequest->id)
            ->assertSee('Pending')
            ->assertSee('Companiganj, Sylhet');
    }

    public function test_citizens_can_only_see_their_own_rescue_requests(): void
    {
        $citizen = User::factory()->create(['role' => 'citizen']);
        $otherCitizen = User::factory()->create(['role' => 'citizen']);

        $ownRequest = $citizen->rescueRequests()->create([
            'contact_name' => 'Own Contact',
            'contact_phone' => '01700000001',
            'location' => 'Own Location',
            'description' => 'Own emergency',
            'people_count' => 1,
            'urgency' => 'High',
        ]);
        $otherCitizen->rescueRequests()->create([
            'contact_name' => 'Private Contact',
            'contact_phone' => '01700000002',
            'location' => 'Private Location',
            'description' => 'Private emergency',
            'people_count' => 2,
            'urgency' => 'Critical',
        ]);

        $this->actingAs($citizen)
            ->get(route('rescue-requests.index'))
            ->assertOk()
            ->assertSee('#'.$ownRequest->id)
            ->assertSee('Own Contact')
            ->assertDontSee('Private Contact')
            ->assertDontSee('Private Location');
    }

    public function test_admins_can_review_all_requests_and_update_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $citizen = User::factory()->create(['role' => 'citizen']);
        $rescueRequest = $citizen->rescueRequests()->create([
            'contact_name' => 'Citizen Contact',
            'contact_phone' => '01700000003',
            'location' => 'Barishal',
            'description' => 'Need evacuation.',
            'people_count' => 3,
            'urgency' => 'High',
        ]);

        $this->actingAs($admin)
            ->get(route('rescue-requests.index'))
            ->assertOk()
            ->assertSee('Citizen Contact');

        $this->patch(route('rescue-requests.status', $rescueRequest), [
            'status' => 'In Progress',
        ])->assertRedirect();

        $this->assertDatabaseHas('rescue_requests', [
            'id' => $rescueRequest->id,
            'status' => 'In Progress',
        ]);
    }

    public function test_non_admins_cannot_update_rescue_request_status(): void
    {
        $citizen = User::factory()->create(['role' => 'citizen']);
        $rescueRequest = $citizen->rescueRequests()->create([
            'contact_name' => 'Citizen Contact',
            'contact_phone' => '01700000004',
            'location' => 'Khulna',
            'description' => 'Need assistance.',
            'people_count' => 1,
            'urgency' => 'Moderate',
        ]);

        $this->actingAs($citizen)
            ->patch(route('rescue-requests.status', $rescueRequest), [
                'status' => 'Rescued',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('rescue_requests', [
            'id' => $rescueRequest->id,
            'status' => 'Pending',
        ]);
    }
}
