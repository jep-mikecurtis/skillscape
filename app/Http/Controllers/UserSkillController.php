<?php

namespace App\Http\Controllers;

use App\Models\UserSkill;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class UserSkillController extends Controller
{
    use AuthorizesRequests;

    public function destroy(UserSkill $userSkill): RedirectResponse
    {
        $this->authorize('delete', $userSkill);

        $userSkill->delete();

        return redirect()->route('skills.index')->with('success', 'Skill untracked successfully!');
    }
}
