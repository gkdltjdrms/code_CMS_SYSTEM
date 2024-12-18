<?php

namespace App\Controllers\Admin;

use App\Models\DefaultModel;
use App\Controllers\BaseController;

class Settings extends BaseController
{
    public function index()
    {
        $defaultModel = new DefaultModel();
        $data = [
            'solution_name' => $defaultModel->getSetting('solution_name'),
            'company_info' => $defaultModel->getSetting('company_info'),
            'business_number' => $defaultModel->getSetting('business_number'),
        ];

        return $this->render('admin/settings', $data);
    }

    public function update()
    {
        $defaultModel = new DefaultModel();

        $defaultModel->setSetting('solution_name', $this->request->getPost('solution_name'));
        $defaultModel->setSetting('company_info', $this->request->getPost('company_info'));
        $defaultModel->setSetting('business_number', $this->request->getPost('business_number'));

        return redirect()->to('/admin/settings')->with('message', '설정이 업데이트되었습니다.');
    }
}
