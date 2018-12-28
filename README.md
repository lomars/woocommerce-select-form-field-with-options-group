Add select field with optgroup new field type to WooCommerce form fields

The options should be a multidimensional array like:

<code><pre>
$options = [
    __("Option group Label 1") => [
        'option-1' 	=> __("Label 1"),
		    'option-2' 	=> __("Label 2"),
    ],
    __("Option group Label 2") => [
        'option-3' 	=> __("Label 3"),
        'option-4' 	=> __("Label 4"),
        'option-5' 	=> __("Label 5"),
        'option-6' 	=> __("Label 6", $domain),
    ],
    __("Option group Label 3")  => [
        'option-7' 	=> __("Label 7"),
        'option-8' 	=> __("Region 8"),
        'option-9' 	=> __("Label 9"),
    ],
];
</pre></code>
