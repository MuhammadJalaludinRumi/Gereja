<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index()
    {
        /** @var \Illuminate\Support\Collection<int, \App\Models\Event> $events */
        $events = Event::where('organization_id', Auth::user()->role->organization_id)
            ->orderByDesc('id')
            ->get();

        if ($events->isEmpty()) {
            return response()->json([]);
        }

        $memberIds = collect($events)->flatMap(function ($event) {
            return collect([
                $event->service_ministry,
                $event->coordinator,
                $event->liturgist,
                $event->pf_assistant,
            ])
                ->merge($event->musician ?? [])
                ->merge($event->worship_leader ?? [])
                ->merge($event->offering_officer ?? [])
                ->merge($event->choir ?? []);
        })
            ->filter()
            ->unique()
            ->values();

        $organizationIds = collect($events)
            ->pluck('organization_id')
            ->unique()
            ->values();

        $members = Member::whereIn('id', $memberIds)
            ->select('id', 'name')
            ->get()
            ->keyBy('id');

        $organization = Organization::where('id', $organizationIds)
            ->select('id', 'name')
            ->first();

        $events = $events->map(function ($event) use ($members, $organization) {
            return [
                'id' => $event->id,
                'service_type' => $event->service_type,
                'service_date' => $event->service_date,
                'service_time' => $event->service_time,
                'service_ministry' => $members[$event->service_ministry],
                'organization' => $organization,

                'scripture_reading' => $event->scripture_reading,
                'sermon_text' => $event->sermon_text,
                'sermon_theme' => $event->sermon_theme,

                'coordinator' => $members[$event->coordinator] ?? null,
                'liturgist' => $members[$event->liturgist] ?? null,
                'pf_assistant' => $members[$event->pf_assistant] ?? null,

                'musician' => collect($event->musician)->map(fn($id) => $members[$id] ?? null)->filter()->values(),
                'worship_leader' => collect($event->worship_leader)->map(fn($id) => $members[$id] ?? null)->filter()->values(),
                'offering_officer' => collect($event->offering_officer)->map(fn($id) => $members[$id] ?? null)->filter()->values(),
                'choir' => collect($event->choir)->map(fn($id) => $members[$id] ?? null)->filter()->values(),

                'male_attendance' => $event->male_attendance,
                'female_attendance' => $event->female_attendance,
                'total_attendance' => $event->total_attendance,

                'red_envelope' => $event->red_envelope,
                'blue_envelope' => $event->blue_envelope,
                'other_envelope' => $event->other_envelope,
                'total_envelope' => $event->total_envelope,

                'note' => $event->note,
            ];
        });

        return response()->json($events);
    }

    public function show($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $memberIds = collect([
            $event->service_ministry,
            $event->coordinator,
            $event->liturgist,
            $event->pf_assistant,
        ])
            ->merge($event->musician ?? [])
            ->merge($event->worship_leader ?? [])
            ->merge($event->offering_officer ?? [])
            ->merge($event->choir ?? [])
            ->filter()
            ->unique()
            ->values();

        $members = Member::whereIn('id', $memberIds)
            ->select('id', 'name')
            ->get()
            ->keyBy('id');

        $organization = Organization::select('id', 'name')
            ->find($event->organization_id);

        return response()->json([
            'id' => $event->id,
            'service_type' => $event->service_type,
            'service_date' => $event->service_date,
            'service_time' => $event->service_time,

            'service_ministry' => $members[$event->service_ministry] ?? null,
            'organization' => $organization,

            'scripture_reading' => $event->scripture_reading,
            'sermon_text' => $event->sermon_text,
            'sermon_theme' => $event->sermon_theme,

            'coordinator' => $members[$event->coordinator] ?? null,
            'liturgist' => $members[$event->liturgist] ?? null,
            'pf_assistant' => $members[$event->pf_assistant] ?? null,

            'musician' => collect($event->musician)
                ->map(fn($id) => $members[$id] ?? null)
                ->filter()
                ->values(),

            'worship_leader' => collect($event->worship_leader)
                ->map(fn($id) => $members[$id] ?? null)
                ->filter()
                ->values(),

            'offering_officer' => collect($event->offering_officer)
                ->map(fn($id) => $members[$id] ?? null)
                ->filter()
                ->values(),

            'choir' => collect($event->choir)
                ->map(fn($id) => $members[$id] ?? null)
                ->filter()
                ->values(),

            'male_attendance' => $event->male_attendance,
            'female_attendance' => $event->female_attendance,
            'total_attendance' => $event->total_attendance,

            'red_envelope' => $event->red_envelope,
            'blue_envelope' => $event->blue_envelope,
            'other_envelope' => $event->other_envelope,
            'total_envelope' => $event->total_envelope,

            'note' => $event->note,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_type' => [
                'required',
                Rule::in([
                    'Umum',
                    'KRT',
                    'Pemuda Remaja',
                    'Sekolah Minggu',
                    'Pria',
                    'Lansia',
                    'Perempuan',
                    'Perayaan HRG',
                    'Lain-lain'
                ])
            ],

            'service_date' => 'required|date',
            'service_time' => 'required|date_format:H:i',

            'service_ministry' => 'required|integer',

            'scripture_reading' => 'required|string|max:100',
            'sermon_text' => 'required|string|max:100',
            'sermon_theme' => 'required|string',

            'coordinator' => 'required|integer|exists:members,id',
            'liturgist' => 'required|integer|exists:members,id',
            'pf_assistant' => 'required|integer|exists:members,id',

            'musician' => 'required|array|min:1',
            'musician.*' => 'integer|exists:members,id',

            'worship_leader' => 'required|array|min:1',
            'worship_leader.*' => 'integer|exists:members,id',

            'offering_officer' => 'required|array|min:1',
            'offering_officer.*' => 'integer|exists:members,id',

            'choir' => 'required|array|min:1',
            'choir.*' => 'integer|exists:members,id',

            'male_attendance' => 'nullable|integer|min:0',
            'female_attendance' => 'nullable|integer|min:0',

            'red_envelope' => 'nullable|integer|min:0',
            'blue_envelope' => 'nullable|integer|min:0',
            'other_envelope' => 'nullable|integer|min:0',

            'note' => 'nullable|string',
        ]);

        $data['organization_id'] = Auth::user()->role->organization_id;

        $data['total_attendance'] =
            ($data['male_attendance'] ?? 0) +
            ($data['female_attendance'] ?? 0);

        $data['total_envelope'] =
            ($data['red_envelope'] ?? 0) +
            ($data['blue_envelope'] ?? 0) +
            ($data['other_envelope'] ?? 0);

        $event = Event::create($data);

        return response()->json([
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $data = $request->validate([
            'service_type' => [
                'sometimes',
                Rule::in([
                    'Umum',
                    'KRT',
                    'Pemuda Remaja',
                    'Sekolah Minggu',
                    'Pria',
                    'Lansia',
                    'Perempuan',
                    'Perayaan HRG',
                    'Lain-lain'
                ])
            ],

            'service_date' => 'sometimes|date',
            'service_time' => 'sometimes|date_format:H:i',

            'service_ministry' => 'sometimes|integer|exists:members,id',

            'scripture_reading' => 'sometimes|string|max:100',
            'sermon_text' => 'sometimes|string|max:100',
            'sermon_theme' => 'sometimes|string',

            'coordinator' => 'sometimes|integer|exists:members,id',
            'liturgist' => 'sometimes|integer|exists:members,id',
            'pf_assistant' => 'sometimes|integer|exists:members,id',

            'musician' => 'sometimes|array|min:1',
            'musician.*' => 'integer|exists:members,id',

            'worship_leader' => 'sometimes|array|min:1',
            'worship_leader.*' => 'integer|exists:members,id',

            'offering_officer' => 'sometimes|array|min:1',
            'offering_officer.*' => 'integer|exists:members,id',

            'choir' => 'sometimes|array|min:1',
            'choir.*' => 'integer|exists:members,id',

            'male_attendance' => 'sometimes|integer|min:0',
            'female_attendance' => 'sometimes|integer|min:0',

            'red_envelope' => 'sometimes|integer|min:0',
            'blue_envelope' => 'sometimes|integer|min:0',
            'other_envelope' => 'sometimes|integer|min:0',

            'note' => 'sometimes|nullable|string',
        ]);

        $data['total_attendance'] =
            ($data['male_attendance'] ?? 0) +
            ($data['female_attendance'] ?? 0);

        $data['total_envelope'] =
            ($data['red_envelope'] ?? 0) +
            ($data['blue_envelope'] ?? 0) +
            ($data['other_envelope'] ?? 0);

        $event->update($data);

        return response()->json([
            'message' => 'Event updated successfully',
            'data' => $event
        ]);
    }

    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete($id);

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
