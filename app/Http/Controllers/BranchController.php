<?php

namespace App\Http\Controllers;

use App\Helpers\DateTimeHelper;
use App\Models\Branches;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    //
    use DateTimeHelper;

    /*-----------------------
     *-----------------------
     *-----------------------
     *-----------------------
     * SUPER ADMIN
     *-----------------------
     *-----------------------
     *-----------------------
     *-----------------------*/

    public function superAdminBranches(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $superAdminExists = User::where('role', 1)->exists();
        $branches = Branches::orderBy('updated_at', 'desc')->paginate(10);

        return view('super_admin.super_admin_branches', compact(
            'currentDate',
            'currentTime',
            'branches',
            'superAdminExists'
        ));
    }

    public function superAdminBranchesActive(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $superAdminExists = User::where('role', 1)->exists();

        $branches = Branches::where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('super_admin.super_admin_branches_active', compact(
            'currentDate',
            'currentTime',
            'branches',
            'superAdminExists'
        ));
    }

    public function superAdminBranchesBlocked(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $superAdminExists = User::where('role', 1)->exists();

        $branches = Branches::where('status', 2)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('super_admin.super_admin_branches_block', compact(
            'currentDate',
            'currentTime',
            'branches',
            'superAdminExists'
        ));
    }

    public function superAdminBranchesDeleted(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $superAdminExists = User::where('role', 1)->exists();

        $branches = Branches::where('status', 0)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('super_admin.super_admin_branches_trash', compact(
            'currentDate',
            'currentTime',
            'branches',
            'superAdminExists'
        ));
    }

    public function blockBranche(Request $request): RedirectResponse
    {
        try {
            $request->validate(['branche_id' => 'required|exists:branches,id']);
            $branche = Branches::findOrFail($request->branche_id);
            $branche->status = 2;  // Bloqué
            $branche->updated_at = now();
            $branche->save();

            return back()->with('success', 'Branche bloquée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du blocage : '.$e->getMessage());
        }
    }

    public function unblockBranche(Request $request): RedirectResponse
    {
        try {
            $request->validate(['branche_id' => 'required|exists:branches,id']);
            $branche = Branches::findOrFail($request->branche_id);
            $branche->status = 1;  // Bloqué
            $branche->updated_at = now();
            $branche->save();

            return back()->with('success', 'Branche débloquée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du déblocage : '.$e->getMessage());
        }
    }

    public function deleteBranche(Request $request): RedirectResponse
    {
        try {
            $request->validate(['branche_id' => 'required|exists:branches,id']);
            $branche = Branches::findOrFail($request->branche_id);
            $branche->status = 0;  // Bloqué
            $branche->updated_at = now();
            $branche->save();

            return back()->with('success', 'Branche supprimée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : '.$e->getMessage());
        }
    }

    public function restoreBranche(Request $request): RedirectResponse
    {
        try {
            $request->validate(['branche_id' => 'required|exists:branches,id']);
            $branche = Branches::findOrFail($request->branche_id);
            $branche->status = 1;  // Bloqué
            $branche->updated_at = now();
            $branche->save();

            return back()->with('success', 'Branche restaurée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la  restauration : '.$e->getMessage());
        }
    }

    public function permaneneDeleteBranche(Request $request): RedirectResponse
    {
        try {
            $request->validate(['branche_id' => 'required|exists:branches,id']);
            $branche = Branches::findOrFail($request->branche_id);
            $branche->forceDelete();  // Bloqué

            return back()->with('success', 'Branche supprimée définitivement.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression définitive  : '.$e->getMessage());
        }
    }

    public function handleBranchAction(Request $request, string $action): RedirectResponse
    {
        try {
            $request->validate(['branche_id' => 'required|exists:branches,id']);
            $branch = Branches::findOrFail($request->branche_id);
            $q = $request->q ? '?q='.$request->q : '';

            switch ($action) {
                case 'block':
                    $branch->status = 2;
                    $message = 'Branche bloquée avec succès.';
                    break;
                case 'unblock':
                    $branch->status = 1;
                    $message = 'Branche débloquée avec succès.';
                    break;
                case 'delete':
                    $branch->status = 0;
                    $message = 'Branche supprimée avec succès.';
                    break;
                case 'restore':
                    $branch->status = 1;
                    $message = 'Branche restaurée avec succès.';
                    break;
                case 'erase':
                    $branch->forceDelete();
                    $message = 'Branche supprimée définitivement.';

                    return redirect()->to('/super_admin_search'.$q)->with('success', $message);
                default:
                    return back()->with('error', 'Action invalide.');
            }

            $branch->updated_at = now();
            $branch->save();

            return redirect()->to('/super_admin_search'.$q)->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : '.$e->getMessage());
        }
    }

    public function createBranches(Request $request): RedirectResponse
    {
        $request->validate([
            'branche_name' => 'required|string|max:255|min:2|unique:branches,branche_name',
        ], [
            'branche_name.required' => 'Le nom de la branche est obligatoire.',
            'branche_name.string' => 'Le nom de la branche doit être une chaîne de caractères.',
            'branche_name.max' => 'Le nom de la branche doit contenir au maximum 255 caractères.',
            'branche_name.min' => 'Le nom de la branche doit contenir au moins 2 caractères.',
            'branche_name.unique' => 'Cette branche existe déjà.',
        ]);
        try {
            // Création de l'utilisateur avec mot de passe hashé
            Branches::create([
                'branche_name' => $request->branche_name,
                'status' => 1,
            ]);

            return redirect()->route('super_admin_branches')
                ->with('success', 'Brance créé avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création : '.$e->getMessage()])
                ->withInput();
        }
    }

    public function updateBranches(Request $request): RedirectResponse
    {
        // Récupérer l'ID depuis le formulaire (champ caché)
        $id = $request->input('branche_id');

        // Validation - inclure l'ID dans la règle unique pour exclure l'enregistrement actuel
        $request->validate([
            'branche_name' => 'required|string|max:255|min:2|unique:branches,branche_name,'.$id,
        ], [
            'branche_name.required' => 'Le nom de la branche est obligatoire.',
            'branche_name.string' => 'Le nom de la branche doit être une chaîne de caractères.',
            'branche_name.max' => 'Le nom de la branche doit contenir au maximum 255 caractères.',
            'branche_name.min' => 'Le nom de la branche doit contenir au moins 2 caractères.',
            'branche_name.unique' => 'Cette branche existe déjà.',
        ]);

        try {
            // Récupérer la branche avec l'ID du formulaire
            $branche = Branches::findOrFail($id);

            // Mise à jour
            $branche->update([
                'branche_name' => $request->branche_name,
            ]);

            return redirect()->route('super_admin_branches')
                ->with('success', 'Branche modifiée avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la modification : '.$e->getMessage()])
                ->withInput();
        }
    }

    public function editFromSearch(Request $request, string $id): View|RedirectResponse
    {
        try {
            $branche = Branches::findOrFail($id);
            $q = $request->query('q', '');

            return view('super_admin.edit-branche-search', [
                'branche' => $branche,
                'q' => $q,
            ]);
        } catch (\Exception $e) {
            return redirect()->to('/super_admin_search')
                ->with('error', 'Branche non trouvée.'.$e->getMessage());
        }
    }

    public function updateFromSearch(Request $request): RedirectResponse
    {
        $id = $request->input('branche_id');
        $q = $request->input('q', '');

        $request->validate([
            'branche_name' => 'required|string|max:255|min:2|unique:branches,branche_name,'.$id,
        ], [
            'branche_name.required' => 'Le nom de la branche est obligatoire.',
            'branche_name.string' => 'Le nom de la branche doit être une chaîne de caractères.',
            'branche_name.max' => 'Le nom de la branche doit contenir au maximum 255 caractères.',
            'branche_name.min' => 'Le nom de la branche doit contenir au moins 2 caractères.',
            'branche_name.unique' => 'Cette branche \":input\" existe déjà.',
        ]);

        try {
            $branche = Branches::findOrFail($id);
            $branche->update(['branche_name' => $request->branche_name]);

            if (! empty($q)) {
                return redirect()->to('/super_admin_search?q='.$q)
                    ->with('success', 'Branche modifiée avec succès !');
            }

            return redirect()->to('/super_admin_search')
                ->with('success', 'Branche modifiée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : '.$e->getMessage())
                ->withInput();
        }
    }

    /*-----------------------
     *-----------------------
     *-----------------------
     *-----------------------
     * ADMINS
     *-----------------------
     *-----------------------
     *-----------------------
     *-----------------------*/

    public function adminsBranches(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $adminsExists = User::where('role', 2)->exists();
        $branches = Branches::whereIn('status', [1, 2])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admins.admins_branches', compact(
            'currentDate',
            'currentTime',
            'branches',
            'adminsExists'
        ));
    }

    public function adminsBranchesActive(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $adminsExists = User::where('role', 2)->exists();

        $branches = Branches::where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admins.admins_branches_active', compact(
            'currentDate',
            'currentTime',
            'branches',
            'adminsExists'
        ));
    }

    public function adminsBranchesDeleted(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $adminsExists = User::where('role', 2)->exists();

        $branches = Branches::where('status', 2)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admins.admins_branches_trash', compact(
            'currentDate',
            'currentTime',
            'branches',
            'adminsExists'
        ));
    }

    public function adminsDeleteBranche(Request $request): RedirectResponse
    {
        try {
            $request->validate(['branche_id' => 'required|exists:branches,id']);
            $branche = Branches::findOrFail($request->branche_id);
            $branche->status = 2;
            $branche->updated_at = now();
            $branche->save();

            return back()->with('success', 'Branche supprimée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : '.$e->getMessage());
        }
    }

    public function adminsRestoreBranche(Request $request): RedirectResponse
    {
        try {
            $request->validate(['branche_id' => 'required|exists:branches,id']);
            $branche = Branches::findOrFail($request->branche_id);
            $branche->status = 1;
            $branche->updated_at = now();
            $branche->save();

            return back()->with('success', 'Branche restaurée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la  restauration : '.$e->getMessage());
        }
    }

    public function adminsFakePermanentDeleteBranche(Request $request): RedirectResponse
    {
        try {
            $request->validate(['branche_id' => 'required|exists:branches,id']);
            $branche = Branches::findOrFail($request->branche_id);
            $branche->status = 0;  // Bloqué
            $branche->updated_at = now();
            $branche->save();

            return back()->with('success', 'Branche supprimée définitivement avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression définitive : '.$e->getMessage());
        }
    }

    public function adminshandleBranchAction(Request $request, string $action): RedirectResponse
    {
        try {
            $request->validate(['branche_id' => 'required|exists:branches,id']);
            $branch = Branches::findOrFail($request->branche_id);
            $q = $request->q ? '?q='.$request->q : '';

            switch ($action) {
                case 'delete':
                    $branch->status = 2;
                    $message = 'Branche supprimée avec succès.';
                    break;
                case 'restore':
                    $branch->status = 1;
                    $message = 'Branche restaurée avec succès.';
                    break;
                case 'fake_erase':
                    $branch->status = 0;
                    $message = 'Branche supprimée définitivement.';

                    return redirect()->to('/admins_search'.$q)->with('success', $message);
                default:
                    return back()->with('error', 'Action invalide.');
            }

            $branch->updated_at = now();
            $branch->save();

            return redirect()->to('/admins_search'.$q)->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : '.$e->getMessage());
        }
    }

    public function adminsCreateBranches(Request $request): RedirectResponse
    {
        $request->validate([
            'branche_name' => 'required|string|max:255|min:2|unique:branches,branche_name',
        ], [
            'branche_name.required' => 'Le nom de la branche est obligatoire.',
            'branche_name.string' => 'Le nom de la branche doit être une chaîne de caractères.',
            'branche_name.max' => 'Le nom de la branche doit contenir au maximum 255 caractères.',
            'branche_name.min' => 'Le nom de la branche doit contenir au moins 2 caractères.',
            'branche_name.unique' => 'Cette branche existe déjà.',
        ]);
        try {
            // Création de l'utilisateur avec mot de passe hashé
            Branches::create([
                'branche_name' => $request->branche_name,
                'status' => 1,
            ]);

            return redirect()->route('admins_branches')
                ->with('success', 'Brance créé avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création : '.$e->getMessage()])
                ->withInput();
        }
    }

    public function adminsUpdateBranches(Request $request): RedirectResponse
    {
        // Récupérer l'ID depuis le formulaire (champ caché)
        $id = $request->input('branche_id');

        // Validation - inclure l'ID dans la règle unique pour exclure l'enregistrement actuel
        $request->validate([
            'branche_name' => 'required|string|max:255|min:2|unique:branches,branche_name,'.$id,
        ], [
            'branche_name.required' => 'Le nom de la branche est obligatoire.',
            'branche_name.string' => 'Le nom de la branche doit être une chaîne de caractères.',
            'branche_name.max' => 'Le nom de la branche doit contenir au maximum 255 caractères.',
            'branche_name.min' => 'Le nom de la branche doit contenir au moins 2 caractères.',
            'branche_name.unique' => 'Cette branche existe déjà.',
        ]);

        try {
            // Récupérer la branche avec l'ID du formulaire
            $branche = Branches::findOrFail($id);

            // Mise à jour
            $branche->update([
                'branche_name' => $request->branche_name,
            ]);

            return redirect()->route('admins_branches')
                ->with('success', 'Branche modifiée avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la modification : '.$e->getMessage()])
                ->withInput();
        }
    }

    public function adminsEditFromSearch(Request $request, string $id): View|RedirectResponse
    {
        try {
            $branche = Branches::findOrFail($id);
            $q = $request->query('q', '');

            return view('admins.edit-branche-search', [
                'branche' => $branche,
                'q' => $q,
            ]);
        } catch (\Exception $e) {
            return redirect()->to('/super_admin_search')
                ->with('error', 'Branche non trouvée.'.$e->getMessage());
        }
    }

    public function adminsUpdateFromSearch(Request $request): RedirectResponse
    {
        $id = $request->input('branche_id');
        $q = $request->input('q', '');

        $request->validate([
            'branche_name' => 'required|string|max:255|min:2|unique:branches,branche_name,'.$id,
        ], [
            'branche_name.required' => 'Le nom de la branche est obligatoire.',
            'branche_name.string' => 'Le nom de la branche doit être une chaîne de caractères.',
            'branche_name.max' => 'Le nom de la branche doit contenir au maximum 255 caractères.',
            'branche_name.min' => 'Le nom de la branche doit contenir au moins 2 caractères.',
            'branche_name.unique' => 'Cette branche \":input\" existe déjà.',
        ]);

        try {
            $branche = Branches::findOrFail($id);
            $branche->update(['branche_name' => $request->branche_name]);

            if (! empty($q)) {
                return redirect()->to('/admins_search?q='.$q)
                    ->with('success', 'Branche modifiée avec succès !');
            }

            return redirect()->to('/admins_search')
                ->with('success', 'Branche modifiée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : '.$e->getMessage())
                ->withInput();
        }
    }

    /*-----------------------
     *-----------------------
     *-----------------------
     *-----------------------
     *utilisateur/Reader
     *-----------------------
     *-----------------------
     *-----------------------
     *-----------------------*/

    public function readersBranchesActive(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $readersExists = User::where('role', 3)->exists();
        $branches = Branches::where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('readers.readers_branches_active', compact(
            'currentDate',
            'currentTime',
            'branches',
            'readersExists'
        ));
    }
}
