<?php

namespace Kapi\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    /**
     * fillData
     *
     * @return Model
     */
    public function fillData(array $data): Model
    {
        $validated_data = $this->getFillableData($data);
        if (count($validated_data) > 0) {
            $this->fill($validated_data);
        }

        return $this;
    }

    /**
     * getFillableData
     */
    public function getFillableData(array $data): array
    {
        $validated_data = [];
        $fillable = $this->getFillable();
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $key => $value) {
                if (in_array($key, $fillable)) {
                    $validated_data[$key] = $value;
                }
            }
        }

        return $validated_data;
    }
}
