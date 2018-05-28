<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\Corporation;
use App\Http\Requests\WageTmpl8\MstrRequest;

class WageTemplatesController extends Controller
{
    protected $deptModel;
    protected $deductModel;
    protected $benfModel;
    protected $expModel;
    protected $tmplModel;

    public function __construct(Request $request)
    {
        $company = Corporation::findOrFail($request->corpID);
      
        $this->deptModel = new \App\Models\T\Depts;
        $this->deptModel->setConnection($company->database_name);

        $this->deductModel = new \App\Models\Py\DeductMstr;
        $this->deductModel->setConnection($company->database_name);

        $this->benfModel = new \App\Models\Py\BenfMstr;
        $this->benfModel->setConnection($company->database_name);

        $this->expModel = new \App\Models\Py\ExpMstr;
        $this->expModel->setConnection($company->database_name);

        $this->tmplModel = new \App\Models\WageTmpl8\Mstr;
        $this->tmplModel->setConnection($company->database_name);
    }

    public function index()
    {
        if(!\Auth::user()->checkAccessByIdForCorp(request()->corpID, 45, 'V')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        return view('wage-templates.index', [
        ]);
    }

    public function create()
    {
        if(!\Auth::user()->checkAccessByIdForCorp(request()->corpID, 45, 'A')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $deductItems = $this->deductModel->where('active', 1)
                        ->whereIn('type', [2,3,4])
                        ->orderBy('description', 'ASC')
                        ->get();

        $benfItems = $this->benfModel->where('active', 1)
                            ->whereIn('type', [1,2,3,4,9])
                            ->orderBy('description', 'ASC')
                            ->get();

        $expItems = $this->expModel->where('active', 1)
                            ->whereIn('type', [2,3,4])
                            ->orderBy('description', 'ASC')
                            ->get();

        $departments = $this->deptModel->orderBy('department', 'ASC')->get();

        return view('wage-templates.create', [
            'deductItems' => $deductItems,
            'benfItems' => $benfItems,
            'expItems' => $expItems,
            'departments' => $departments
        ]);
    }

    public function edit($id)
    {
        if(!\Auth::user()->checkAccessByIdForCorp(request()->corpID, 45, 'E')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $template = $this->tmplModel->findOrFail($id);

        $deductItems = $this->deductModel->where('active', 1)
                        ->whereIn('type', [2,3,4])
                        ->orderBy('description', 'ASC')
                        ->get();

        $benfItems = $this->benfModel->where('active', 1)
                            ->whereIn('type', [1,2,3,4,9])
                            ->orderBy('description', 'ASC')
                            ->get();

        $expItems = $this->expModel->where('active', 1)
                            ->whereIn('type', [2,3,4])
                            ->orderBy('description', 'ASC')
                            ->get();

        $departments = $this->deptModel->orderBy('department', 'ASC')->get();

        return view('wage-templates.edit', [
            'deductItems' => $deductItems,
            'benfItems' => $benfItems,
            'expItems' => $expItems,
            'departments' => $departments,
            'template' => $template
        ]);
    }

    public function store(MstrRequest $request)
    {
        if(!\Auth::user()->checkAccessByIdForCorp(request()->corpID, 45, 'A')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $template = $this->tmplModel->create($this->templParams());
        
        if(is_array(request()->details)) {
            foreach(request()->details as $type => $items) {
                $class = null;
                switch($type) {
                    case 'benf':
                        $class = 'benf_mstr';
                        break;
                    case 'exp':
                        $class = 'exp_mstr';
                        break;
                    case 'deduct':
                        $class = 'deduct_mstr';
                        break;
                }

                foreach($items as $key => $value) {
                    $template->details()->create([
                        'pay_db' => $class,
                        'ID' => $key
                    ]);
                }
            }
        }

        \Session::flash('success', "New Template {$template->code} has been created");

        return redirect(route('wage-templates.index', ['corpID' => request()->corpID]));
    }

    public function update(MstrRequest $request, $id)
    {
        if(!\Auth::user()->checkAccessByIdForCorp(request()->corpID, 45, 'E')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $template = $this->tmplModel->findOrFail($id);
        $template->update($this->templParams());

        $template->details()->delete();
        
        if(is_array(request()->details)) {
            foreach(request()->details as $type => $items) {
                $class = null;
                switch($type) {
                    case 'benf':
                        $class = \App\Models\Py\BenfMstr::class;
                        break;
                    case 'exp':
                        $class = \App\Models\Py\ExpMstr::class;
                        break;
                    case 'deduct':
                        $class = \App\Models\Py\DeductMstr::class;
                        break;
                }

                foreach($items as $key => $value) {
                    $template->details()->create([
                        'pay_db' => $class,
                        'ID' => $key
                    ]);
                }
            }
        }

        \Session::flash('success', "Template {$template->code} has been updated");

        return redirect(route('wage-templates.index', ['corpID' => request()->corpID]));
    }

    public function destroy($id)
    {
        if(!\Auth::user()->checkAccessByIdForCorp(request()->corpID, 45, 'D')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $template = $this->tmplModel->findOrFail($id);
        $template->delete();

        return response()->json([
            'success' => true
        ]);
    }

    private function templParams()
    {
        $params = request()->only([
            'code', 'base_rate', 'position', 'dept_id', 'notes', 'active',
            'entry_level'
        ]);

        $params['active'] = $params['active'] ?: 0;
        $params['entry_level'] = $params['entry_level'] ?: 0;

        return $params;
    }

}