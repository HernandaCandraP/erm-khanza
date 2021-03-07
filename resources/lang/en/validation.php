<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Field :attribute harus disetujui.',
    'active_url' => 'Field :attribute bukan valid URL.',
    'after' => 'Field :attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => 'Field :attribute must be a date after or equal to :date.',
    'alpha' => 'Field :attribute hanya boleh berupa kata.',
    'alpha_dash' => 'Field :attribute hanya boleh berupa kata, nomor, and penghubung.',
    'alpha_num' => 'Field :attribute hanya boleh berupa kata and nomor.',
    'array' => 'Field :attribute harus berupa array.',
    'before' => 'Field :attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => 'Field :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'Field :attribute harus diantara :min and :max.',
        'file' => 'Field :attribute harus diantara :min and :max kilobytes.',
        'string' => 'Field :attribute harus diantara :min and :max characters.',
        'array' => 'Field :attribute harus diantara :min and :max items.',
    ],
    'boolean' => 'Field :attribute field harus berupa true atau false.',
    'confirmed' => 'Field :attribute konfirmasi tidak cocok.',
    'date' => 'Field :attribute bukan berupa tanggal valid.',
    'date_equals' => 'Field :attribute must be a date equal to :date.',
    'date_format' => 'Field :attribute tidak cocok dengan format :format.',
    'different' => 'Field :attribute dan :other harus berbeda.',
    'digits' => 'Field :attribute harus berupa :digits digits.',
    'digits_between' => 'Field :attribute harus diantara :min and :max digits.',
    'dimensions' => 'Field :attribute has invalid image dimensions.',
    'distinct' => 'Field :attribute field has a duplicate value.',
    'email' => 'Field :attribute harus berupa email yang valid.',
    'ends_with' => 'Field :attribute must end with one of the following: :values.',
    'exists' => 'The selected :attribute tidak valid.',
    'file' => 'Field :attribute harus berupa file.',
    'filled' => 'Field :attribute field must have a value.',
    'gt' => [
        'numeric' => 'Field :attribute must be greater than :value.',
        'file' => 'Field :attribute must be greater than :value kilobytes.',
        'string' => 'Field :attribute must be greater than :value characters.',
        'array' => 'Field :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'Field :attribute must be greater than or equal :value.',
        'file' => 'Field :attribute must be greater than or equal :value kilobytes.',
        'string' => 'Field :attribute must be greater than or equal :value characters.',
        'array' => 'Field :attribute must have :value items or more.',
    ],
    'image' => 'Field :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'Field :attribute field does not exist in :other.',
    'integer' => 'Field :attribute must be an integer.',
    'ip' => 'Field :attribute must be a valid IP address.',
    'ipv4' => 'Field :attribute must be a valid IPv4 address.',
    'ipv6' => 'Field :attribute must be a valid IPv6 address.',
    'json' => 'Field :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'Field :attribute must be less than :value.',
        'file' => 'Field :attribute must be less than :value kilobytes.',
        'string' => 'Field :attribute must be less than :value characters.',
        'array' => 'Field :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'Field :attribute must be less than or equal :value.',
        'file' => 'Field :attribute must be less than or equal :value kilobytes.',
        'string' => 'Field :attribute must be less than or equal :value characters.',
        'array' => 'Field :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'Field :attribute tidak boleh lebih dari :max.',
        'file' => 'Field :attribute tidak boleh lebih dari :max kilobytes.',
        'string' => 'Field :attribute tidak boleh lebih dari :max characters.',
        'array' => 'Field :attribute tidak boleh lebih dari :max items.',
    ],
    'mimes' => 'Field :attribute must be a file of type: :values.',
    'mimetypes' => 'Field :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'Field :attribute harus lebih dari :min.',
        'file' => 'Field :attribute harus lebih dari :min kilobytes.',
        'string' => 'Field :attribute harus lebih dari :min characters.',
        'array' => 'Field :attribute harus lebih dari :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'Field :attribute format is invalid.',
    'numeric' => 'Field :attribute harus berupa angka.',
    'password' => 'The password tidak sesuai.',
    'present' => 'Field :attribute field must be present.',
    'regex' => 'Field :attribute format tidak valid.',
    'required' => 'Field :attribute harus diisi.',
    'required_if' => 'Field :attribute field is required when :other is :value.',
    'required_unless' => 'Field :attribute field is required unless :other is in :values.',
    'required_with' => 'Field :attribute field is required when :values is present.',
    'required_with_all' => 'Field :attribute field is required when :values are present.',
    'required_without' => 'Field :attribute field is required when :values is not present.',
    'required_without_all' => 'Field :attribute field is required when none of :values are present.',
    'same' => 'Field :attribute and :other must match.',
    'size' => [
        'numeric' => 'Field :attribute must be :size.',
        'file' => 'Field :attribute must be :size kilobytes.',
        'string' => 'Field :attribute must be :size characters.',
        'array' => 'Field :attribute must contain :size items.',
    ],
    'starts_with' => 'Field :attribute must start with one of the following: :values.',
    'string' => 'Field :attribute must be a string.',
    'timezone' => 'Field :attribute must be a valid zone.',
    'unique' => 'Field :attribute sudah ada.',
    'uploaded' => 'Field :attribute failed to upload.',
    'url' => 'Field :attribute formattidak valid.',
    'uuid' => 'Field :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
