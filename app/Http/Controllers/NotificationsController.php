<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Inertia\Inertia;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = DatabaseNotification::orderBy('created_at', 'desc')
            ->paginate(config('pagination.default'))
            ->withQueryString();
        return Inertia::render('Notifications/NotificationsList', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead($notificationId) {
        $notification = DatabaseNotification::find($notificationId);

        if ($notification->read_at === null) {
            $notification->markAsRead();
        } else {
            $notification->markAsUnread();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DatabaseNotification::destroy($id);
    }
}
