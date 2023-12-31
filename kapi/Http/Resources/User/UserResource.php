<?php

namespace Kapi\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Kapi\Http\Resources\JobTitle\JobTitleResource;

class UserResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'user';

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
            'api_key' => $this->api_key, // only for testing, please remove later
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'job_title' => (new JobTitleResource($this->whenLoaded('jobTitle'))),
            'contact' => (new ContactResource($this->whenLoaded('contact'))),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
