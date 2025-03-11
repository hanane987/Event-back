<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user)
    {
        return true;
    }

    public function view(?User $user, Event $event)
    {
        if ($event->status === 'published') {
            return true;
        }
        
        return $user && ($user->isAdmin() || $user->id === $event->creator_id);
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Event $event)
    {
        return $user->isAdmin() || $user->id === $event->creator_id;
    }

    public function delete(User $user, Event $event)
    {
        return $user->isAdmin() || $user->id === $event->creator_id;
    }

    public function createTicket(User $user, Event $event)
    {
        return $user->isAdmin() || $user->id === $event->creator_id;
    }

    public function updateTicket(User $user, Event $event)
    {
        return $user->isAdmin() || $user->id === $event->creator_id;
    }

    public function deleteTicket(User $user, Event $event)
    {
        return $user->isAdmin() || $user->id === $event->creator_id;
    }

    public function assignCategory(User $user, Event $event)
    {
        return $user->isAdmin() || $user->id === $event->creator_id;
    }

    public function updateCategory(User $user, Event $event)
    {
        return $user->isAdmin() || $user->id === $event->creator_id;
    }
}
