<?php

namespace App\Http\Controllers\Identity;

use App\Http\Controllers\Controller;
use App\Http\Requests\Identity\InviteRequestStoreRequest;
use App\Services\Identity\InviteRequestStoreService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class InviteRequestStoreController extends Controller
{
    public function __invoke(InviteRequestStoreRequest $request, InviteRequestStoreService $service): RedirectResponse
    {
        $service->execute($request->validated());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Recebemos seu pedido! Em breve entraremos em contato.',
        ]);

        return back();
    }
}
