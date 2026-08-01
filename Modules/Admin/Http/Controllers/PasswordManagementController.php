<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Clients\Models\Client;

class PasswordManagementController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeSuperAdmin($request);

        $search = trim((string) $request->input('search'));

        $admins = Admin::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'admin_page')
            ->withQueryString();

        $teamMembers = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'team_page')
            ->withQueryString();

        $clients = Client::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10, ['*'], 'client_page')
            ->withQueryString();

        return view('admin::passwords.index', compact('admins', 'teamMembers', 'clients', 'search'));
    }

    public function update(Request $request)
    {
        $this->authorizeSuperAdmin($request);

        $validated = $request->validate([
            'account_type' => ['required', 'in:admin,team_member,client'],
            'account_id' => ['required', 'integer'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $account = $this->resolveAccount($validated['account_type'], (int) $validated['account_id']);

        $account->forceFill([
            'password' => Hash::make($validated['password']),
        ]);

        if ($this->supportsRememberToken($account)) {
            $account->setRememberToken(Str::random(60));
        }

        $account->save();

        return back()->with('success', $this->successMessage($validated['account_type'], $account->name));
    }

    protected function authorizeSuperAdmin(Request $request): void
    {
        abort_unless($request->user('admin')?->isSuperAdmin(), 403);
    }

    protected function resolveAccount(string $accountType, int $accountId): Admin|User|Client
    {
        return match ($accountType) {
            'admin' => Admin::query()->findOrFail($accountId),
            'team_member' => User::query()->findOrFail($accountId),
            'client' => Client::query()->findOrFail($accountId),
        };
    }

    protected function supportsRememberToken(Admin|User|Client $account): bool
    {
        $tokenColumn = $account->getRememberTokenName();

        return !empty($tokenColumn) && Schema::hasColumn($account->getTable(), $tokenColumn);
    }

    protected function successMessage(string $accountType, string $name): string
    {
        return match ($accountType) {
            'admin' => "Password updated for admin account {$name}.",
            'team_member' => "Password updated for team member {$name}.",
            'client' => "Password updated for client {$name}.",
        };
    }
}
