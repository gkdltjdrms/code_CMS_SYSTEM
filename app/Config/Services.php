<?php

namespace Config;

use App\Models\DefaultModel; // DefaultModel의 네임스페이스 추가
use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */


    public static function solutionSettings($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('solutionSettings');
        }

        // DefaultModel을 사용하여 솔루션 이름 불러오기
        $defaultModel = new DefaultModel();
        $solutionName = $defaultModel->getSetting('solution_name') ?? '기본 솔루션';
        $company_info = $defaultModel->getSetting('company_info') ?? '';
        $business_number = $defaultModel->getSetting('business_number') ?? '';

        // stdClass 객체에 솔루션 이름 저장
        $settings = new \stdClass();
        $settings->solution_name = $solutionName;
        $settings->company_info = $company_info;
        $settings->business_number = $business_number;

        return $settings;
    }

}
