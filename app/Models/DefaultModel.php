<?php

namespace App\Models;

use CodeIgniter\Model;

class DefaultModel extends Model
{
    protected $table = 'default';
    protected $primaryKey = 'id';
    protected $allowedFields = ['key', 'value'];

    // 설정값 가져오기
    public function getSetting($key)
    {
        return $this->where('key', $key)->first()['value'] ?? null;
    }

    // 설정값 업데이트
    public function setSetting($key, $value)
    {
        $existing = $this->where('key', $key)->first();

        if ($existing) {
            return $this->update($existing['id'], ['value' => $value]);
        } else {
            return $this->insert(['key' => $key, 'value' => $value]);
        }
    }
}
