<?php

namespace App\Http\Controllers\Api;
use App\Application\UseCases\CreateEventUseCase;
use App\Application\UseCases\ListEventUseCase;
use App\Application\UseCases\DeleteEventUseCase;
use App\Application\UseCases\UpdateEventUseCase;
use App\Domain\Services\EventRecurringService as EventRepeaterService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Infrastructure\Database\DatabaseEventRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function store(StoreEventRequest $request): JsonResponse
    {
        $useCase = new CreateEventUseCase(new DatabaseEventRepository(), new EventRepeaterService());
        $data = $request->validated();

        $useCase->create($data);
        return response()->json(['status' => 'event created successfully']);
    }

    public function update(UpdateEventRequest $request, $uuid): JsonResponse
    {
        $useCase = new UpdateEventUseCase(new DatabaseEventRepository());
        $data = $request->validated();

        $useCase->update($data, $uuid);
        return response()->json(['status' => 'event updated successfully']);
    }

    public function list(Request $request): JsonResponse
    {
        $useCase = new ListEventUseCase(new DatabaseEventRepository());
        $data = $useCase->list($request->all());

        return response()->json($data);
    }

    public function delete($uuid): JsonResponse
    {
        $useCase = new DeleteEventUseCase(new DatabaseEventRepository());
        $useCase->delete($uuid);

        return response()->json(['status' => 'event deleted successfully']);
    }
}
