<?php

namespace App\Http\Requests\WorkOrder;

use App\Http\Requests\BaseRequest;

class StoreWorkOrderRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'assessment_type' => 'required|string|in:PSB',

            'customer' => 'required|array',
            'customer.customer_name' => 'required|string',
            'customer.address' => 'required|string',
            'customer.pppoe_secret' => 'required|string',
            'customer.branch_id' => 'required|string',
            'customer.branch_name' => 'required|string',
            'customer.regional_name' => 'required|string',

            'technical_data' => 'required|array',
            'technical_data.area_id' => 'required|string',
            'technical_data.area_name' => 'required|string',
            'technical_data.area_code' => 'required|string',
            'technical_data.area_primary_id' => 'nullable',
            'technical_data.area_primary_name' => 'nullable',
            'technical_data.area_primary_code' => 'nullable',
            'technical_data.splitter_id' => 'required|integer',
            'technical_data.spliter' => 'required|string',
            'technical_data.customer_port' => 'required|integer',

            'work_order' => 'required|array',
            'work_order.wo_id' => 'required|string',
            'work_order.work_order_date' => 'required|date',
            'work_order.start_work_order' => 'required|date',
            'work_order.end_work_order' => 'required|date|after:work_order.start_work_order',
            'work_order.duration_minute' => 'required|integer',
            'work_order.detail' => 'required|array',
            'work_order.detail.team_id' => 'required|string',
            'work_order.detail.team_name' => 'required|string',
            'work_order.detail.sales_distance' => 'required|integer',
            'work_order.detail.technician_distance' => 'required|integer',
            'work_order.detail.dropcore' => 'required|integer',
            'work_order.detail.cable_status' => 'required|string|in:baru,bekas',
            'work_order.detail.cable_history_id' => 'nullable|integer',
            'work_order.detail.cable_detail_history_id' => 'nullable|integer',

            'pic' => 'required|array|min:1',
            'pic.*.role' => 'required|string',
            'pic.*.nip' => 'required|string',
            'pic.*.nick_name' => 'required|string',
            'pic.*.full_name' => 'required|string',
            'pic.*.order' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $orders = collect(request()->input('pic', []))
                        ->pluck('order');

                    if ($orders->duplicates()->isNotEmpty()) {
                        $fail('The order value must be unique across the entire request, even in different arrays.');
                    }
                }
            ],

            'step' => 'required|array|min:1',
            'step.*.step_id' => 'required|string',
            'step.*.step_code' => 'required|string',
            'step.*.step_name' => 'required|string',
            'step.*.order_number' => 'required|integer',
            'step.*.submitted_at' => 'required|date',
            'step.*.data_values' => 'required|array|min:1',
            'step.*.data_values.*.data_code' => 'required|string',
            'step.*.data_values.*.data_name' => 'required|string',
            'step.*.data_values.*.type_data' => 'required|string|in:image,datetime,date,text,number',
            'step.*.data_values.*.value' => 'required',
        ];
    }

    public function getErpExtraData()
    {
        $requiredFields = [
            'assessment_type'
        ];

        $data = $this->all();
        return array_diff_key($data, array_flip($requiredFields));
    }

    public function getCustExtraData()
    {
        $requiredFields = [
            'customer_name',
            'address',
            'pppoe_secret',
            'branch_id',
            'branch_name',
            'regional_name'
        ];

        $data = $this->input('customer', []);
        return array_diff_key($data, array_flip($requiredFields));
    }
    public function getTechExtraData()
    {
        $requiredFields = [
            'area_id',
            'area_name',
            'area_code',
            'area_primary_id',
            'area_primary_name',
            'area_primary_code',
            'splitter_id',
            'spliter',
            'customer_port'
        ];

        $data = $this->input('technical_data', []);
        return array_diff_key($data, array_flip($requiredFields));
    }
    public function getPsbExtraData()
    {
        $requiredFields = [
            'team_id',
            'team_name',
            'sales_distance',
            'technician_distance',
            'dropcore',
            'regional_name',
            'cable_status',
            'cable_history_id',
            'cable_detail_history_id'
        ];

        $data = $this->input('work_order.detail', []);
        return array_diff_key($data, array_flip($requiredFields));
    }

    public function messages()
    {
        return [
            // Custom error messages dapat didefinisikan sesuai kebutuhan
        ];
    }
}
