<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'user_alert_create',
            ],
            [
                'id'    => 18,
                'title' => 'user_alert_show',
            ],
            [
                'id'    => 19,
                'title' => 'user_alert_delete',
            ],
            [
                'id'    => 20,
                'title' => 'user_alert_access',
            ],
            [
                'id'    => 21,
                'title' => 'product_management_access',
            ],
            [
                'id'    => 22,
                'title' => 'product_category_create',
            ],
            [
                'id'    => 23,
                'title' => 'product_category_edit',
            ],
            [
                'id'    => 24,
                'title' => 'product_category_show',
            ],
            [
                'id'    => 25,
                'title' => 'product_category_delete',
            ],
            [
                'id'    => 26,
                'title' => 'product_category_access',
            ],
            [
                'id'    => 27,
                'title' => 'product_tag_create',
            ],
            [
                'id'    => 28,
                'title' => 'product_tag_edit',
            ],
            [
                'id'    => 29,
                'title' => 'product_tag_show',
            ],
            [
                'id'    => 30,
                'title' => 'product_tag_delete',
            ],
            [
                'id'    => 31,
                'title' => 'product_tag_access',
            ],
            [
                'id'    => 32,
                'title' => 'product_create',
            ],
            [
                'id'    => 33,
                'title' => 'product_edit',
            ],
            [
                'id'    => 34,
                'title' => 'product_show',
            ],
            [
                'id'    => 35,
                'title' => 'product_delete',
            ],
            [
                'id'    => 36,
                'title' => 'product_access',
            ],
            [
                'id'    => 37,
                'title' => 'add_company_create',
            ],
            [
                'id'    => 38,
                'title' => 'add_company_edit',
            ],
            [
                'id'    => 39,
                'title' => 'add_company_show',
            ],
            [
                'id'    => 40,
                'title' => 'add_company_delete',
            ],
            [
                'id'    => 41,
                'title' => 'add_company_access',
            ],
            [
                'id'    => 42,
                'title' => 'stocks_management_access',
            ],
            [
                'id'    => 43,
                'title' => 'our_stock_create',
            ],
            [
                'id'    => 44,
                'title' => 'our_stock_edit',
            ],
            [
                'id'    => 45,
                'title' => 'our_stock_show',
            ],
            [
                'id'    => 46,
                'title' => 'our_stock_delete',
            ],
            [
                'id'    => 47,
                'title' => 'our_stock_access',
            ],
            [
                'id'    => 48,
                'title' => 'check_godown_create',
            ],
            [
                'id'    => 49,
                'title' => 'check_godown_edit',
            ],
            [
                'id'    => 50,
                'title' => 'check_godown_show',
            ],
            [
                'id'    => 51,
                'title' => 'check_godown_delete',
            ],
            [
                'id'    => 52,
                'title' => 'check_godown_access',
            ],
            [
                'id'    => 53,
                'title' => 'stock_transfer_create',
            ],
            [
                'id'    => 54,
                'title' => 'stock_transfer_edit',
            ],
            [
                'id'    => 55,
                'title' => 'stock_transfer_show',
            ],
            [
                'id'    => 56,
                'title' => 'stock_transfer_delete',
            ],
            [
                'id'    => 57,
                'title' => 'stock_transfer_access',
            ],
            [
                'id'    => 58,
                'title' => 'order_access',
            ],
            [
                'id'    => 59,
                'title' => 'check_order_create',
            ],
            [
                'id'    => 60,
                'title' => 'check_order_edit',
            ],
            [
                'id'    => 61,
                'title' => 'check_order_show',
            ],
            [
                'id'    => 62,
                'title' => 'check_order_delete',
            ],
            [
                'id'    => 63,
                'title' => 'check_order_access',
            ],
            [
                'id'    => 64,
                'title' => 'shipping_access',
            ],
            [
                'id'    => 65,
                'title' => 'carrier_create',
            ],
            [
                'id'    => 66,
                'title' => 'carrier_edit',
            ],
            [
                'id'    => 67,
                'title' => 'carrier_show',
            ],
            [
                'id'    => 68,
                'title' => 'carrier_delete',
            ],
            [
                'id'    => 69,
                'title' => 'carrier_access',
            ],
            [
                'id'    => 70,
                'title' => 'cancellation_create',
            ],
            [
                'id'    => 71,
                'title' => 'cancellation_edit',
            ],
            [
                'id'    => 72,
                'title' => 'cancellation_show',
            ],
            [
                'id'    => 73,
                'title' => 'cancellation_delete',
            ],
            [
                'id'    => 74,
                'title' => 'cancellation_access',
            ],
            [
                'id'    => 75,
                'title' => 'support_desk_access',
            ],
            [
                'id'    => 76,
                'title' => 'dispute_create',
            ],
            [
                'id'    => 77,
                'title' => 'dispute_edit',
            ],
            [
                'id'    => 78,
                'title' => 'dispute_show',
            ],
            [
                'id'    => 79,
                'title' => 'dispute_delete',
            ],
            [
                'id'    => 80,
                'title' => 'dispute_access',
            ],
            [
                'id'    => 81,
                'title' => 'refund_create',
            ],
            [
                'id'    => 82,
                'title' => 'refund_edit',
            ],
            [
                'id'    => 83,
                'title' => 'refund_show',
            ],
            [
                'id'    => 84,
                'title' => 'refund_delete',
            ],
            [
                'id'    => 85,
                'title' => 'refund_access',
            ],
            [
                'id'    => 86,
                'title' => 'setting_access',
            ],
            [
                'id'    => 87,
                'title' => 'tax_create',
            ],
            [
                'id'    => 88,
                'title' => 'tax_edit',
            ],
            [
                'id'    => 89,
                'title' => 'tax_show',
            ],
            [
                'id'    => 90,
                'title' => 'tax_delete',
            ],
            [
                'id'    => 91,
                'title' => 'tax_access',
            ],
            [
                'id'    => 92,
                'title' => 'shop_setting_create',
            ],
            [
                'id'    => 93,
                'title' => 'shop_setting_edit',
            ],
            [
                'id'    => 94,
                'title' => 'shop_setting_show',
            ],
            [
                'id'    => 95,
                'title' => 'shop_setting_delete',
            ],
            [
                'id'    => 96,
                'title' => 'shop_setting_access',
            ],
            [
                'id'    => 97,
                'title' => 'configuration_create',
            ],
            [
                'id'    => 98,
                'title' => 'configuration_edit',
            ],
            [
                'id'    => 99,
                'title' => 'configuration_show',
            ],
            [
                'id'    => 100,
                'title' => 'configuration_delete',
            ],
            [
                'id'    => 101,
                'title' => 'configuration_access',
            ],
            [
                'id'    => 102,
                'title' => 'support_create',
            ],
            [
                'id'    => 103,
                'title' => 'support_edit',
            ],
            [
                'id'    => 104,
                'title' => 'support_show',
            ],
            [
                'id'    => 105,
                'title' => 'support_delete',
            ],
            [
                'id'    => 106,
                'title' => 'support_access',
            ],
            [
                'id'    => 107,
                'title' => 'web_setting_access',
            ],
            [
                'id'    => 108,
                'title' => 'privacy_policy_create',
            ],
            [
                'id'    => 109,
                'title' => 'privacy_policy_edit',
            ],
            [
                'id'    => 110,
                'title' => 'privacy_policy_show',
            ],
            [
                'id'    => 111,
                'title' => 'privacy_policy_delete',
            ],
            [
                'id'    => 112,
                'title' => 'privacy_policy_access',
            ],
            [
                'id'    => 113,
                'title' => 'term_condition_create',
            ],
            [
                'id'    => 114,
                'title' => 'term_condition_edit',
            ],
            [
                'id'    => 115,
                'title' => 'term_condition_show',
            ],
            [
                'id'    => 116,
                'title' => 'term_condition_delete',
            ],
            [
                'id'    => 117,
                'title' => 'term_condition_access',
            ],
            [
                'id'    => 118,
                'title' => 'about_us_create',
            ],
            [
                'id'    => 119,
                'title' => 'about_us_edit',
            ],
            [
                'id'    => 120,
                'title' => 'about_us_show',
            ],
            [
                'id'    => 121,
                'title' => 'about_us_delete',
            ],
            [
                'id'    => 122,
                'title' => 'about_us_access',
            ],
            [
                'id'    => 123,
                'title' => 'wallet_management_access',
            ],
            [
                'id'    => 124,
                'title' => 'wallet_request_create',
            ],
            [
                'id'    => 125,
                'title' => 'wallet_request_edit',
            ],
            [
                'id'    => 126,
                'title' => 'wallet_request_show',
            ],
            [
                'id'    => 127,
                'title' => 'wallet_request_delete',
            ],
            [
                'id'    => 128,
                'title' => 'wallet_request_access',
            ],
            [
                'id'    => 129,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 130,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 131,
                'title' => 'asset_management_access',
            ],
            [
                'id'    => 132,
                'title' => 'asset_category_create',
            ],
            [
                'id'    => 133,
                'title' => 'asset_category_edit',
            ],
            [
                'id'    => 134,
                'title' => 'asset_category_show',
            ],
            [
                'id'    => 135,
                'title' => 'asset_category_delete',
            ],
            [
                'id'    => 136,
                'title' => 'asset_category_access',
            ],
            [
                'id'    => 137,
                'title' => 'asset_location_create',
            ],
            [
                'id'    => 138,
                'title' => 'asset_location_edit',
            ],
            [
                'id'    => 139,
                'title' => 'asset_location_show',
            ],
            [
                'id'    => 140,
                'title' => 'asset_location_delete',
            ],
            [
                'id'    => 141,
                'title' => 'asset_location_access',
            ],
            [
                'id'    => 142,
                'title' => 'asset_status_create',
            ],
            [
                'id'    => 143,
                'title' => 'asset_status_edit',
            ],
            [
                'id'    => 144,
                'title' => 'asset_status_show',
            ],
            [
                'id'    => 145,
                'title' => 'asset_status_delete',
            ],
            [
                'id'    => 146,
                'title' => 'asset_status_access',
            ],
            [
                'id'    => 147,
                'title' => 'asset_create',
            ],
            [
                'id'    => 148,
                'title' => 'asset_edit',
            ],
            [
                'id'    => 149,
                'title' => 'asset_show',
            ],
            [
                'id'    => 150,
                'title' => 'asset_delete',
            ],
            [
                'id'    => 151,
                'title' => 'asset_access',
            ],
            [
                'id'    => 152,
                'title' => 'assets_history_access',
            ],
            [
                'id'    => 153,
                'title' => 'expense_management_access',
            ],
            [
                'id'    => 154,
                'title' => 'expense_category_create',
            ],
            [
                'id'    => 155,
                'title' => 'expense_category_edit',
            ],
            [
                'id'    => 156,
                'title' => 'expense_category_show',
            ],
            [
                'id'    => 157,
                'title' => 'expense_category_delete',
            ],
            [
                'id'    => 158,
                'title' => 'expense_category_access',
            ],
            [
                'id'    => 159,
                'title' => 'income_category_create',
            ],
            [
                'id'    => 160,
                'title' => 'income_category_edit',
            ],
            [
                'id'    => 161,
                'title' => 'income_category_show',
            ],
            [
                'id'    => 162,
                'title' => 'income_category_delete',
            ],
            [
                'id'    => 163,
                'title' => 'income_category_access',
            ],
            [
                'id'    => 164,
                'title' => 'expense_create',
            ],
            [
                'id'    => 165,
                'title' => 'expense_edit',
            ],
            [
                'id'    => 166,
                'title' => 'expense_show',
            ],
            [
                'id'    => 167,
                'title' => 'expense_delete',
            ],
            [
                'id'    => 168,
                'title' => 'expense_access',
            ],
            [
                'id'    => 169,
                'title' => 'income_create',
            ],
            [
                'id'    => 170,
                'title' => 'income_edit',
            ],
            [
                'id'    => 171,
                'title' => 'income_show',
            ],
            [
                'id'    => 172,
                'title' => 'income_delete',
            ],
            [
                'id'    => 173,
                'title' => 'income_access',
            ],
            [
                'id'    => 174,
                'title' => 'expense_report_create',
            ],
            [
                'id'    => 175,
                'title' => 'expense_report_edit',
            ],
            [
                'id'    => 176,
                'title' => 'expense_report_show',
            ],
            [
                'id'    => 177,
                'title' => 'expense_report_delete',
            ],
            [
                'id'    => 178,
                'title' => 'expense_report_access',
            ],
            [
                'id'    => 179,
                'title' => 'profile_password_edit',
            ],
            ['title' => 'sub_category_create'],
            ['title' => 'sub_category_edit'],
            ['title' => 'sub_category_show'],
            ['title' => 'sub_category_delete'],
            ['title' => 'sub_category_access'],
        ];

        // This seeder is also run against existing installations.  Do not use
        // insert() with generator-era fixed IDs: it fails on the second run.
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['title' => $permission['title']]);
        }
    }
}
