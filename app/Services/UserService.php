<?php
namespace App\Services;
use App\Enums\UserEnum;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserService{
    public static function getUsersByRole($role = UserEnum::STUDENT): AnonymousResourceCollection
    {
        return UserResource::collection(
            User::query()
                ->select('id', 'first_name', 'last_name', 'email', 'deleted_at')
                ->filter(request()?->only('search', 'trashed', 'column', 'direction'))
                ->role($role->value)
                ->paginate()
                ->withQueryString());
    }

}
