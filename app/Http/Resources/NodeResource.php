<?php

namespace App\Http\Resources;

use App\Models\Node;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class NodeResource extends JsonResource
{
    protected $include = [];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $response = collect(parent::toArray($request))
            ->only($this->attributeKeys());

        if (isset($response['children'])) {
            $response['children'] = $this->children->transform(function (Node $node) {
                return NodeResource::make($node);
            });
        }

        return $response;
    }

    public function includes(array $keys): self
    {
        $this->include = $keys;

        return $this;
    }

    public function getIncludes(): array
    {
        return $this->include;
    }

    protected function attributeKeys(): array
    {
        return array_merge([
            'id',
            'name',
            'programming_language',
            'department',
        ], $this->include);
    }
}
