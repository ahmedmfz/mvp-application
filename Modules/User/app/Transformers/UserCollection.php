<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;


class UserCollection extends ResourceCollection
{
    public function paginationInformation($request, $paginated, $default)
    {
        unset($default['links']);
        unset($default['meta']);
        return $default;
    }
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    */
    public function toArray($request)
    {
        return [
            'users'                => UserResource::collection($this->collection),
            'pagination'          => [
                "current_page"    => $this->currentPage(),
                "total_pages"     => $this->lastPage(),
                "per_page"        => $this->perPage(),
                "total_items"     => $this->total(),
            ],
        ];
    }

}