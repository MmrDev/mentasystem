<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeBalanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from_account_id' => null,
            'to_account_id' => null,
            'good_id' => null,
            'amount' => null,
            'revoked' => null,
            'author' => null,
            'uuid' => null,
            'reverse' => null,
            'extraValue' => null,
            'goodExtraValue' => null,
            'parent_id' => null,
            'application_id' => null
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
