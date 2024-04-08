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

    'accepted' => _i('The :attribute must be accepted'),
    'active_url' => _i('The :attribute is not a valid URL'),
    'after' => _i('The :attribute must be a date after :date'),
    'after_or_equal' => _i('The :attribute must be a date after or equal to :date'),
    'alpha' => _i('The :attribute may only contain letters'),
    'alpha_dash' => _i('The :attribute may only contain letters, numbers, dashes and underscores'),
    'alpha_num' => _i('The :attribute may only contain letters and numbers'),
    'array' => _i('The :attribute must be an array'),
    'before' => _i('The :attribute must be a date before :date'),
    'before_or_equal' => _i('The :attribute must be a date before or equal to :date'),
    'between' => [
        'numeric' => _i('The :attribute must be between :min and :max'),
        'file' => _i('The :attribute must be between :min and :max kilobytes'),
        'string' => _i('The :attribute must be between :min and :max characters'),
        'array' => _i('The :attribute must have between :min and :max items'),
    ],
    'boolean' => _i('The :attribute field must be true or false'),
    'confirmed' => _i('The :attribute confirmation does not match'),
    'date' => _i('The :attribute is not a valid date'),
    'date_equals' => _i('The :attribute must be a date equal to :date'),
    'date_format' => _i('The :attribute does not match the format :format'),
    'different' => _i('The :attribute and :other must be different'),
    'digits' => _i('The :attribute must be :digits digits'),
    'digits_between' => _i('The :attribute must be between :min and :max digits'),
    'dimensions' => _i('The :attribute has invalid image dimensions'),
    'distinct' => _i('The :attribute field has a duplicate value'),
    'email' => _i('The :attribute must be a valid email address'),
    'ends_with' => _i('The :attribute must end with one of the following: :values'),
    'exists' => _i('The selected :attribute is invalid'),
    'file' => _i('The :attribute must be a file'),
    'filled' => _i('The :attribute field must have a value'),
    'gt' => [
        'numeric' => _i('The :attribute must be greater than :value'),
        'file' => _i('The :attribute must be greater than :value kilobytes'),
        'string' => _i('The :attribute must be greater than :value characters'),
        'array' => _i('The :attribute must have more than :value items'),
    ],
    'gte' => [
        'numeric' => _i('The :attribute must be greater than or equal :value'),
        'file' => _i('The :attribute must be greater than or equal :value kilobytes'),
        'string' => _i('The :attribute must be greater than or equal :value characters'),
        'array' => _i('The :attribute must have :value items or more'),
    ],
    'image' => _i('The :attribute must be an image'),
    'in' => _i('The selected :attribute is invalid'),
    'in_array' => _i('The :attribute field does not exist in :other'),
    'integer' => _i('The :attribute must be an integer number'),
    'ip' => _i('The :attribute must be a valid IP address'),
    'ipv4' => _i('The :attribute must be a valid IPv4 address'),
    'ipv6' => _i('The :attribute must be a valid IPv6 address'),
    'json' => _i('The :attribute must be a valid JSON string'),
    'lt' => [
        'numeric' => _i('The :attribute must be less than :value'),
        'file' => _i('The :attribute must be less than :value kilobytes'),
        'string' => _i('The :attribute must be less than :value characters'),
        'array' => _i('The :attribute must have less than :value items'),
    ],
    'lte' => [
        'numeric' => _i('The :attribute must be less than or equal :value'),
        'file' => _i('The :attribute must be less than or equal :value kilobytes'),
        'string' => _i('The :attribute must be less than or equal :value characters'),
        'array' => _i('The :attribute must not have more than :value items'),
    ],
    'max' => [
        'numeric' => _i('The :attribute may not be greater than :max'),
        'file' => _i('The :attribute may not be greater than :max kilobytes'),
        'string' => _i('The :attribute may not be greater than :max characters'),
        'array' => _i('The :attribute may not have more than :max items'),
    ],
    'mimes' => _i('The :attribute must be a file of type: :values'),
    'mimetypes' => _i('The :attribute must be a file of type: :values'),
    'min' => [
        'numeric' => _i('The :attribute must be at least :min'),
        'file' => _i('The :attribute must be at least :min kilobytes'),
        'string' => _i('The :attribute must be at least :min characters'),
        'array' => _i('The :attribute must have at least :min items'),
    ],
    'not_in' => _i('The selected :attribute is invalid'),
    'not_regex' => _i('The :attribute format is invalid'),
    'numeric' => _i('The :attribute must be a number'),
    'password' => _i('The password is incorrect'),
    'present' => _i('The :attribute field must be present'),
    'regex' => _i('The :attribute format is invalid'),
    'required' => _i('The :attribute field is required'),
    'required_if' => _i('The :attribute field is required when :other is :value'),
    'required_unless' => _i('The :attribute field is required unless :other is in :values'),
    'required_with' => _i('The :attribute field is required when :values is present'),
    'required_with_all' => _i('The :attribute field is required when :values are present'),
    'required_without' => _i('The :attribute field is required when :values is not present'),
    'required_without_all' => _i('The :attribute field is required when none of :values are present'),
    'same' => _i('The :attribute and :other must match'),
    'size' => [
        'numeric' => _i('The :attribute must be :size'),
        'file' => _i('The :attribute must be :size kilobytes'),
        'string' => _i('The :attribute must be :size characters'),
        'array' => _i('The :attribute must contain :size items'),
    ],
    'starts_with' => _i('The :attribute must start with one of the following: :values'),
    'string' => _i('The :attribute must be a string'),
    'timezone' => _i('The :attribute must be a valid zone'),
    'unique' => _i('The :attribute has already been taken'),
    'uploaded' => _i('The :attribute failed to upload'),
    'url' => _i('The :attribute format is invalid'),
    'uuid' => _i('The :attribute must be a valid UUID'),

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
        'allocation_criteria' => [
            'required' => _i('You must select at least one assignment criteria')
        ],
        'bonus' => [
            'required_if' => _i('The bonus to be awarded field is mandatory'),
        ],
        'bonus_code' => [
            'required_if' => _i('The bonus code field is required'),
        ],
        'amount_type' => [
            'required_if' => _i('The type of amount field is required'),
        ],
        'min_deposit' => [
            'required_if' => _i('The minimum deposit field is required'),
        ],
        'limit' => [
            'required_if' => _i('The limit field is required'),
        ],
        'provider_type' => [
            'required_if' => _i('The type of provider field is required'),
        ],
        'multiplier' => [
            'required_if' => _i('The multiplier field is required'),
        ],
        'days' => [
            'required_if' => _i('The rollover expiration time field is required'),
        ],
        'include_deposit' => [
            'required_if' => _i('The field include deposit of rollovers is required'),
        ],
        'promo_code' => [
            'required_with' => _i('The field promo code is required'),
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

    'attributes' => [
        'username' => _i('Username'),
        'password' => _i('Password'),
        'email' => _i('Email'),
        'country' => _i('Country'),
        'timezone' => _i('Timezone'),
        'currency' => _i('Currency'),
        'dni' => _i('DNI'),
        'first_name' => _i('First name'),
        'last_name' => _i('Last name'),
        'phone' => _i('Phone'),
        'image' => _i('Image'),
        'section' => _i('Section'),
        'language' => _i('Language'),
        'title' => _i('Title'),
        'content' => _i('Content'),
        'name' => _i('Name'),
        'description' => _i('Description'),
        'points' => _i('Points'),
        'amount' => _i('Amount'),
        'transaction_type' => _i('Transaction type'),
        'convert' => _i('Convert'),
        'subject' => _i('Subject'),
        'year' => _i('Year'),
        'month' => _i('Month'),
        'allocation_criteria' => _i('Allocation criteria'),
        'bonus_code' => _i('Bonus code'),
        'complete_profile' => _i('Complete profile'),
        'complete_rollover' => _i('Complete rollover'),
        'bonus' => _i('Bonus'),
        'internal_name' => _i('Internal name'),
        'include_deposit' => _i('Include deposit'),
        'id' => _i('ID'),
        'wallet' => _i('Wallet ID'),
        'start_date' => _i('Start date'),
        'available_days_claim' => _i('Days available to claim the bonus'),
        'max_balance_convert' => _i('Amount that can be converted into real balance'),
        'currencies' => _i('Currencies')
    ],

];
