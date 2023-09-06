<?php

namespace Kapi\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;
use Kapi\Http\Resources\JobTitle\JobTitleResource;
use Kapi\Http\Resources\User\UserResource;

class TaskResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'task';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => (new UserResource($this->whenLoaded('user'))),
            'ref' => $this->ref,
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => (string) $this->start_date,
            'end_date' => (string) $this->end_date,
            'duration' => $this->whenNotNull($this->duration),
            'is_completed' => (bool) $this->is_completed,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'completed_at' => $this->whenNotNull($this->completed_at),
        ];
    }
}
