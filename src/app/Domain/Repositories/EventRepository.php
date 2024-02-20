<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as paginator;

interface EventRepository
{
    public function create(Event $event): void;
    public function update(Event $event): void;
    public function list(array $data): paginator;
    public function delete(Event $event): void;
}
