<div id="sidebar">
    <ul>
        <li class="submenu <?if ($LEFT_MENU == "Users") echo " open";?>">
            <a href="#">
                <i class="icon icon-user"></i> <span>Users</span></a>
            <ul>
                <li <?if ($SUB_LEFT_MENU == "Add user") echo 'class="active"';?>><a
                    href="<?php echo site_url('backend/user/add_user')?>" >Add user</a></li>
                <li <?if ($SUB_LEFT_MENU == "All users") echo 'class="active"';?>><a
                    href="<?php echo site_url('backend/user/list_user')?>" >All users</a></li>
            </ul>
        </li>
        <li class="submenu <?if ($LEFT_MENU == "Administrators") echo " open";?>">
            <a href="#">
                <i class="icon icon-user"></i>
                <span>
                    Administrators
                </span>
            </a>
            <ul>
                <li <?if ($SUB_LEFT_MENU == "Add administrator") echo 'class="active"';?>>
                    <a href="<?php echo site_url('backend/admin/add')?>" >
                        Add administrator
                    </a>
                </li>
                <li <?if ($SUB_LEFT_MENU == "All administrators") echo 'class="active"';?>>
                    <a href="<?php echo site_url('backend/admin/list_admins')?>" >
                        All administrators
                    </a>
                </li>
            </ul>
        </li>
        <li class="submenu <?if ($LEFT_MENU == "Language") echo " open";?>">
            <a href="#">
                <i class="icon icon-bullhorn"></i> <span>Languages</span></a>
            <ul>
                <li <?if ($SUB_LEFT_MENU == "View languages") echo 'class="active"';?>><a
                    href="<?php echo site_url('backend/lang/')?>" >View languages</a></li>
                <li <?if ($SUB_LEFT_MENU == "View translation") echo 'class="active"';?>><a
                    href="<?php echo site_url('backend/lang/translation/')?>" >View translation</a></li>

            </ul>
        </li>

        <li class="submenu <?if ($LEFT_MENU == "Static pages") echo " open";?>">
            <a href="#">
                <i class="icon icon-file"></i>
                <span>
                    Static pages
                </span>
            </a>
            <ul>
                <li <?if ($SUB_LEFT_MENU == "View pages") echo 'class="active"';?>>
                    <a href="<?php echo site_url('backend/static_page/')?>" >
                        View pages
                    </a>
                </li>
                <li <?if ($SUB_LEFT_MENU == "Add page") echo 'class="active"';?>>
                    <a href="<?php echo site_url('backend/static_page/add')?>" >
                        Add page
                    </a>
                </li>

            </ul>
        </li>

        <li class="<?if ($LEFT_MENU == "Transaction history") echo " open";?>">
            <a href="<?php echo site_url('backend/transaction')?>">
                <i class="icon icon-random"></i>
                <span>
                    Transaction history
                </span>
            </a>
        </li>
        <li class="<?if ($LEFT_MENU == "Invoice") echo "open";?>">
            <a href="<?php echo site_url('backend/invoice')?>">
                <i class="icon icon-edit"></i>
                <span>
                    Invoice
                </span>
            </a>
        </li>

        <li class="submenu <?if ($LEFT_MENU == "Setting") echo " open";?>">
            <a href="#">
                <i class="icon icon-wrench"></i>
                <span>
                    Setting
                </span>
            </a>
            <ul>
                <li <?if ($SUB_LEFT_MENU == "Categories") echo 'class="active"';?>>
                    <a href="<?php echo site_url('backend/setting/categories')?>" >
                        Categories
                    </a>
                </li>
                <li <?if ($SUB_LEFT_MENU == "Payment page") echo 'class="active"';?>>
                    <a href="<?php echo site_url('backend/setting/payment_page')?>" >
                        Payment page
                    </a>
                </li>
                <li <?if ($SUB_LEFT_MENU == "Unit") echo 'class="active"';?>>
                    <a href="<?php echo site_url('backend/unit')?>" >
                        Unit
                    </a>
                </li>
                <li <?if ($SUB_LEFT_MENU == "Frequency") echo 'class="active"';?>>
                    <a href="<?php echo site_url('backend/frequency')?>" >
                        Frequency
                    </a>
                </li>
                <li <?if ($SUB_LEFT_MENU == "Admin phone") echo 'class="active"';?>>
                    <a href="<?php echo site_url('backend/setting/admin_phone')?>" >
                        Admin phone
                    </a>
                </li>
            </ul>
        </li>
        <li class="submenu <?if ($LEFT_MENU == "Notification") echo " open";?>">
            <a href="#">
                <i class="icon icon-envelope"></i>
                <span>
                    Notification
                </span>
            </a>
            <ul>
                <li <?if ($SUB_LEFT_MENU == "Categories") echo 'class="active"';?>>
                    <a href="<?php echo site_url('backend/notification/categories')?>" >
                        Categories
                    </a>
                </li>
                <li <?if ($SUB_LEFT_MENU == "Companies") echo 'class="active"';?>>
                    <a href="<?php echo site_url('backend/notification/companies')?>" >
                        Companies
                    </a>
                </li>
                <li <?if ($SUB_LEFT_MENU == "Schedule") echo 'class="active"';?>>
                    <a href="<?php echo site_url('backend/notification/schedule')?>" >
                        Schedule
                    </a>
                </li>
            </ul>
        </li>
        <li class="<?if ($LEFT_MENU == "Statistic") echo "open";?>">
            <a href="<?php echo site_url('backend/statistic')?>">
                <i class="icon icon-calendar"></i>
                <span>
                    Statistic
                </span>
            </a>
        </li>

    </ul>

</div>
