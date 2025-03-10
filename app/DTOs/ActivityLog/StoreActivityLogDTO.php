<?php

namespace App\DTOs\ActivityLog;

use App\DTOs\BaseDTO;

class StoreActivityLogDTO extends BaseDTO
{
    public function __construct(
        public int $request_log_id,
        public string $description,
        public bool $showable,
    ) {}
}
