<?php
/*
 * Copyright (C) 2022 Renan Carvs
 */


/*
 * Show the value formated to user in browser
 */
function getBoolColumn($value)
{
    if ($value) {
        return '<span class="badge badge-success">' . __('general.yes') . '</span>';
    } else {
        return '<span class="badge badge-danger">' . __('general.no') . '</span>';
    }
}

/*
 * Return default date format for the current locale
 * @param bool $hasHours
 * @return string
 */
function getLocaleDateFormat($hasHours = false)
{
    $locale = app()->getLocale();

    switch ($locale) {
        case ('en' || 'en_US' || 'us'):
            return 'm/d/Y' . " " . ($hasHours ? 'h:i A' : '');
        default:
            return 'd/m/Y' . " " . ($hasHours ? 'H:i' : '');
    }
    return $date_format;
}

/*
 * Return a database date in a human readable format
 * @param Carbon|string $date
 * @param bool $htmlTooltip
 * @return string
 */
function getDateHumanFormat(\Carbon\Carbon|string $date, $htmlTooltip = false)
{
    if (is_string($date)) {
        $date = new \Carbon\Carbon($date);
    }

    if ($htmlTooltip) {
        return '<p data-toggle="tooltip" data-placement="bottom" title="' . $date->translatedFormat('j F Y H:i:s') . '">' . $date->diffForHumans() . '</p>';
    } else {
        return $date->diffForHumans();
    }
}

/*
 * Return email with link to open in new tab
 * @param string $email
 * @return string
 */
function getLinkForEmail(string $email)
{
    return '<a href="mailto:' . $email . '" target="_blank">' . $email . '</a>';
}

/*
 * Return user role name translated
 * @param string $role
 * @return string
 */
function getUserRoleString(string $role)
{
    return trans('general.roles_list.' . $role);
}

/*
 * Return only numbers from string
 */
function onlyDigits($val)
{
    return preg_replace('/\D+/', '', $val);
}

/*
 * Get number before a percent addition
 */
function getNumberBeforePercentage($number, $percentage)
{
    $beforePercentage = 100 + $percentage;
    $onePercent = $number / $beforePercentage;
    return $onePercent * 100;
}

function getTextForOrderCount($count)
{
    if ($count < 10) {
        return __('New!');
    } elseif ($count < 20) {
        return __(':quantity completed orders', ['quantity' => '+10']);
    } elseif ($count < 50) {
        return __(':quantity completed orders', ['quantity' => '+20']);
    } elseif ($count < 100) {
        return __(':quantity completed orders', ['quantity' => '+50']);
    } elseif ($count < 1000) {
        return __(':quantity completed orders', ['quantity' => '+100']);
    } else {
        return __(':quantity completed orders', ['quantity' => '+1000']);
    }
}

function getMediaColumn($mediaModel, $mediaCollectionName = 'default', $optionClass = '', $mediaThumbnail = 'icon')
{
    $optionClass = $optionClass == '' ? ' rounded ' : $optionClass;

    if ($mediaModel && $mediaModel->hasMedia($mediaCollectionName)) {

        return "<img class='" . $optionClass . "' style='width:50px;height:50px;object-fit: contain;' src='" . $mediaModel->getFirstMediaUrl($mediaCollectionName, $mediaThumbnail) . "' alt='" . $mediaModel->getFirstMedia($mediaCollectionName)->name . "'>";
    } else {
        return "<img class='" . $optionClass . "' style='width:50px;height:50px;object-fit: contain;' src='" . asset('img/avatardefault.png') . "' alt='image_default'>";
    }
}


function getPrice($price = 0)
{
    if (setting('currency_right', false) != false) {
        return number_format((float)$price, 2, '.', '') . "<span>" . getCurrencySymbolByCurrencyCode(setting('currency', 'USD')) . "</span>";
    } else {
        return "<span>" . getCurrencySymbolByCurrencyCode(setting('currency', 'USD')) . "</span>" . number_format((float)$price, 2, '.', ' ');
    }
}


function getCurrencySymbolByCurrencyCode($currencyCode)
{
    foreach (getAvailableCurrencies() as $key => $value) {
        if ($value['code'] == $currencyCode) {
            return $value['symbol'];
        }
    }
    return '';
}

/*
 * Return available payment gateways
 */
function getAvailablePaymentGatewaysArray()
{
    $array = array();
    if (setting('enable_stripe')) {
        $array['stripe'] = 'Stripe';
    }
    if (setting('enable_paypal')) {
        $array['paypal'] = 'PayPal';
    }
    if (setting('enable_mercado_pago')) {
        $array['mercadopago'] = 'Mercado Pago';
    }
    return $array;
}

/*
 * Return the order status options in array
 */
function getAvailableOrderStatusArray()
{
    return [
        'waiting' => trans('general.order_status_list.waiting'),
        'pending' => trans('general.order_status_list.pending'),
        'accepted' => trans('general.order_status_list.accepted'),
        'rejected' => trans('general.order_status_list.rejected'),
        'collected' => trans('general.order_status_list.collected'),
        'delivered' => trans('general.order_status_list.delivered'),
        'completed' => trans('general.order_status_list.completed'),
        'cancelled' => trans('general.order_status_list.cancelled'),
    ];
}



/*
 * Return the payment status options in array
 */
function getAvailablePaymentStatusArray()
{
    return [
        'pending' => trans('general.order_status_list.pending'),
        'paid' => trans('general.order_status_list.paid'),
        'cancelled' => trans('general.order_status_list.cancelled'),
    ];
}

/*
 * Get available languages in system
 */
function getAvailableLanguagesArray()
{
    return [
        'de' => __('German'),
        'en' => __('English'),
        'es' => __('Spanish'),
        'fr' => __('French'),
        'it' => __('Italian'),
        'pt' => __('Portuguese'),
        'pt-br' => __('Brazilian Portuguese'),
        'tr' => __('Turkish'),
        'ru' => __('Russian'),
        'ar' => __('Arabic'),
        'zh' => __('Chinese'),
        'ja' => __('Japanese'),
        'ko' => __('Korean'),
        'nl' => __('Dutch'),
        'sv' => __('Swedish'),
        'pl' => __('Polish'),
        'hi' => __('Hindi'),
        'id' => __('Indonesian'),
        'bn' => __('Bengali'),
    ];
}

/*
 * Get all available timezones to use choose in system
 */
function getAvailableTimezonesArray()
{
    $list = DateTimeZone::listIdentifiers();
    $return = [];
    foreach ($list as $key => $value) {
        $return[$value] = $value;
    }
    return $return;
}

/*
 * All currencies
 */
function getAvailableCurrencies()
{
    return array(
        array(
            'code' => 'ALL',
            'countryname' => 'Albania',
            'name' => 'Albanian lek',
            'symbol' => 'L'
        ),

        array(
            'code' => 'AFN',
            'countryname' => 'Afghanistan',
            'name' => 'Afghanistan Afghani',
            'symbol' => '&#1547;'
        ),

        array(
            'code' => 'ARS',
            'countryname' => 'Argentina',
            'name' => 'Argentine Peso',
            'symbol' => '&#36;'
        ),

        array(
            'code' => 'AWG',
            'countryname' => 'Aruba',
            'name' => 'Aruban florin',
            'symbol' => '&#402;'
        ),

        array(
            'code' => 'AUD',
            'countryname' => 'Australia',
            'name' => 'Australian Dollar',
            'symbol' => '&#65;&#36;'
        ),

        array(
            'code' => 'AZN',
            'countryname' => 'Azerbaijan',
            'name' => 'Azerbaijani Manat',
            'symbol' => '&#8380;'
        ),

        array(
            'code' => 'BSD',
            'countryname' => 'The Bahamas',
            'name' => 'Bahamas Dollar',
            'symbol' => '&#66;&#36;'
        ),

        array(
            'code' => 'BBD',
            'countryname' => 'Barbados',
            'name' => 'Barbados Dollar',
            'symbol' => '&#66;&#100;&#115;&#36;'
        ),

        array(
            'code' => 'BDT',
            'countryname' => 'People\'s Republic of Bangladesh',
            'name' => 'Bangladeshi taka',
            'symbol' => '&#2547;'
        ),

        array(
            'code' => 'BYN',
            'countryname' => 'Belarus',
            'name' => 'Belarus Ruble',
            'symbol' => '&#66;&#114;'
        ),

        array(
            'code' => 'BZD',
            'countryname' => 'Belize',
            'name' => 'Belize Dollar',
            'symbol' => '&#66;&#90;&#36;'
        ),

        array(
            'code' => 'BMD',
            'countryname' => 'British Overseas Territory of Bermuda',
            'name' => 'Bermudian Dollar',
            'symbol' => '&#66;&#68;&#36;'
        ),

        array(
            'code' => 'BOP',
            'countryname' => 'Bolivia',
            'name' => 'Boliviano',
            'symbol' => '&#66;&#115;'
        ),

        array(
            'code' => 'BAM',
            'countryname' => 'Bosnia and Herzegovina',
            'name' => 'Bosnia-Herzegovina Convertible Marka',
            'symbol' => '&#75;&#77;'
        ),

        array(
            'code' => 'BWP',
            'countryname' => 'Botswana',
            'name' => 'Botswana pula',
            'symbol' => '&#80;'
        ),

        array(
            'code' => 'BGN',
            'countryname' => 'Bulgaria',
            'name' => 'Bulgarian lev',
            'symbol' => '&#1083;&#1074;'
        ),

        array(
            'code' => 'BRL',
            'countryname' => 'Brazil',
            'name' => 'Brazilian real',
            'symbol' => '&#82;&#36;'
        ),

        array(
            'code' => 'BND',
            'countryname' => 'Sultanate of Brunei',
            'name' => 'Brunei dollar',
            'symbol' => '&#66;&#36;'
        ),

        array(
            'code' => 'KHR',
            'countryname' => 'Cambodia',
            'name' => 'Cambodian riel',
            'symbol' => '&#6107;'
        ),

        array(
            'code' => 'CAD',
            'countryname' => 'Canada',
            'name' => 'Canadian dollar',
            'symbol' => '&#67;&#36;'
        ),

        array(
            'code' => 'KYD',
            'countryname' => 'Cayman Islands',
            'name' => 'Cayman Islands dollar',
            'symbol' => '&#36;'
        ),

        array(
            'code' => 'CLP',
            'countryname' => 'Chile',
            'name' => 'Chilean peso',
            'symbol' => '&#36;'
        ),

        array(
            'code' => 'CNY',
            'countryname' => 'China',
            'name' => 'Chinese Yuan Renminbi',
            'symbol' => '&#165;'
        ),

        array(
            'code' => 'COP',
            'countryname' => 'Colombia',
            'name' => 'Colombian peso',
            'symbol' => '&#36;'
        ),

        array(
            'code' => 'CRC',
            'countryname' => 'Costa Rica',
            'name' => 'Costa Rican colón',
            'symbol' => '&#8353;'
        ),

        array(
            'code' => 'HRK',
            'countryname' => 'Croatia',
            'name' => 'Croatian kuna',
            'symbol' => '&#107;&#110;'
        ),

        array(
            'code' => 'CUP',
            'countryname' => 'Cuba',
            'name' => 'Cuban peso',
            'symbol' => '&#8369;'
        ),

        array(
            'code' => 'CZK',
            'countryname' => 'Czech Republic',
            'name' => 'Czech koruna',
            'symbol' => '&#75;&#269;'
        ),

        array(
            'code' => 'DKK',
            'countryname' => 'Denmark, Greenland, and the Faroe Islands',
            'name' => 'Danish krone',
            'symbol' => '&#107;&#114;'
        ),

        array(
            'code' => 'DOP',
            'countryname' => 'Dominican Republic',
            'name' => 'Dominican peso',
            'symbol' => '&#82;&#68;&#36;'
        ),

        array(
            'code' => 'XCD',
            'countryname' => 'Antigua and Barbuda, Commonwealth of Dominica, Grenada, Montserrat, St. Kitts and Nevis, Saint Lucia and St. Vincent and the Grenadines',
            'name' => 'Eastern Caribbean dollar',
            'symbol' => '&#36;'
        ),

        array(
            'code' => 'EGP',
            'countryname' => 'Egypt',
            'name' => 'Egyptian pound',
            'symbol' => '&#163;'
        ),

        array(
            'code' => 'SVC',
            'countryname' => 'El Salvador',
            'name' => 'Salvadoran colón',
            'symbol' => '&#36;'
        ),

        array(
            'code' => 'EEK',
            'countryname' => 'Estonia',
            'name' => 'Estonian kroon',
            'symbol' => '&#75;&#114;'
        ),

        array(
            'code' => 'EUR',
            'countryname' => 'European Union, Italy, Belgium, Bulgaria, Croatia, Cyprus, Czechia, Denmark, Estonia, Finland, France, Germany,
                    Greece, Hungary, Ireland, Latvia, Lithuania, Luxembourg, Malta, Netherlands,
                    Portugal, Romania, Slovakia, Slovenia, Spain, Sweden',
            'name' => 'Euro',
            'symbol' => '&#8364;'
        ),

        array(
            'code' => 'PLN',
            'countryname' => 'Poland',
            'name' => 'Polish złoty',
            'symbol' => 'zł'
        ),

        array(
            'code' => 'FKP',
            'countryname' => 'Falkland Islands',
            'name' => 'Falkland Islands (Malvinas) Pound',
            'symbol' => '&#70;&#75;&#163;'
        ),

        array(
            'code' => 'FJD',
            'countryname' => 'Fiji',
            'name' => 'Fijian dollar',
            'symbol' => '&#70;&#74;&#36;'
        ),

        array(
            'code' => 'GHC',
            'countryname' => 'Ghana',
            'name' => 'Ghanaian cedi',
            'symbol' => '&#71;&#72;&#162;'
        ),

        array(
            'code' => 'GIP',
            'countryname' => 'Gibraltar',
            'name' => 'Gibraltar pound',
            'symbol' => '&#163;'
        ),

        array(
            'code' => 'GTQ',
            'countryname' => 'Guatemala',
            'name' => 'Guatemalan quetzal',
            'symbol' => '&#81;'
        ),

        array(
            'code' => 'GGP',
            'countryname' => 'Guernsey',
            'name' => 'Guernsey pound',
            'symbol' => '&#81;'
        ),

        array(
            'code' => 'GYD',
            'countryname' => 'Guyana',
            'name' => 'Guyanese dollar',
            'symbol' => '&#71;&#89;&#36;'
        ),

        array(
            'code' => 'HNL',
            'countryname' => 'Honduras',
            'name' => 'Honduran lempira',
            'symbol' => '&#76;'
        ),

        array(
            'code' => 'HKD',
            'countryname' => 'Hong Kong',
            'name' => 'Hong Kong dollar',
            'symbol' => '&#72;&#75;&#36;'
        ),

        array(
            'code' => 'HUF',
            'countryname' => 'Hungary',
            'name' => 'Hungarian forint',
            'symbol' => '&#70;&#116;'
        ),

        array(
            'code' => 'ISK',
            'countryname' => 'Iceland',
            'name' => 'Icelandic króna',
            'symbol' => '&#237;&#107;&#114;'
        ),

        array(
            'code' => 'INR',
            'countryname' => 'India',
            'name' => 'Indian rupee',
            'symbol' => '&#8377;'
        ),

        array(
            'code' => 'IDR',
            'countryname' => 'Indonesia',
            'name' => 'Indonesian rupiah',
            'symbol' => '&#82;&#112;'
        ),

        array(
            'code' => 'IRR',
            'countryname' => 'Iran',
            'name' => 'Iranian rial',
            'symbol' => '&#65020;'
        ),

        array(
            'code' => 'IMP',
            'countryname' => 'Isle of Man',
            'name' => 'Manx pound',
            'symbol' => '&#163;'
        ),

        array(
            'code' => 'ILS',
            'countryname' => 'Israel, Palestinian territories of the West Bank and the Gaza Strip',
            'name' => 'Israeli Shekel',
            'symbol' => '&#8362;'
        ),

        array(
            'code' => 'JMD',
            'countryname' => 'Jamaica',
            'name' => 'Jamaican dollar',
            'symbol' => '&#74;&#36;'
        ),

        array(
            'code' => 'JPY',
            'countryname' => 'Japan',
            'name' => 'Japanese yen',
            'symbol' => '&#165;'
        ),

        array(
            'code' => 'JEP',
            'countryname' => 'Jersey',
            'name' => 'Jersey pound',
            'symbol' => '&#163;'
        ),

        array(
            'code' => 'KZT',
            'countryname' => 'Kazakhstan',
            'name' => 'Kazakhstani tenge',
            'symbol' => '&#8376;'
        ),

        array(
            'code' => 'KPW',
            'countryname' => 'North Korea',
            'name' => 'North Korean won',
            'symbol' => '&#8361;'
        ),

        array(
            'code' => 'KPW',
            'countryname' => 'South Korea',
            'name' => 'South Korean won',
            'symbol' => '&#8361;'
        ),

        array(
            'code' => 'KGS',
            'countryname' => 'Kyrgyz Republic',
            'name' => 'Kyrgyzstani som',
            'symbol' => '&#1083;&#1074;'
        ),

        array(
            'code' => 'LAK',
            'countryname' => 'Laos',
            'name' => 'Lao kip',
            'symbol' => '&#8365;'
        ),

        array(
            'code' => 'LAK',
            'countryname' => 'Laos',
            'name' => 'Latvian lats',
            'symbol' => '&#8364;'
        ),

        array(
            'code' => 'LVL',
            'countryname' => 'Laos',
            'name' => 'Latvian lats',
            'symbol' => '&#8364;'
        ),

        array(
            'code' => 'LBP',
            'countryname' => 'Lebanon',
            'name' => 'Lebanese pound',
            'symbol' => '&#76;&#163;'
        ),

        array(
            'code' => 'LRD',
            'countryname' => 'Liberia',
            'name' => 'Liberian dollar',
            'symbol' => '&#76;&#68;&#36;'
        ),

        array(
            'code' => 'LTL',
            'countryname' => 'Lithuania',
            'name' => 'Lithuanian litas',
            'symbol' => '&#8364;'
        ),

        array(
            'code' => 'MKD',
            'countryname' => 'North Macedonia',
            'name' => 'Macedonian denar',
            'symbol' => '&#1076;&#1077;&#1085;'
        ),

        array(
            'code' => 'MYR',
            'countryname' => 'Malaysia',
            'name' => 'Malaysian ringgit',
            'symbol' => '&#82;&#77;'
        ),

        array(
            'code' => 'MUR',
            'countryname' => 'Mauritius',
            'name' => 'Mauritian rupee',
            'symbol' => '&#82;&#115;'
        ),

        array(
            'code' => 'MXN',
            'countryname' => 'Mexico',
            'name' => 'Mexican peso',
            'symbol' => '&#77;&#101;&#120;&#36;'
        ),

        array(
            'code' => 'MNT',
            'countryname' => 'Mongolia',
            'name' => 'Mongolian tögrög',
            'symbol' => '&#8366;'
        ),


        array(
            'code' => 'MZN',
            'countryname' => 'Mozambique',
            'name' => 'Mozambican metical',
            'symbol' => '&#77;&#84;'
        ),

        array(
            'code' => 'NAD',
            'countryname' => 'Namibia',
            'name' => 'Namibian dollar',
            'symbol' => '&#78;&#36;'
        ),

        array(
            'code' => 'NPR',
            'countryname' => 'Federal Democratic Republic of Nepal',
            'name' => 'Nepalese rupee',
            'symbol' => '&#82;&#115;&#46;'
        ),

        array(
            'code' => 'ANG',
            'countryname' => 'Curaçao and Sint Maarten',
            'name' => 'Netherlands Antillean guilder',
            'symbol' => '&#402;'
        ),

        array(
            'code' => 'NZD',
            'countryname' => 'New Zealand, the Cook Islands, Niue, the Ross Dependency, Tokelau, the Pitcairn Islands',
            'name' => 'New Zealand dollar',
            'symbol' => '&#36;'
        ),


        array(
            'code' => 'NIO',
            'countryname' => 'Nicaragua',
            'name' => 'Nicaraguan córdoba',
            'symbol' => '&#67;&#36;'
        ),

        array(
            'code' => 'NGN',
            'countryname' => 'Nigeria',
            'name' => 'Nigerian naira',
            'symbol' => '&#8358;'
        ),

        array(
            'code' => 'NOK',
            'countryname' => 'Norway and its dependent territories',
            'name' => 'Norwegian krone',
            'symbol' => '&#107;&#114;'
        ),

        array(
            'code' => 'OMR',
            'countryname' => 'Oman',
            'name' => 'Omani rial',
            'symbol' => '&#65020;'
        ),

        array(
            'code' => 'PKR',
            'countryname' => 'Pakistan',
            'name' => 'Pakistani rupee',
            'symbol' => '&#82;&#115;'
        ),

        array(
            'code' => 'PAB',
            'countryname' => 'Panama',
            'name' => 'Panamanian balboa',
            'symbol' => '&#66;&#47;&#46;'
        ),

        array(
            'code' => 'PYG',
            'countryname' => 'Paraguay',
            'name' => 'Paraguayan Guaraní',
            'symbol' => '&#8370;'
        ),

        array(
            'code' => 'PEN',
            'countryname' => 'Peru',
            'name' => 'Sol',
            'symbol' => '&#83;&#47;&#46;'
        ),

        array(
            'code' => 'PHP',
            'countryname' => 'Philippines',
            'name' => 'Philippine peso',
            'symbol' => '&#8369;'
        ),

        array(
            'code' => 'PLN',
            'countryname' => 'Poland',
            'name' => 'Polish złoty',
            'symbol' => '&#122;&#322;'
        ),

        array(
            'code' => 'QAR',
            'countryname' => 'State of Qatar',
            'name' => 'Qatari Riyal',
            'symbol' => '&#65020;'
        ),

        array(
            'code' => 'RON',
            'countryname' => 'Romania',
            'name' => 'Romanian leu (Leu românesc)',
            'symbol' => '&#76;'
        ),

        array(
            'code' => 'RUB',
            'countryname' => 'Russian Federation, Abkhazia and South Ossetia, Donetsk and Luhansk',
            'name' => 'Russian ruble',
            'symbol' => '&#8381;'
        ),


        array(
            'code' => 'SHP',
            'countryname' => 'Saint Helena, Ascension and Tristan da Cunha',
            'name' => 'Saint Helena pound',
            'symbol' => '&#163;'
        ),

        array(
            'code' => 'SAR',
            'countryname' => 'Saudi Arabia',
            'name' => 'Saudi riyal',
            'symbol' => '&#65020;'
        ),

        array(
            'code' => 'RSD',
            'countryname' => 'Serbia',
            'name' => 'Serbian dinar',
            'symbol' => '&#100;&#105;&#110;'
        ),

        array(
            'code' => 'SCR',
            'countryname' => 'Seychelles',
            'name' => 'Seychellois rupee',
            'symbol' => '&#82;&#115;'
        ),

        array(
            'code' => 'SGD',
            'countryname' => 'Singapore',
            'name' => 'Singapore dollar',
            'symbol' => '&#83;&#36;'
        ),

        array(
            'code' => 'SBD',
            'countryname' => 'Solomon Islands',
            'name' => 'Solomon Islands dollar',
            'symbol' => '&#83;&#73;&#36;'
        ),

        array(
            'code' => 'SOS',
            'countryname' => 'Somalia',
            'name' => 'Somali shilling',
            'symbol' => '&#83;&#104;&#46;&#83;&#111;'
        ),

        array(
            'code' => 'ZAR',
            'countryname' => 'South Africa',
            'name' => 'South African rand',
            'symbol' => '&#82;'
        ),

        array(
            'code' => 'LKR',
            'countryname' => 'Sri Lanka',
            'name' => 'Sri Lankan rupee',
            'symbol' => '&#82;&#115;'
        ),


        array(
            'code' => 'SEK',
            'countryname' => 'Sweden',
            'name' => 'Swedish krona',
            'symbol' => '&#107;&#114;'
        ),


        array(
            'code' => 'CHF',
            'countryname' => 'Switzerland',
            'name' => 'Swiss franc',
            'symbol' => '&#67;&#72;&#102;'
        ),

        array(
            'code' => 'SRD',
            'countryname' => 'Suriname',
            'name' => 'Suriname Dollar',
            'symbol' => '&#83;&#114;&#36;'
        ),

        array(
            'code' => 'SYP',
            'countryname' => 'Syria',
            'name' => 'Syrian pound',
            'symbol' => '&#163;&#83;'
        ),

        array(
            'code' => 'TWD',
            'countryname' => 'Taiwan',
            'name' => 'New Taiwan dollar',
            'symbol' => '&#78;&#84;&#36;'
        ),


        array(
            'code' => 'THB',
            'countryname' => 'Thailand',
            'name' => 'Thai baht',
            'symbol' => '&#3647;'
        ),


        array(
            'code' => 'TTD',
            'countryname' => 'Trinidad and Tobago',
            'name' => 'Trinidad and Tobago dollar',
            'symbol' => '&#84;&#84;&#36;'
        ),


        array(
            'code' => 'TRY',
            'countryname' => 'Turkey, Turkish Republic of Northern Cyprus',
            'name' => 'Turkey Lira',
            'symbol' => '&#8378;'
        ),

        array(
            'code' => 'TVD',
            'countryname' => 'Tuvalu',
            'name' => 'Tuvaluan dollar',
            'symbol' => '&#84;&#86;&#36;'
        ),

        array(
            'code' => 'UAH',
            'countryname' => 'Ukraine',
            'name' => 'Ukrainian hryvnia',
            'symbol' => '&#8372;'
        ),


        array(
            'code' => 'GBP',
            'countryname' => 'United Kingdom, Jersey, Guernsey, the Isle of Man, Gibraltar, South Georgia and the South Sandwich Islands, the British Antarctic Territory, and Tristan da Cunha',
            'name' => 'Pound sterling',
            'symbol' => '&#163;'
        ),


        array(
            'code' => 'UGX',
            'countryname' => 'Uganda',
            'name' => 'Ugandan shilling',
            'symbol' => '&#85;&#83;&#104;'
        ),


        array(
            'code' => 'USD',
            'countryname' => 'United States',
            'name' => 'United States dollar',
            'symbol' => '&#36;'
        ),

        array(
            'code' => 'UYU',
            'countryname' => 'Uruguayan',
            'name' => 'Peso Uruguayolar',
            'symbol' => '&#36;&#85;'
        ),

        array(
            'code' => 'UZS',
            'countryname' => 'Uzbekistan',
            'name' => 'Uzbekistani soʻm',
            'symbol' => '&#1083;&#1074;'
        ),


        array(
            'code' => 'VEF',
            'countryname' => 'Venezuela',
            'name' => 'Venezuelan bolívar',
            'symbol' => '&#66;&#115;'
        ),


        array(
            'code' => 'VND',
            'countryname' => 'Vietnam',
            'name' => 'Vietnamese dong (Đồng)',
            'symbol' => '&#8363;'
        ),

        array(
            'code' => 'VND',
            'countryname' => 'Yemen',
            'name' => 'Yemeni rial',
            'symbol' => '&#65020;'
        ),

        array(
            'code' => 'ZWD',
            'countryname' => 'Zimbabwe',
            'name' => 'Zimbabwean dollar',
            'symbol' => '&#90;&#36;'
        ),
    );
}

function getUtf8ConvertedStringFromHtmlEntities($str)
{
    return preg_replace_callback("/(&#[0-9]+;)/", function ($m) {
        return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
    }, $str);
}
