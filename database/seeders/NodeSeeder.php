<?php

namespace Database\Seeders;

use App\Models\Node;
use Illuminate\Database\Seeder;

class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*

                root
               /    \
              a      b
              |
              c
            / 	\
           d     e

        */

        Node::truncate();

        // Create root node
        $root = Node::factory()->create([
            'name' => 'root',
        ]);

        // Create child nodes of root
        $a = Node::factory()->create([
            'name' => 'a',
            'parent_id' => $root->id,
        ]);
        $b = Node::factory()->create([
            'name' => 'b',
            'parent_id' => $root->id,
        ]);

        // Create child nodes of A
        $c = Node::factory()->create([
            'name' => 'c',
            'parent_id' => $a->id,
        ]);

        // Create child nodes of C
        $d = Node::factory()->create([
            'name' => 'd',
            'parent_id' => $c->id,
        ]);
        $e = Node::factory()->create([
            'name' => 'e',
            'parent_id' => $c->id,
        ]);
    }
}
