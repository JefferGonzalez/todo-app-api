<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function findAll(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'page' => 'integer|min:0',
            'query' => 'string',
            'status' => 'in:pending,progress,completed',
            'priority' => 'in:low,high'
        ]);

        if ($validator->fails()) {
            return $this->sendErrors('Validation error', $validator->errors());
        }

        $user_id = auth('api')->id();

        if (!$user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $query  = DB::table('tasks');

        $query->where('user_id', $user_id);

        $filter = $request->query('query');
        $status = $request->query('status');
        $priority = $request->query('priority');

        if ($filter) {
            $query->where('title', 'like', "%$filter%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        $count = $query->count();

        $skip = $request->page ?? 0;
        $take = 6;

        $tasks = $query
            ->offset($skip * $take)
            ->limit($take)
            ->get(['id', 'title', 'description', 'status', 'priority']);

        $data = [
            'data' => $tasks,
            'info' => [
                'pages' => ceil($count / $take)
            ],
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function findOne($id)
    {
        $user_id = auth('api')->id();

        if (!$user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $task = Task::with('user')->where('id', $id)->where('user_id', $user_id)->first(['id', 'title', 'description', 'status', 'priority']);

        if (!$task) {
            $data = [
                'message' => 'Task not found',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'data' => $task,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function create(Request $request)
    {
        $payload = $request->only('title', 'description', 'status', 'priority', 'user_id');

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'string|max:255|nullable',
            'status' => 'required|in:pending,progress,completed',
            'priority' => 'required|in:low,high'
        ];

        $messages = [
            'title.required' => 'Por favor, ingrese un título.',
            'title.max' => 'El título debe tener menos de 255 caracteres.',
            'description.string' => '',
            'description.max' => 'La descripción debe tener menos de 255 caracteres.',
            'status.required' => 'Por favor, seleccione un estado.',
            'status.in' => 'El estado debe ser Pendiente, En progreso o Completado.',
            'priority.required' => 'Por favor, seleccione una prioridad.',
            'priority.in' => 'La prioridad debe ser Baja o Alta'
        ];

        $validator = Validator::make($payload,  $rules, $messages);

        if ($validator->fails()) {
            return $this->sendErrors('Validation error', $validator->errors());
        }

        $user_id = auth('api')->id();

        if (!$user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $task = Task::create([
            'title' => $payload['title'],
            'description' => $payload['description'] ?? 'No description',
            'status' => $payload['status'],
            'priority' => $payload['priority'],
            'user_id' => $user_id
        ]);

        if (!$task) {
            $data = [
                'message' => 'Task not created',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'data' => $task,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function update(Request $request, $id)
    {
        $user_id = auth('api')->id();

        if (!$user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payload = $request->only('title', 'description', 'status', 'priority');
        $rules = [
            'title' => 'string|max:255',
            'description' => 'string|max:255|nullable',
            'status' => 'in:pending,progress,completed',
            'priority' => 'in:low,high'
        ];

        $messages = [
            'title.max' => 'El título debe tener menos de 255 caracteres.',
            'description.max' => 'La descripción debe tener menos de 255 caracteres.',
            'status.in' => 'El estado debe ser Pendiente, En progreso o Completado.',
            'priority.in' => 'La prioridad debe ser Baja o Alta'
        ];

        $validator = Validator::make($payload, $rules, $messages);

        if ($validator->fails()) {
            return $this->sendErrors('Validation error', $validator->errors());
        }

        $task = Task::with('user')->where('id', $id)->where('user_id', $user_id)->first();

        if (!$task) {
            $data = [
                'message' => 'Task not found',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $task->title = $payload['title'] ?? $task->title;
        $task->description =  $payload['description'] ?  $payload['description'] : 'No description';
        $task->status =  $payload['status'] ?? $task->status;
        $task->priority =  $payload['priority'] ?? $task->priority;

        $task->save();

        $data = [
            'data' => $task,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function delete($id)
    {

        $user_id = auth('api')->id();

        if (!$user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $task = Task::with('user')->where('id', $id)->where('user_id', $user_id)->first();

        if (!$task) {
            $data = [
                'message' => 'Task not found',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $task->delete();

        $data = [
            'message' => 'Task deleted',
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
