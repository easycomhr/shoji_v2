<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemParameter extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'description', 'data_type'];

    // === METHODS ===

    /**
     * Lấy giá trị tham số theo key
     */
    public static function getValue($key, $default = null)
    {
        $parameter = static::where('key', $key)->first();

        if (!$parameter) {
            return $default;
        }

        return static::castValue($parameter->value, $parameter->data_type);
    }

    /**
     * Set giá trị tham số
     */
    public static function setValue($key, $value, $description = null, $dataType = 'string')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description,
                'data_type' => $dataType
            ]
        );
    }

    /**
     * Cast giá trị theo kiểu dữ liệu
     */
    private static function castValue($value, $dataType)
    {
        switch ($dataType) {
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    // === SCOPES ===

    public function scopeByType($query, $dataType)
    {
        return $query->where('data_type', $dataType);
    }
}
