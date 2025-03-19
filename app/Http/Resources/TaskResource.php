<?php

namespace App\Http\Resources;


class TaskResource extends BaseResource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['can_complete_task'] = $this->canCompleteTask();
        $data['can_edit_task'] = $this->canEditTask();

        return $data;
    }
}
