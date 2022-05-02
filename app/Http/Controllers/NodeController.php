<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNodeRequest;
use App\Http\Requests\UpdateNodeRequest;
use App\Http\Resources\NodeResource;
use App\Models\Node;
use Illuminate\Database\Eloquent\Collection;

class NodeController extends Controller
{
    public function index(): Collection
    {
        return Node::all()->transform(function (Node $node): NodeResource {
            return NodeResource::make($node)
                ->includes(['parent_id', 'height', 'children']);
        });
    }

    public function show(Node $node): NodeResource
    {
        $node->load('children');

        return NodeResource::make($node)
            ->includes(['parent_id', 'height', 'children']);
    }

    public function store(CreateNodeRequest $request): NodeResource
    {
        $node = Node::create($request->only([
            'name',
            'parent_id',
            'department',
            'programming_language',
        ]));

        return $this->show($node);
    }

    public function update(Node $node, UpdateNodeRequest $request): NodeResource
    {
        $node->update($request->only([
            'name',
            'parent_id',
            'department',
            'programming_language',
        ]));

        return $this->show($node);
    }
}
