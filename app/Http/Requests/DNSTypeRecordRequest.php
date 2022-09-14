<?php

namespace App\Http\Requests;

use App\Rules\DomainNameValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DNSTypeRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    { 
        return [
            "domain" => ["required", new DomainNameValidation],
            "nameserver" => ["required"],
            "type" => ["required", Rule::in( config('dnsutility.recordTypes') )],
        ];
    }
}
