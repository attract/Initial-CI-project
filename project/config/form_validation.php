<?php
$config = array(
    /* Логин админ для БЭКЕНДА*/
    'login_backend' => array(
        array(
            'field' => 'email_user',
            'label' => 'Логин',
            'type' => 'input[type=text]',
            'data'=>array('class'=>'login_user, my-class','title'=>'Укажите логин'),
            'rules' => 'required|xss_clean'
        ),
        array(
            'field' => 'password_user',
            'label' => 'Пароль',
            'type' => 'input[type=password]',
            'data'=>array('class'=>'pass_user, my-class','title'=>'Укажите пароль'),
            'rules' => 'required|xss_clean'
        ),
    ) ,
    'login' => array(
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|xss_clean|valid_email'
        ),
        array(
            'field' => 'password',
            'label' => 'Пароль',
            'rules' => 'trim|required|xss_clean|min_length[6]|htmlspecialchars'
        ),
    ) ,
    'password_recovery' => array(
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|xss_clean|valid_email|_email_in_db|_activate_account'
        ),
    ) ,
    'password_change' => array(
        array(
            'field' => 'password',
            'label' => 'Пароль',
            'rules' => 'trim|required|xss_clean|min_length[6]|htmlspecialchars'
        ),
        array(
            'field' => 'password_confirm',
            'label' => 'Повторить пароль',
            'rules' => 'trim|required|xss_clean|min_length[6]|htmlspecialchars|matches[password]'
        ),
    ) ,
    'registration' => array(
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|xss_clean|valid_email|_unique_email'
        ),
        array(
            'field' => 'password',
            'label' => 'Пароль',
            'rules' => 'trim|required|xss_clean|min_length[6]|htmlspecialchars'
        ),
        array(
            'field' => 'password_confirm',
            'label' => 'Повторить пароль',
            'rules' => 'trim|required|xss_clean|min_length[6]|htmlspecialchars|matches[password]'
        ),
        array(
            'field' => 'first_name',
            'label' => 'Имя',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Фамилия',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'name_company',
            'label' => 'Название компании',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'country_id',
            'label' => 'Страна',
            'rules' => 'trim|required|is_natural_no_zero'
        ),
        array(
            'field' => 'region_id',
            'label' => 'Область',
            'rules' => 'trim|required|is_natural'
        ),
        array(
            'field' => 'district_id',
            'label' => 'Район',
            'rules' => 'trim|required|is_natural_no_zero'
        ),
        array(
            'field' => 'city_id',
            'label' => 'Город',
            'rules' => 'trim|required|is_natural_no_zero'
        ),
        array(
            'field' => 'zip_code',
            'label' => 'Почтовый индекс',
            'rules' => 'trim|required|numeric'
        ),
        array(
            'field' => 'address',
            'label' => 'Адресс',
            'rules' => 'trim|required'
        ),
    ) ,
    'contact_us'=> array(
        array(
            'field' => 'name',
            'label' => 'Имя',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|xss_clean|valid_email'
        ),
        array(
            'field' => 'phone',
            'label' => 'Телефон',
            'rules' => 'trim|_tel'
        ),
        array(
            'field' => 'message',
            'label' => 'Сообщение',
            'rules' => 'trim|required'
        ),
    ),
    'procurement'=> array(
        array(
            'field' => 'posting_end_date',
            'label' => 'дата окончания',
            'rules' => 'trim|required|_check_datepicker'
        ),
        array(
            'field' => 'headline',
            'label' => 'Заголовок',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'quantity',
            'label' => 'количество',
            'rules' => 'trim|required|numeric'
        ),
        array(
            'field' => 'unit_id',
            'label' => 'Единицы',
            'rules' => 'trim|required|is_natural_no_zero'
        ),
        array(
            'field' => 'frequency_id',
            'label' => 'Частота',
            'rules' => 'trim|required|is_natural_no_zero'
        ),
        array(
            'field' => 'description_procurement',
            'label' => 'описание',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'date_completed',
            'label' => 'дата окончания',
            'rules' => 'trim|required|_check_datepicker'
        ),
        array(
            'field' => 'user_id_responsible',
            'label' => 'Ответственный',
            'rules' => 'trim'
        ),
        array(
            'field' => 'is_fgs_procurement',
            'label' => 'Требовать FGS',
            'rules' => 'trim|required|_check_is'
        ),
        array(
            'field' => 'is_private_procurement',
            'label' => 'Приватный',
            'rules' => 'trim|_check_is'
        ),
        array(
            'field' => 'is_take_proposal',
            'label' => 'Принимать предложения',
            'rules' => 'trim|_check_is'
        ),
    ),
    'responsible_person' => array(
        array(
            'field' => 'responsible_first_name',
            'label' => 'Имя',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'responsible_last_name',
            'label' => 'Фамилия',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'responsible_email_address',
            'label' => 'Email',
            'rules' => 'trim|required|xss_clean|valid_email'
        ),
        array(
            'field' => 'responsible_phone',
            'label' => 'Phone',
            'rules' => 'trim|required|_tel'
        ),
    ),
    'company_add_edit' => array(
        array(
            'field' => 'zip_code',
            'label' => 'zip code',
            'rules' => 'trim|required|min_length[3]|max_length[15]|numeric|'
        ),
        array(
            'field' => 'city',
            'label' => 'city',
            'rules' => 'trim|required|is_natural_no_zero'
        ),
        array(
            'field' => 'authority_company',
            'label' => 'Address',
            'rules' => 'trim|xss_clean|htmlspecialchars'
        ),

        array(
            'field' => 'currency_id',
            'label' => 'Currency',
            'rules' => 'trim|is_natural_no_zero'
        ),
        array(
            'field' => 'description_company',
            'label' => 'Description',
            'rules' => 'trim|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'name_company',
            'label' => 'Company name',
            'rules' => 'trim|xss_clean|required|htmlspecialchars'
        ),
        array(
            'field' => 'address',
            'label' => 'address',
            'rules' => 'trim|xss_clean|required|htmlspecialchars'
        ),
        array(
            'field' => 'phone_company',
            'label' => 'phone company',
            'rules' => 'trim|_tel'
        ),
        array(
            'field' => 'service_area_company',
            'label' => 'Service area',
            'rules' => 'trim|xss_clean||numeric|greater_than[-0.1]|less_than[1000]'
        ),
        array(
            'field' => 'vat',
            'label' => 'VAT',
            'rules' => 'trim|xss_clean|numeric|greater_than[-0.1]|less_than[50]'
        ),
        array(
            'field' => 'tax',
            'label' => 'TAX',
            'rules' => 'trim|xss_clean|numeric|greater_than[-0.1]|less_than[50]'
        ),
        array(
            'field' => 'spoken_lang_company',
            'label' => 'Spoken language',
            'rules' => 'trim|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'terms_company',
            'label' => 'Terms & Conditions',
            'rules' => 'trim|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'why_choose_us_company',
            'label' => 'Why choose us',
            'rules' => 'trim|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'minimum_order_company',
            'label' => 'Minimum order/deal',
            'rules' => 'trim|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'reg_number_company',
            'label' => 'registration number',
            'rules' => 'trim|xss_clean|htmlspecialchars|min_length[5]|max_length[15]|numeric'
        ),
        array(
            'field' => 'email_company',
            'label' => 'PayPal email',
            'rules' => 'trim|xss_clean|valid_email'
        ),
        array(
            'field' => 'in_business_since_company',
            'label' => 'In business since',
            'rules' => 'trim|xss_clean'
        )
    ),
    'user_settings' => array(
        array(
            'field' => 'password',
            'label' => 'Пароль',
            'rules' => 'trim|xss_clean|min_length[6]|htmlspecialchars'
        ),
        array(
            'field' => 'password_confirm',
            'label' => 'Повторить пароль',
            'rules' => 'trim|xss_clean|min_length[6]|htmlspecialchars|matches[password]'
        ),
        array(
            'field' => 'first_name',
            'label' => 'Имя',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Фамилия',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'phone_user',
            'label' => 'Телефон',
            'rules' => 'trim|_tel'
        ),
        array(
            'field' => 'notification_email',
            'label' => 'Е-mail уведомлений',
            'rules' => 'trim|required|xss_clean|valid_email'
        ),
    ),
    'offer'=> array(
        array(
            'field' => 'message_offer',
            'label' => 'Message',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'price_offer',
            'label' => 'Email',
            'rules' => 'trim|required|_float_num'
        ),
    ),
    /* create invoice */
    'invoice'=> array(
        array(
            'field' => 'invoice_number',
            'label' => 'Invoice number',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'payment_day',
            'label' => 'Payment day',
            'rules' => 'trim|required|_check_datepicker'
        ),
    ),
    'feedback'=> array(
        array(
            'field' => 'comment_feedback',
            'label' => 'Comment',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
    ),
    /* ADD USER BACKEND */
    'add_user_backend' => array(
        array('field' => 'id_user',  'type' => 'input[type=hidden]' ),
        array('field' => 'first_name',  'label' => 'First name*',  'type' => 'input[type=text]',
            'data'=>array('title'=>'Enter name'),
            'rules' => 'required|xss_clean|htmlspecialchars' ),
        array('field' => 'last_name',  'label' => 'Last name*',  'type' => 'input[type=text]',
            'data'=>array('title'=>'Enter surname'),
            'rules' => 'required|xss_clean|htmlspecialchars' ),
        array('field' => 'password',  'label' => 'Password',  'type' => 'input[type=password]',
            'data'=>array('title'=>'Enter pass'),
            'rules' => 'trim|xss_clean|required|min_length[6]|htmlspecialchars' ),
        array('field' => 'pass2_user',  'label' => 'Repeat password',  'type' => 'input[type=password]',
            'data'=>array('title'=>'Enter pass'),
            'rules' => 'trim|xss_clean|required|htmlspecialchars|matches[password]' ),
        array('field' => 'email',  'label' => 'Email*',  'type' => 'input[type=text]',
            'data'=>array('title'=>'Enter email'),
            'rules' => 'trim|required|xss_clean|valid_email|is_unique_only_one[id_user,user.email]' ),
        array('field' => 'company',  'label' => 'Company*',  'type' => 'select',
            'data'=>array('title'=>'Select company','style'=>'width:300px;'),
            'key_with_array' => 'aCompany_list',
            'rules' => 'required|xss_clean|_noZero' ),
        array('field' => 'is_admin_company',  'label' => 'Have admin right',  'type' => 'input[type=checkbox]',
            'data'=>array('title'=>'Admin right'),
            'rules' => 'xss_clean',
            'value'=>1
        )
    ),
    /* EDIT USER BACKEND */
    'edit_user_backend' => array(

        array('field' => 'id_user',  'type' => 'input[type=hidden]' ),
        array('field' => 'first_name',  'label' => 'First name*',  'type' => 'input[type=text]',
            'data'=>array('title'=>'Enter name'),
            'rules' => 'required|xss_clean|htmlspecialchars' ),
        array('field' => 'last_name',  'label' => 'Last name*',  'type' => 'input[type=text]',
            'data'=>array('title'=>'Enter surname'),
            'rules' => 'required|xss_clean|htmlspecialchars' ),
        array('field' => 'password',  'label' => 'Password',  'type' => 'input[type=password]',
            'data'=>array('title'=>'Enter pass'),
            'rules' => 'trim|xss_clean|min_length[6]|htmlspecialchars|_check_confirm_field[pass2_user]' ),
        array('field' => 'pass2_user',  'label' => 'Repeat password',  'type' => 'input[type=password]',
            'data'=>array('title'=>'Enter pass'),
            'rules' => 'trim|xss_clean|htmlspecialchars|matches[password]' ),
        array('field' => 'email',  'label' => 'Email*',  'type' => 'input[type=text]',
            'data'=>array('title'=>'Enter email'),
            'rules' => 'trim|required|xss_clean|valid_email|is_unique_only_one[id_user,user.email]' )
    ),
    /* ADD USER BACKEND */
    'add_admin_backend' => array(
        array('field' => 'id_admin',  'type' => 'input[type=hidden]' ),
        array('field' => 'login',  'label' => 'Login*',  'type' => 'input[type=text]',
            'data'=>array('title'=>'Enter login'),
            'rules' => 'required|xss_clean|htmlspecialchars'),
        array('field' => 'password',  'label' => 'Password*',  'type' => 'input[type=password]',
            'data'=>array('title'=>'Enter pass'),
            'rules' => 'trim|xss_clean|required|min_length[6]|htmlspecialchars' ),
        array('field' => 'pass2_admin',  'label' => 'Repeat password*',  'type' => 'input[type=password]',
            'data'=>array('title'=>'Enter pass'),
            'rules' => 'trim|xss_clean|required|htmlspecialchars|matches[password]' ),
        array('field' => 'email',  'label' => 'Email*',  'type' => 'input[type=text]',
            'data'=>array('title'=>'Enter email'),
            'rules' => 'trim|required|xss_clean|valid_email|is_unique_only_one[id_admin,admin.email]' )
    ),
    /* EDIT ADMIN BACKEND */
    'edit_admin_backend' => array(

        array('field' => 'id_admin',  'type' => 'input[type=hidden]' ),
        array('field' => 'login',  'label' => 'Login*',  'type' => 'input[type=text]',
            'data'=>array('title'=>'Enter login'),
            'rules' => 'required|xss_clean|htmlspecialchars'),

        array('field' => 'password',  'label' => 'Password',  'type' => 'input[type=password]',
            'data'=>array('title'=>'Enter pass'),
            'rules' => 'trim|xss_clean|min_length[6]|htmlspecialchars|_check_confirm_field[pass2_admin]' ),

        array('field' => 'pass2_admin',  'label' => 'Repeat password',  'type' => 'input[type=password]',
            'data'=>array('title'=>'Enter pass'),
            'rules' => 'trim|xss_clean|htmlspecialchars|matches[password]' ),

        array('field' => 'email',  'label' => 'Email*',  'type' => 'input[type=text]',
            'data'=>array('title'=>'Enter email'),
            'rules' => 'trim|required|xss_clean|valid_email|is_unique_only_one[id_admin,admin.email]' )
    ),
    /* EDIT CREDIT COAST LIST*/
    'edit_credit_coast' => array(
        array(
            'field' => 'count_credit_coast',
            'label' => 'Credit coast',
            'rules' => 'trim|required|xss_clean|is_natural'
        ),
        array(
            'field' => 'price_credit_coast',
            'label' => 'Credit coast',
            'rules' => 'trim|required|xss_clean|numeric|greater_than[-1]'
        ),
        array(
            'field' => 'save_percent_credit_coast',
            'label' => 'Credit coast',
            'rules' => 'trim|required|xss_clean|numeric|greater_than[-1]'
        )

    ),
    /* EDIT CREDIT COAST LIST*/
    'edit_credit_interval' => array(
        array(
            'field' => 'min_price',
            'label' => 'Min price',
            'rules' => 'trim|required|xss_clean|is_natural'
        ),
        array(
            'field' => 'max_price',
            'label' => 'Max price',
            'rules' => 'trim|required|xss_clean|numeric|greater_than[0]'
        ),
        array(
            'field' => 'count_credit',
            'label' => 'Count credits',
            'rules' => 'trim|required|xss_clean|numeric|greater_than[0]'
        )
    ),
    'unit_backend'=> array(
        array(
            'field' => 'unit_1',
            'label' => 'Metric unit',
            'rules' => 'trim|required|htmlspecialchars'
        ),
        array(
            'field' => 'unit_2',
            'label' => 'Imperial unit',
            'rules' => 'trim|required|htmlspecialchars'
        ),
        array(
            'field' => 'coefficient',
            'label' => 'Coefficient',
            'rules' => 'trim|required|_float_num'
        )
    ),
    'frequency_backend'=> array(
        array(
            'field' => 'frequency',
            'label' => 'Frequency',
            'rules' => 'trim|required|htmlspecialchars'
        ),
    ),
    'invite_new_member' => array(
        array(
            'field' => 'invite_first_name',
            'label' => 'Имя',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'invite_last_name',
            'label' => 'Фамилия',
            'rules' => 'trim|required|xss_clean|htmlspecialchars'
        ),
        array(
            'field' => 'invite_email',
            'label' => 'Email',
            'rules' => 'trim|required|xss_clean|valid_email'
        ),
        array(
            'field' => 'invite_phone_user',
            'label' => 'Телефон',
            'rules' => 'trim|_tel'
        ),
    ),
);

?>
