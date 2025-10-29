<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Candidate;
use App\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_candidate_with_portfolio(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $portfolio = Portfolio::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.candidates.store'), [
                'name' => 'Test Candidate',
                'portfolio_id' => $portfolio->id,
                'position' => 'Test Position',
            ])
            ->assertRedirect(route('admin.candidates.index'))
            ->assertSessionHas('success', 'Candidate created successfully.');

        $this->assertDatabaseHas('candidates', [
            'name' => 'Test Candidate',
            'portfolio_id' => $portfolio->id,
            'position' => 'Test Position',
        ]);
    }

    public function test_admin_can_edit_candidate_with_portfolio(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $portfolio = Portfolio::factory()->create();
        $newPortfolio = Portfolio::factory()->create();
        $candidate = Candidate::factory()->create([
            'portfolio_id' => $portfolio->id
        ]);

        $this->actingAs($admin)
            ->put(route('admin.candidates.update', $candidate), [
                'name' => 'Updated Name',
                'portfolio_id' => $newPortfolio->id,
                'position' => 'Updated Position',
            ])
            ->assertRedirect(route('admin.candidates.index'))
            ->assertSessionHas('success', 'Candidate updated successfully.');

        $this->assertDatabaseHas('candidates', [
            'id' => $candidate->id,
            'name' => 'Updated Name',
            'portfolio_id' => $newPortfolio->id,
            'position' => 'Updated Position',
        ]);
    }

    public function test_admin_sees_portfolios_on_create_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $portfolio = Portfolio::factory()->create();

        $response = $this->actingAs($admin)
            ->get(route('admin.candidates.create'));

        $response->assertOk()
            ->assertViewHas('portfolios')
            ->assertSee($portfolio->name);
    }

    public function test_admin_sees_portfolios_on_edit_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $portfolio = Portfolio::factory()->create();
        $candidate = Candidate::factory()->create([
            'portfolio_id' => $portfolio->id
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.candidates.edit', $candidate));

        $response->assertOk()
            ->assertViewHas('portfolios')
            ->assertSee($portfolio->name);
    }
}
