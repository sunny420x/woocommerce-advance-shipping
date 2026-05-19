<?php
/**
 * Plugin Name: Custom Shipping Company For Woocommerce
 * Description: ระบบเลือกบริษัทขนส่งเองสำหรับลูกค้า ใช้ร่วมกับ Weight Based Shipping for WooCommerce
 * Version: 1.0
 * Author: Jirakit Pawnsakunrungrot
 * Author URI: https://www.linkedin.com/in/sunny-jirakit
 * Plugin URI: https://github.com/sunny420x/woocommerce-custom-shipping-company
 */

add_action('admin_menu', 'worldchem_custom_shipping_company_menu');

function worldchem_custom_shipping_company_menu()
{
    add_menu_page(
        'จัดการค่าบริการบริษัทขนส่ง', // Title ของหน้า
        'จัดการค่าบริการบริษัทขนส่ง', // ชื่อเมนูที่โชว์ในแถบข้าง
        'manage_options', //สิทธิ์การเข้าถึง (Admin)
        'woocommerce-custom-shipping-settings', // Slug ของหน้า
        'woocommerce_custom_shipping_setting_page', // ฟังก์ชันที่ใช้พ่น HTML หน้า Setting
        'dashicons-airplane', // ไอคอน
        '80' // ตำแหน่งเมนู
    );
}

function woocommerce_custom_shipping_setting_page()
{
    ?>
    <style>
        .leftside {
            width: 350px;
            background: #f8f8f8;
            height: max-content;
        }
        .leftside h1 {
            background: #009FE3;
            color: #fff;
            font-size: 16px;
            padding: 10px 20px;
            margin: 0;
        }
        .leftside a {
            padding: 10px 20px;
            font-size: 14px;
            background: #f8f8f8;
            color: #000;
            transition: .2s ease-in-out;
            display: block;
            width: 100%;
            text-decoration: none;
        }
        .leftside a:hover {
            background: #fff;
            cursor: pointer;
        }
        .container {
            width: 1200px;
            background: #fff; 
        }
        .container h1 {
            background: #555;
            color: #fff;
            font-size: 16px;
            padding: 10px 20px;
            margin: 0;
        }
        .container p {
            padding: 0;
        }
        .white-label-zone {
            width: calc(100% + 20px);
            height: auto;
            background: #fff;
            display: flex;
            margin: 0 0 0 -20px;
        }
        .white-label-zone h1,p {
            padding: 0 20px;
        }
    </style>
    <div class="white-label-zone no-print">
        <span style="padding: 60px 10px 60px 40px;float: left;font-size: 60px;">🚚</span>
        <div style="padding: 20px 0;">
            <h1>WooCommerce Custom Shipping System</h1>
            <p>ระบบเลือกบริษัทขนส่งเองสำหรับลูกค้า และคำนวณค่าขนส่ง ค่าแพ็คสินค้า ตามน้ำหนัก
                <br>
                <strong>Github Repository:</strong> <a href="https://github.com/sunny420x/woocommerce-custom-shipping-company" target="_blank">https://github.com/sunny420x/woocommerce-custom-shipping-company</a>
            </p>
        </div>
    </div>
    <div class="wrap" style="display: flex;">            
        <div class="leftside">
            <h1>WooCommerce Custom Shipping</h1>
            <a href="/wp-admin/admin.php?page=woocommerce-custom-shipping-settings&option=ems">🚚 EMS</a>
            <a href="/wp-admin/admin.php?page=woocommerce-custom-shipping-settings&option=category_based_shipping_cost">🚚 คิดค่าขนส่งคงที่ตามประเภทสินค้า</a>
            <a href="/wp-admin/admin.php?page=woocommerce-custom-shipping-settings&option=packing_settings">📦 การแพ็คสินค้า</a>
            <a href="/wp-admin/admin.php?page=woocommerce-custom-shipping-settings&option=settings">🔧 ตั้งค่าทั่วไป</a>
        </div>
        <div class="container">
            <?php
            if(isset($_GET['option']) && $_GET['option'] == "ems") {
            ?>
            <h1>ไปรษณีย์ไทย (EMS)</h1>
            <div style="padding: 0px 25px 25px 25px;">
                <form action="options.php" method="post" style="display: flex; gap: 50px;">
                    <?php
                    settings_fields('ems_shipping_settings_group');
                    ?>
                    <div>
                        <p>หากลูกค้าเลือกขนส่ง ไปรษณีย์ไทย (EMS) <br>ให้บวกเพิ่มกี่บาท</p>
                        <input type="number" name="ems_fee" value="<?php echo esc_attr(get_option('ems_fee', 20)); ?>" /> บาท
                        <h3>ค่าขนส่ง EMS</h3>
                        <h4>ไม่เกิน 20 กรัม</h4>
                        <input type="number" name="ems_fee_p1" value="<?php echo esc_attr(get_option('ems_fee_p1', 32)); ?>" /> บาท
                        <h4>20 - 100 กรัม</h4>
                        <input type="number" name="ems_fee_p2" value="<?php echo esc_attr(get_option('ems_fee_p2', 37)); ?>" /> บาท
                        <h4>100 - 250 กรัม</h4>
                        <input type="number" name="ems_fee_p3" value="<?php echo esc_attr(get_option('ems_fee_p3', 42)); ?>" /> บาท
                        <h4>250 - 500 กรัม</h4>
                        <input type="number" name="ems_fee_p4" value="<?php echo esc_attr(get_option('ems_fee_p4', 52)); ?>" /> บาท
                        <h4>500 กรัม - 1 กิโลกรัม</h4>
                        <input type="number" name="ems_fee_p5" value="<?php echo esc_attr(get_option('ems_fee_p5', 67)); ?>" /> บาท
                    </div>
                    <div>
                        <h4>1.001 กิโลกรัม - 1.5 กิโลกรัม</h4>
                        <input type="number" name="ems_fee_p6" value="<?php echo esc_attr(get_option('ems_fee_p6', 82)); ?>" /> บาท
                        <h4>1.501 กิโลกรัม - 2 กิโลกรัม</h4>
                        <input type="number" name="ems_fee_p7" value="<?php echo esc_attr(get_option('ems_fee_p7', 97)); ?>" /> บาท
                        <h4>2.001 กิโลกรัม - 2.5 กิโลกรัม</h4>
                        <input type="number" name="ems_fee_p8" value="<?php echo esc_attr(get_option('ems_fee_p8', 100)); ?>" /> บาท
                        <h4>2.501 กิโลกรัม - 3 กิโลกรัม</h4>
                        <input type="number" name="ems_fee_p9" value="<?php echo esc_attr(get_option('ems_fee_p9', 105)); ?>" /> บาท
                        <h4>3.001 กิโลกรัม - 3.5 กิโลกรัม</h4>
                        <input type="number" name="ems_fee_p10"
                            value="<?php echo esc_attr(get_option('ems_fee_p10', 110)); ?>" /> บาท
                        <h4>3.501 กิโลกรัม - 4 กิโลกรัม</h4>
                        <input type="number" name="ems_fee_p11"
                            value="<?php echo esc_attr(get_option('ems_fee_p11', 120)); ?>" /> บาท
                    </div>
                    <div>
                        <h4>4.001 กิโลกรัม - 4.5 กิโลกรัม</h4>
                        <input type="number" name="ems_fee_p12"
                            value="<?php echo esc_attr(get_option('ems_fee_p12', 120)); ?>" /> บาท
                        <h4>4.501 กิโลกรัม - 5 กิโลกรัม</h4>
                        <input type="number" name="ems_fee_p13"
                            value="<?php echo esc_attr(get_option('ems_fee_p13', 120)); ?>" /> บาท
                        <h4>5.001 กิโลกรัม - 5.5 กิโลกรัม</h4>
                        <input type="number" name="ems_fee_p14"
                            value="<?php echo esc_attr(get_option('ems_fee_p14', 130)); ?>" /> บาท
                        <h4>5.501 กิโลกรัม - 6 กิโลกรัม</h4>
                        <input type="number" name="ems_fee_p15"
                            value="<?php echo esc_attr(get_option('ems_fee_p15', 140)); ?>" /> บาท
                        <h4>6 กิโลกรัมขึ้นไป คิดเพิ่มกิโลกรัมละ</h4>
                        <input type="number" name="ems_fee_after_6kg"
                            value="<?php echo esc_attr(get_option('ems_fee_after_6kg', 35)); ?>" /> บาท
                    </div>
                </form>
                <br>
                <button class="button button-primary" style="width: 100%;" type="submit">บันทึกการเปลี่ยนแปลง</button>
            </div>
            <?php
            } else if(isset($_GET['option']) && $_GET['option'] == "settings") {
            ?>
            <h1>ตั้งค่าทั่วไป</h1>
            <div style="padding: 0 25px 25px 25px;">                
                <form action="options.php" method="post">
                    <?php
                    settings_fields('shipping_settings_group');
                    ?>
                    <div style="display: flex; gap: 20px;">
                        <div>
                            <h2>Kerry Express</h2>
                            <p>เปิดใช้งานขนส่ง Kerry Express</p>
                            <select name="enable_kerry_express" id="">
                                <option value="yes" <?php selected(get_option('enable_kerry_express'), 'yes') ?>>ใช่</option>
                                <option value="no" <?php selected(get_option('enable_kerry_express'), 'no') ?>>ไม่ใช่</option>
                            </select>
                            <p>หากลูกค้าเลือกขนส่ง Kerry Express จะบวกเพิ่มเป็นจำนวนกี่บาท</p>
                            <input type="number" name="kerry_express_fee"
                                value="<?php echo esc_attr(get_option('kerry_express_fee', 30)); ?>" />
                            <h2>พื้นที่ห่างไกล (Remote Areas)</h2>
                            <p>กรอกรหัสไปรษณีย์ที่ต้องการบวกค่าบริการเพิ่ม (แยกด้วยเครื่องหมายคอมม่า หรือขึ้นบรรทัดใหม่)</p>
                            <textarea name="remote_areas_list" rows="10" cols="50" class="large-text" style="font-family: monospace;"><?php 
                                echo esc_textarea(get_option('remote_areas_list', '50240, 50250, 50260')); 
                            ?></textarea>
                            <p>หากลูกค้าอยู่ในพื้นที่ห่างไกล เช่น 85 อำเภอห่างไกล จะคิดค่าบริการเพิ่มกี่บาท</p>
                            <input type="number" name="remote_surcharge"
                                value="<?php echo esc_attr(get_option('remote_surcharge', 50)); ?>" />
                        </div>
                        <div>
                            <h2>อื่น ๆ</h2>
                            <p>ลูกค้าสามารถรับสินค้าเองที่ร้านได้</p>
                            <select name="enable_self_pickup" id="">
                                <option value="yes" <?php if(get_option('enable_self_pickup', 'no') == "yes") { echo "selected"; } ?>>เปิด</option>
                                <option value="no" <?php if(get_option('enable_self_pickup', 'no') == "no") { echo "selected"; } ?>>ปิด</option>
                            </select>
                            <br>
                            <p>การรับสินค้าที่ร้านจะไม่ได้รับส่วนลดจากคูปอง</p>
                            <select name="no_discount_self_pickup" id="">
                                <option value="yes" <?php selected(get_option('no_discount_self_pickup'), 'yes') ?>>ใช่</option>
                                <option value="no" <?php selected(get_option('no_discount_self_pickup'), 'no') ?>>ไม่ใช่</option>
                            </select>
                            <br>
                            <h2>เปิด/ปิด ใช้งานการคิดค่าส่งตามประเภทสินค้า</h2>
                            <select name="enable_category_based_shipping_cost" id="">
                                <option value="yes" <?php selected(get_option('enable_category_based_shipping_cost'), 'yes'); ?>>เปิดใช้งาน</option>
                                <option value="no" <?php selected(get_option('enable_category_based_shipping_cost'), 'no'); ?>>ปิดใช้งาน</option>
                            </select>
                            <br><br>
                        </div>
                    </div>
                    <br>
                    <button class="button button-primary" style="width: 100%;" type="submit">บันทึกการเปลี่ยนแปลง</button>
                </form>
            </div>
            <?php 
            } else if(isset($_GET['option']) && $_GET['option'] == "packing_settings") {
            ?>
            <form action="options.php" method="post">
                <?php
                settings_fields('packing_shipping_settings_group');
                ?>
                <h1>การแพ็คสินค้า</h1>
                <div style="padding: 25px 25px 25px 25px;">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>น้ำหนัก (กรัม)</th>
                                <th>ค่าแพ็คสินค้า</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>0 - 1,000</td>
                                <td><input type="number" name="packing_fee_0_1" value="<?php echo esc_attr(get_option('packing_fee_0_1')); ?>" /> บาท</td>
                            </tr>
                            <tr>
                                <td>1,001 - 5,000</td>
                                <td><input type="number" name="packing_fee_1_5" value="<?php echo esc_attr(get_option('packing_fee_1_5')); ?>" /> บาท</td>
                            </tr>
                            <tr>
                                <td>5,001 - 20,000</td>
                                <td><input type="number" name="packing_fee_5_20" value="<?php echo esc_attr(get_option('packing_fee_5_20')); ?>" /> บาท</td>
                            </tr>
                            <tr>
                                <td>20,001 - 30,000</td>
                                <td><input type="number" name="packing_fee_20_30" value="<?php echo esc_attr(get_option('packing_fee_20_30')); ?>" /> บาท</td>
                            </tr>
                            <tr>
                                <td>30,001 กรัมขึ้นไป</td>
                                <td><input type="number" name="packing_fee_30_plus" value="<?php echo esc_attr(get_option('packing_fee_30_plus')); ?>" /> บาท</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <button class="button button-primary" style="width: 100%;" type="submit">บันทึกการเปลี่ยนแปลง</button>
                </div>
            </form>
            <?php
            } else if(isset($_GET['option']) && $_GET['option'] == "category_based_shipping_cost") {
                //Page
                if (isset($_GET['newProfile'])) {
                    //Action
                    if (isset($_POST['slug'])) {
                        $category_based_shipping[] = array(
                            'slug' => sanitize_text_field($_POST['slug']),
                            'cost' => sanitize_text_field($_POST['cost'])
                        );

                        update_option('category_based_shipping_list', $category_based_shipping);
                        $slug = sanitize_text_field($_POST['slug']);
                        wp_redirect(admin_url("admin.php?page=woocommerce-custom-shipping-settings&option=category_based_shipping_cost"));
                        exit;
                    }
            ?>
            <h1>เพิ่มประเภทและค่าจัดส่งคงที่ใหม่</h1>
            <div style="padding: 25px 25px 25px 25px;">
                <form action="" method="POST">
                    <label for="">Slug:</label>
                    <input type="text" name="slug" style="width: 500px;">
                    <br><br>
                    <label for="">ค่าจัดส่ง:</label>
                    <input type="number" name="cost"> บาท
                    <br><br>
                    <button class="button button-primary" style="width: 100%;" type="submit">เพิ่มข้อมูล</button>
                </form>
            </div>
            <?php
                }

                if (isset($_GET['delete'])) {
                    $profiles = get_option('category_based_shipping_list', array());
                    $target_name = $_GET['delete'];
                    $found = false;

                    foreach ($profiles as $index => $profile) {
                        if ($profile['slug'] === $target_name) {
                            unset($profiles[$index]);
                            $found = true;
                            break;
                        }
                    }

                    if ($found) {
                        $profiles = array_values($profiles);

                        update_option('category_based_shipping_list', $profiles);

                        wp_redirect(admin_url('admin.php?page=woocommerce-custom-shipping-settings&option=category_based_shipping_cost'));
                        exit;
                    }
                }

                if(isset($_GET['edit'])) {
                    $profiles = get_option('category_based_shipping_list', array());

                    $selected_profile = array_find($profiles, function ($profile) {
                        return $profile['slug'] === $_GET['edit'];
                    });

                    if (isset($_POST['slug'])) {
                        $profile_name_to_find = $_GET['edit'];
                        $found = false;

                        foreach ($profiles as &$profile) {
                            if ($profile['slug'] === $profile_name_to_find) {

                                // อัปเดตค่าจากฟอร์มลงไปใน Array
                                $profile['slug'] = sanitize_text_field($_POST['slug']);
                                $profile['cost'] = sanitize_text_field($_POST['cost']);

                                $found = true;
                                break;
                            }
                        }

                        if (!$found) {
                            $profiles[] = array(
                                'slug' => sanitize_text_field($_POST['slug']),
                                'cost' => sanitize_text_field($_POST['cost'])
                            );
                        }

                        update_option('category_based_shipping_list', $profiles);
                        $slug = sanitize_text_field($_POST['slug']);
                        wp_redirect(admin_url("admin.php?page=woocommerce-custom-shipping-settings&option=category_based_shipping_cost&edit=$slug"));
                        exit;
                    }
                ?>
                <h1>แก้ไขประเภทและค่าจัดส่งคงที่ใหม่</h1>
                <div style="padding: 25px 25px 25px 25px;">
                    <form action="" method="POST">
                        <label for="">Slug:</label>
                        <input type="text" name="slug" style="width: 500px;" value="<?=$selected_profile['slug'];?>">
                        <br><br>
                        <label for="">ค่าจัดส่ง:</label>
                        <input type="number" name="cost" value="<?=$selected_profile['cost'];?>"> บาท
                        <br><br>
                        <button class="button button-primary" style="width: 100%;" type="submit">บันทึกการเปลี่ยนแปลง</button>
                    </form>
                </div>
            <?php
                }
            ?>
            <h1>ใช้งานการคิดค่าส่งตามประเภทสินค้า (Product Category Based Shipping Cost)</h1>
            <div style="padding: 25px 25px 25px 25px;">
                <button class="button" style="width: 100%;" onclick="window.location.href='admin.php?page=woocommerce-custom-shipping-settings&option=category_based_shipping_cost&newProfile'">➕ เพิ่มประเภทและค่าจัดส่งคงที่ใหม่</button>
                <table class="wp-list-table widefat fixed striped" style="margin-top: 15px;">
                    <thead>
                        <tr>
                            <th>Slug ของสินค้า</th>
                            <th>ค่าส่ง (คงที่)</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $category_based_shipping = get_option('category_based_shipping_list', array());
                        foreach($category_based_shipping as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row['slug']; ?></td>
                            <td><?php echo $row['cost']; ?></td>
                            <td>
                                <button class="button" onclick="window.location.href='admin.php?page=woocommerce-custom-shipping-settings&option=category_based_shipping_cost&edit=<?=$row['slug'];?>'">แก้ไข</button>
                                <button class="button" onclick="window.location.href='admin.php?page=woocommerce-custom-shipping-settings&option=category_based_shipping_cost&delete=<?=$row['slug'];?>'">ลบ</button>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
            } else {
            ?>
            <h1>WooCommerce Custom Shipping Setting</h1>
            <div style="padding: 0 25px 25px 25px;">
                <h2>ระบบนี้คืออะไร ?</h2>
                <p>ระบบ WooCommerce Custom Shipping Setting
                    คือระบบที่ออกแบบมาเพื่อจัดการค่าบริการบริษัทขนส่งสำหรับ Weight Based Shipping for WooCommerce รองรับการเลือกบริษัทขนส่งเองโดยลูกค้า 
                    รองรับการคิดค่าบริการขนส่งแบบขั้นบันใดของไปรษณีย์ไทย รองรับการกำหนดค่าขนส่งแบบคงที่จะสำหรับหมวดหมู่ของสินค้าบางหมวดหมู่
                </p>
                <h2>วิธีการติดตั้ง</h2>
                <p>
                    สามารถติดตั้งปลั้กอินนี้ได้โดยการดาวน์โหลดไฟล์นี้จาก Github หน้านี้ และอัพโหลดลงในหน้า /wp-admin/plugin-install.php หลังจากอัพโหลด 
                    และเปิดใช้งาน (Activate) ระบบจะทำการสร้างตารางและคอลัมน์ใหม่จากตารางเดิมโดยอัตโนมัติ
                </p>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
    <?php
}

add_action('admin_init', 'woocommerce_custom_shipping_setting_init');
function woocommerce_custom_shipping_setting_init()
{
    register_setting('shipping_settings_group', 'enable_self_pickup');

    register_setting('ems_shipping_settings_group', 'ems_fee');
    register_setting('ems_shipping_settings_group', 'ems_fee_p1');
    register_setting('ems_shipping_settings_group', 'ems_fee_p2');
    register_setting('ems_shipping_settings_group', 'ems_fee_p3');
    register_setting('ems_shipping_settings_group', 'ems_fee_p4');
    register_setting('ems_shipping_settings_group', 'ems_fee_p5');
    register_setting('ems_shipping_settings_group', 'ems_fee_p6');
    register_setting('ems_shipping_settings_group', 'ems_fee_p7');
    register_setting('ems_shipping_settings_group', 'ems_fee_p8');
    register_setting('ems_shipping_settings_group', 'ems_fee_p9');
    register_setting('ems_shipping_settings_group', 'ems_fee_p10');
    register_setting('ems_shipping_settings_group', 'ems_fee_p11');
    register_setting('ems_shipping_settings_group', 'ems_fee_p12');
    register_setting('ems_shipping_settings_group', 'ems_fee_p13');
    register_setting('ems_shipping_settings_group', 'ems_fee_p14');
    register_setting('ems_shipping_settings_group', 'ems_fee_p15');
    register_setting('ems_shipping_settings_group', 'ems_fee_after_6kg');
    
    register_setting('packing_shipping_settings_group', 'packing_fee_0_1');
    register_setting('packing_shipping_settings_group', 'packing_fee_1_5');
    register_setting('packing_shipping_settings_group', 'packing_fee_5_20');
    register_setting('packing_shipping_settings_group', 'packing_fee_20_30');
    register_setting('packing_shipping_settings_group', 'packing_fee_30_plus');
    
    register_setting('shipping_settings_group', 'kerry_express_fee');
    register_setting('shipping_settings_group', 'remote_surcharge');
    register_setting('shipping_settings_group', 'remote_areas_list');
    register_setting('shipping_settings_group', 'no_discount_self_pickup');
    register_setting('shipping_settings_group', 'enable_kerry_express');

    register_setting('shipping_settings_group', 'enable_category_based_shipping_cost');
    register_setting('category_shipping_settings_group', 'category_based_shipping_list');
}

add_filter('woocommerce_package_rates', 'combined_shipping_methods', 10, 2);
function combined_shipping_methods($rates, $package)
{
    $new_rates = array();
    $total_weight = WC()->cart->get_cart_contents_weight(); // กรัม

    if($total_weight <= 1000) {
        $packing_fee = (float) get_option('packing_fee_0_1');
    } else if($total_weight <= 5000) {
        $packing_fee = (float) get_option('packing_fee_1_5');
    } else if($total_weight <= 20000) {
        $packing_fee = (float) get_option('packing_fee_5_20');
    } else if($total_weight <= 30000) {
        $packing_fee = (float) get_option('packing_fee_20_30');
    } else if($total_weight > 30001) {
        $packing_fee = (float) get_option('packing_fee_30_plus');
    }

    // 1. ดึงรหัสไปรษณีย์
    $destination_zip = $package['destination']['postcode'];

    // 2. รายชื่อรหัสพื้นที่ห่างไกล (ก๊อปของพี่มาใส่ตรงนี้)
    $remote_areas_raw = get_option('remote_areas_list', '');
    $remote_areas = explode("\n", $remote_areas_raw);
    $remote_areas = array_map('trim', $remote_areas);

    // 3. เช็คว่าเป็นพื้นที่ห่างไกลไหม
    $is_remote = in_array($destination_zip, $remote_areas);
    $remote_surcharge = $is_remote ? (float) get_option('remote_surcharge', 60) : 0; // ถ้าใช่ บวก 50 ถ้าไม่ใช่ บวก 0

    foreach ($rates as $rate_id => $rate) {
        if (strpos($rate_id, 'wbs') !== false) {

            $original_rate = clone $rate;
            
            if(get_option('enable_category_based_shipping_cost') == "yes") {
                $category_based_shipping = get_option('category_based_shipping_list', array());
                
                $total_category_cost = 0;
                $has_custom_cat = false;

                // 1. ลูปสินค้าในตะกร้าทีละชิ้น
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $product_id = $cart_item['product_id'];
                    $quantity = $cart_item['quantity']; // จำนวนชิ้นของสินค้าตัวนี้

                    // ตั้งค่าเริ่มต้นสำหรับสินค้าชิ้นนี้: ให้ค่าส่งแพงที่สุดเริ่มต้นที่ 0
                    $highest_item_cost = 0;
                    $item_matched = false;

                    // 2. ตรวจสอบสินค้าชิ้นนี้กับทุกโปรไฟล์หมวดหมู่ที่ตั้งไว้
                    foreach($category_based_shipping as $profile) {                     
                        $target_slug = trim($profile['slug']); 

                        // ถ้าสินค้าชิ้นนี้ตรงกับหมวดหมู่พิเศษนี้
                        if ( has_term($target_slug, 'product_cat', $product_id ) ) {
                            $item_matched = true;
                            $current_cat_cost = (float)$profile['cost'];

                            // ถ้าค่าส่งของหมวดนี้ แพงกว่าหมวดก่อนหน้าของสินค้าชิ้นเดียวกัน ให้ดึงค่านี้มาแทน
                            if ($current_cat_cost > $highest_item_cost) {
                                $highest_item_cost = $current_cat_cost;
                            }
                        }
                    }

                    // 3. หลังจากเช็คครบทุกหมวดของสินค้าชิ้นนี้แล้ว ค่อยเอาเรทที่แพงที่สุดไปคูณจำนวนชิ้น
                    if ($item_matched) {
                        $has_custom_cat = true;
                        
                        // เอาค่าส่งที่แพงที่สุดของสินค้าชิ้นนี้ × จำนวนชิ้น
                        $item_shipping_cost = $highest_item_cost * $quantity;
                        
                        // บวกสะสมเข้าไปในยอดรวมทั้งหมดของตะกร้า
                        $total_category_cost += $item_shipping_cost;
                    }
                }

                // 3. ถ้ามีสินค้าหมวดพิเศษอยู่ในตะกร้าและมียอดเงิน
                if ($has_custom_cat && $total_category_cost > 0) {
                    // หักดิบค่าน้ำหนักทิ้ง แล้วแทนที่ด้วยยอดสะสมคูณจำนวนชิ้นของทุกหมวดหมู่รวมกัน
                    $original_rate->cost = $total_category_cost; 
                }
            }
            
            $original_rate->label = 'ค่าจัดส่ง (เลือกอัตโนมัติ)';
            $original_rate->cost += $remote_surcharge;
            $original_rate->cost += $packing_fee;
            $new_rates[$rate_id] = $original_rate;
                
            if(get_option("enable_kerry_express") == "yes") {
                $kerry_rate = clone $rate;
                $kerry_rate->id = $rate_id . '_kerry'; // เติม ID ต่อท้าย
                $kerry_rate->label = 'Kerry Express';
                $kerry_rate->cost += $remote_surcharge + (float) get_option('kerry_express_fee', 30);
                $new_rates[$kerry_rate->id] = $kerry_rate;
            }

            //Thailand Post ส่งได้มากสุดแค่ 20kg
            if ($total_weight <= 20000) {
                $ems = clone $rate;
                $ems->id = $rate_id . '_ems';
                $ems->label = 'ไปรษณีย์ไทย (EMS)';

                $w = $total_weight; // หน่วยเป็นกรัม
                $ems_cost = 0;

                if ($w <= 20) {
                    $ems_cost = (float) get_option('ems_fee_p1', 32);
                } elseif ($w <= 100) {
                    $ems_cost = (float) get_option('ems_fee_p2', 37);
                } elseif ($w <= 250) {
                    $ems_cost = (float) get_option('ems_fee_p3', 42);
                } elseif ($w <= 500) {
                    $ems_cost = (float) get_option('ems_fee_p4', 52);
                } elseif ($w <= 1000) {
                    $ems_cost = (float) get_option('ems_fee_p5', 67);
                } elseif ($w <= 1500) {
                    $ems_cost = (float) get_option('ems_fee_p6', 82);
                } elseif ($w <= 2000) {
                    $ems_cost = (float) get_option('ems_fee_p7', 97);
                } elseif ($w <= 2500) {
                    $ems_cost = (float) get_option('ems_fee_p8', 100);
                } elseif ($w <= 3000) {
                    $ems_cost = (float) get_option('ems_fee_p9', 105);
                } elseif ($w <= 3500) {
                    $ems_cost = (float) get_option('ems_fee_p10', 110);
                } elseif ($w <= 4000) {
                    $ems_cost = (float) get_option('ems_fee_p11', 120);
                } elseif ($w <= 4500) {
                    $ems_cost = (float) get_option('ems_fee_p12', 120);
                } elseif ($w <= 5000) {
                    $ems_cost = (float) get_option('ems_fee_p13', 120);
                } elseif ($w <= 5500) {
                    $ems_cost = (float) get_option('ems_fee_p14', 130);
                } elseif ($w <= 6000) {
                    $ems_cost = (float) get_option('ems_fee_p15', 140);
                } else {
                    // ถ้าเกิน 6 กิโล ให้ใช้เรทสุดท้าย หรือจะบวกเพิ่มกิโลละ 20 บาทก็ได้ครับ
                    $extra_kg = ceil(($w - 6000) / 1000);
                    $ems_cost = (float) get_option('ems_fee_p15', 140) + ($extra_kg * get_option('ems_fee_after_6kg', 35));
                }

                $ems->cost = $ems_cost + $packing_fee + $remote_surcharge;
                $new_rates[$ems->id] = $ems;
            }

            if(get_option('enable_self_pickup', "no") == "yes") {
                $self_pickup = clone $rate;
                $pickup_id = $rate_id . '_selfpickup';
                $self_pickup->id = $pickup_id;
                $self_pickup->label = 'รับเองหน้าร้าน';
                $self_pickup->cost = 0;
                $new_rates[$pickup_id] = $self_pickup;
            }

            if(get_option('no_discount_self_pickup', "yes") == "yes") {
                add_action('woocommerce_before_calculate_totals', 'disable_discounts_for_self_pickup', 20);

                function disable_discounts_for_self_pickup($cart) {
                    if (is_admin() && !defined('DOING_AJAX')) return;

                    $chosen_methods = WC()->session->get('chosen_shipping_methods');
                    $chosen_shipping = isset($chosen_methods[0]) ? $chosen_methods[0] : '';

                    if (strpos($chosen_shipping, '_selfpickup') !== false) {
                        if (!empty($cart->get_applied_coupons())) {
                            $cart->remove_coupons();
                            wc_clear_notices();
                            wc_add_notice('การรับสินค้าเองหน้าร้านไม่สามารถใช้ร่วมกับคูปองส่วนลดได้', 'notice');
                        }
                    }
                }
            }
        } else {
            // ถ้ามีขนส่งอื่นที่ไม่ใช่ Weight Based ให้โชว์ปกติ
            $new_rates[$rate_id] = $rate;
        }
    }

    return $new_rates;
}

/**
 * บันทึกชื่อบริษัทขนส่งลงใน Order Note หลังจากลูกค้าสั่งซื้อ
 */
add_action('woocommerce_checkout_update_order_meta', 'worldchem_save_shipping_label_to_order_note', 10, 2);
function worldchem_save_shipping_label_to_order_note($order_id, $data)
{
    // 1. ดึงข้อมูลการจัดส่งจากออเดอร์
    $order = wc_get_order($order_id);
    $shipping_methods = $order->get_shipping_methods();

    foreach ($shipping_methods as $method) {
        // ดึงชื่อ Label ที่ลูกค้าเลือก (เช่น ไปรษณีย์ไทย (EMS))
        $method_name = $method->get_name();

        // 2. เขียนข้อความลงใน Order Note (หลังบ้านจะเห็นเป็นแถบสีม่วง/เทา)
        $note = "ประเภทการขนส่งที่ลูกค้าเลือก: " . $method_name;
        $order->add_order_note($note);
    }
}