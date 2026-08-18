@if($model->updated_by || $model->created_by)
    @php
        $actorId = $model->updated_by ?? $model->created_by;
        $actorType = $model->updater_type ?? $model->creator_type;
        $actorName = 'Unknown';
        if ($actorType == 'admin') {
            $admin = \App\Models\Admin::find($actorId);
            $actorName = $admin ? $admin->name : 'Admin';
        } else {
            $user = \App\Models\User::find($actorId);
            $actorName = $user ? $user->name : 'User';
        }
    @endphp
    <small class="text-muted d-block">{{ $actorName }}</small>
    <span class="badge bg-light-primary text-primary" style="font-size: 0.65rem;">{{ ucfirst($actorType) }}</span>
@else
    <span class="text-muted">-</span>
@endif
