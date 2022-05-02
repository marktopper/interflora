<?php

namespace Tests\Feature;

use App\Models\Node;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NodeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_node()
    {
        $response = $this->post('/api/nodes', [
            'name' => 'Awesome Node',
            'parent_id' => null,
            'department' => null,
            'programming_language' => null,
        ]);

        $response->assertStatus(201);
        $response->assertExactJson([
            'data' => [
                'id' => 1,
                'name' => 'Awesome Node',
                'parent_id' => null,
                'department' => null,
                'programming_language' => null,
                'children' => [],
                'height' => 0,
            ],
        ]);
    }

    public function test_creating_node_with_department()
    {
        $response = $this->post('/api/nodes', [
            'name' => 'Awesome Node',
            'parent_id' => null,
            'department' => 'IT',
            'programming_language' => null,
        ]);

        $response->assertStatus(201);
        $response->assertExactJson([
            'data' => [
                'id' => 1,
                'name' => 'Awesome Node',
                'parent_id' => null,
                'department' => 'IT',
                'programming_language' => null,
                'children' => [],
                'height' => 0,
            ],
        ]);
    }

    public function test_creating_node_with_programming_language()
    {
        $response = $this->post('/api/nodes', [
            'name' => 'Awesome Node',
            'parent_id' => null,
            'department' => null,
            'programming_language' => 'php',
        ]);

        $response->assertStatus(201);
        $response->assertExactJson([
            'data' => [
                'id' => 1,
                'name' => 'Awesome Node',
                'parent_id' => null,
                'department' => null,
                'programming_language' => 'php',
                'children' => [],
                'height' => 0,
            ],
        ]);
    }

    public function test_creating_node_with_parent_id()
    {
        Node::factory()->create();

        $response = $this->post('/api/nodes', [
            'name' => 'Awesome Node',
            'parent_id' => 1,
            'department' => null,
            'programming_language' => null,
        ]);

        $response->assertStatus(201);
        $response->assertExactJson([
            'data' => [
                'id' => 2,
                'name' => 'Awesome Node',
                'parent_id' => 1,
                'department' => null,
                'programming_language' => null,
                'children' => [],
                'height' => 1,
            ],
        ]);
    }

    public function test_creating_node_with_parent_id_height_two()
    {
        Node::factory()->create();
        Node::factory()->create([
            'parent_id' => 1,
        ]);

        $response = $this->post('/api/nodes', [
            'name' => 'Awesome Node',
            'parent_id' => 2,
            'department' => null,
            'programming_language' => null,
        ]);

        $response->assertStatus(201);
        $response->assertExactJson([
            'data' => [
                'id' => 3,
                'name' => 'Awesome Node',
                'parent_id' => 2,
                'department' => null,
                'programming_language' => null,
                'children' => [],
                'height' => 2,
            ],
        ]);
    }

    public function test_getting_node()
    {
        Node::factory()->create([
            'name' => 'Awesome Node',
        ]);

        $response = $this->get('/api/nodes/1');

        $response->assertStatus(200);
        $response->assertExactJson([
            'data' => [
                'id' => 1,
                'name' => 'Awesome Node',
                'parent_id' => null,
                'department' => null,
                'programming_language' => null,
                'children' => [],
                'height' => 0,
            ],
        ]);
    }

    public function test_getting_node_with_children()
    {
        Node::factory()->create([
            'name' => 'Awesome Node',
        ]);

        Node::factory()->create([
            'parent_id' => 1,
            'name' => 'Sub Node',
        ]);

        $response = $this->get('/api/nodes/1');

        $response->assertStatus(200);
        $response->assertExactJson([
            'data' => [
                'id' => 1,
                'name' => 'Awesome Node',
                'parent_id' => null,
                'department' => null,
                'programming_language' => null,
                'children' => [
                    [
                        'id' => 2,
                        'name' => 'Sub Node',
                        'department' => null,
                        'programming_language' => null,
                    ],
                ],
                'height' => 0,
            ],
        ]);
    }

    public function test_updating_parent_id()
    {
        // Create available parents
        for ($i = 1; $i <= 2; $i++) {
            Node::factory()->create([
                'name' => 'Awesome #' . $i,
            ]);
        }

        // Create Node
        Node::factory()->create([
            'parent_id' => 1,
            'name' => 'Sub Node',
        ]);

        $response = $this->patch('/api/nodes/3', [
            'parent_id' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertExactJson([
            'data' => [
                'id' => 3,
                'name' => 'Sub Node',
                'parent_id' => 1,
                'department' => null,
                'programming_language' => null,
                'children' => [],
                'height' => 1,
            ],
        ]);
    }

    public function test_updating_parent_id_to_invalid()
    {
        // Create available parents
        for ($i = 1; $i <= 2; $i++) {
            Node::factory()->create([
                'name' => 'Awesome #' . $i,
            ]);
        }

        // Create Node
        Node::factory()->create([
            'parent_id' => 1,
            'name' => 'Sub Node',
        ]);

        $response = $this->patch('/api/nodes/3', [
            'parent_id' => 4,
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'The selected parent id is invalid.',
            'errors' => [
                'parent_id' => [
                    'The selected parent id is invalid.',
                ],
            ],
        ]);
    }

    public function test_updating_parent_id_to_self()
    {
        // Create available parents
        for ($i = 1; $i <= 2; $i++) {
            Node::factory()->create([
                'name' => 'Awesome #' . $i,
            ]);
        }

        // Create Node
        Node::factory()->create([
            'parent_id' => 1,
            'name' => 'Sub Node',
        ]);

        $response = $this->patch('/api/nodes/3', [
            'parent_id' => 3,
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'message' => 'Parent cannot be self.',
            'errors' => [
                'parent_id' => [
                    'Parent cannot be self.',
                ],
            ],
        ]);
    }
}
