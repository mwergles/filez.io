<?php

namespace Tests\Feature;

use App\Models\Node;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NodeTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    public function test_nodes_can_be_retrieved()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());
        DB::table('sessions')->insert([
            'id' => 'test',
            'user_id' => $user->id,
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'payload' => 'test',
            'last_activity' => time(),
        ]);

        $node = Node::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/node');

        $response->assertStatus(200);
        $response->assertSee($node->name);
        $response->assertSee('nodes');
        $response->assertSee('path');
    }

    public function test_nodes_can_be_retrieved_from_a_parent()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());

        // the session last activity is used to the temporary download urls
        DB::table('sessions')->insert([
            'id' => 'test',
            'user_id' => $user->id,
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'payload' => 'test',
            'last_activity' => time(),
        ]);

        $parentNode = $user->nodes()->create([
            'name' => 'Parent Node',
            'type' => 'folder',
        ]);

        $childDirectory = $user->nodes()->create([
            'name' => 'Child Directory',
            'type' => 'folder',
            'parent_id' => $parentNode->id,
        ]);

        $grandChildNode = $user->nodes()->create([
            'name' => 'Grand Child Node',
            'type' => 'folder',
            'parent_id' => $childDirectory->id,
        ]);

        $node = Node::factory()->create([
            'name' => 'Child Node',
            'user_id' => $user->id,
            'parent_id' => $parentNode->id,
        ]);

        $notSiblingNode = Node::factory()->create([
            'name' => 'Not Sibling Node',
            'user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/node/{$parentNode->id}");

        $response->assertStatus(200);
        $response->assertSee($node->name);
        $response->assertDontSee($grandChildNode->name);
        $response->assertDontSee($notSiblingNode->name);
    }

    public function test_nodes_path_have_a_valid_order()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());

        $node4 = Node::create(['name' => 'Node 4', 'user_id' => $user->id, 'type' => 'folder']);
        $node3 = Node::create(['name' => 'Node 3', 'parent_id' => $node4->id, 'user_id' => $user->id, 'type' => 'folder']);
        $node2 = Node::create(['name' => 'Node 2', 'parent_id' => $node3->id, 'user_id' => $user->id, 'type' => 'folder']);
        $node1 = Node::create(['name' => 'Node 1', 'parent_id' => $node2->id, 'user_id' => $user->id, 'type' => 'folder']);

        $response = $this->getJson('/api/node/' . $node1->id);

        $response->assertJson([
            'data' => [
                'nodes' => [],
                'path' => [
                    ['id' => $node4->id, 'name' => 'Node 4'],
                    ['id' => $node3->id, 'name' => 'Node 3'],
                    ['id' => $node2->id, 'name' => 'Node 2'],
                    ['id' => $node1->id, 'name' => 'Node 1'],
                ],
            ],
        ]);
    }

    public function test_nodes_can_be_moved_to_a_new_parent_folder()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());

        $parentNode = $user->nodes()->create([
            'name' => 'Parent Node',
            'type' => 'folder',
        ]);

        $emptyNode = $user->nodes()->create([
            'name' => 'Empty Node',
            'type' => 'folder',
        ]);

        $childNode = $user->nodes()->create([
            'name' => 'Test Node',
            'type' => 'folder',
            'parent_id' => $parentNode->id,
        ]);

        $response = $this->patch('/api/node/move/', [
            'nodeId' => $childNode->id,
            'targetId' => $emptyNode->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('nodes', [
            'id' => $childNode->id,
            'parent_id' => $emptyNode->id,
        ]);
        $this->assertDatabaseMissing('nodes', [
            'parent_id' => $parentNode->id,
        ]);
    }

    public function test_nodes_can_only_be_moved_to_a_folder_node()
    {
        $this->actingAs($user = User::factory()->create());

        $parentNode = $user->nodes()->create([
            'name' => 'Parent Node',
            'type' => 'folder',
        ]);

        $childNode = $user->nodes()->create([
            'name' => 'Test Node',
            'type' => 'folder',
            'parent_id' => $parentNode->id,
        ]);

        $invalidTargetNode = $user->nodes()->create([
            'name' => 'Invalid Target Node',
            'type' => 'file',
        ]);

        $response = $this->patch('/api/node/move/', [
            'nodeId' => $childNode->id,
            'targetId' => $invalidTargetNode->id,
        ]);

        $response->assertStatus(400);
        $this->assertDatabaseHas('nodes', [
            'id' => $childNode->id,
            'parent_id' => $parentNode->id,
        ]);
        $this->assertDatabaseMissing('nodes', [
            'parent_id' => $invalidTargetNode->id,
        ]);
    }

    public function test_nodes_can_be_renamed()
    {
        $this->actingAs($user = User::factory()->create());

        $node = $user->nodes()->create([
            'name' => 'Test Node',
            'type' => 'folder'
        ]);

        $response = $this->patch("/api/node/{$node->id}", [
            'name' => 'New Node Name'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('nodes', [
            'id' => $node->id,
            'name' => 'New Node Name',
        ]);
    }

    public function test_folder_nodes_can_be_created()
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->post('/api/node/folder', [
            'name' => 'New Folder',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('nodes', [
            'name' => 'New Folder',
            'type' => 'folder'
        ]);
    }

    public function test_files_can_be_uploaded()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());

        Storage::fake('s3');

        $fileName = 'test.txt';
        $fileBeingUploaded = UploadedFile::fake()->create($fileName, 100, 'text/plain');

        $response = $this->post('/api/node/file', [
            'file' => $fileBeingUploaded,
        ]);

        $data = json_decode($response->getContent(), true);

        $response->assertStatus(201);
        $this->assertDatabaseHas('nodes', [
            'name' => $fileName,
            'type' => 'file'
        ]);
        Storage::disk('s3')->assertExists($data['data']['id']);
    }

    public function test_files_could_not_be_uploaded_to_storage()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());

        Storage::shouldReceive('put')
            ->once()
            ->andReturn(false);

        $fileName = 'test.txt';
        $fileBeingUploaded = UploadedFile::fake()->create($fileName, 100, 'text/plain');

        $response = $this->post('/api/node/file', [
            'file' => $fileBeingUploaded,
        ]);

        $response->assertStatus(500);
        $this->assertDatabaseMissing('nodes', [
            'name' => $fileName,
            'type' => 'file'
        ]);
    }

    public function test_node_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());

        $node = $user->nodes()->create([
            'name' => 'Test Node',
            'type' => 'folder'
        ]);

        $response = $this->delete("/api/node/{$node->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('nodes', [
            'id' => $node->id,
        ]);
    }

    public function test_node_could_not_be_deleted_from_storage()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($user = User::factory()->create());

        Storage::shouldReceive('delete')
            ->once()
            ->andReturn(false);

        $node = $user->nodes()->create([
            'name' => 'Test Node',
            'type' => 'folder'
        ]);

        $response = $this->delete("/api/node/{$node->id}");

        $response->assertStatus(500);
        $this->assertDatabaseHas('nodes', [
            'id' => $node->id,
        ]);
    }
}
