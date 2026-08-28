<?php
/**
 * Plugin Name: Advance Shipping For Woocommerce
 * Description: ระบบกำหนดค่าบริการขนส่ง และเลือกบริษัทขนส่งเองสำหรับลูกค้า สำหรับสินค้าบน WooCommerce
 * Author: Jirakit Pawnsakunrungrot
 * Author URI: https://www.linkedin.com/in/sunny-jirakit
 * Plugin URI: https://github.com/sunny420x/woocommerce-advance-shipping
 * GitHub Plugin URI: https://github.com/sunny420x/woocommerce-advance-shipping
 * Primary Branch: master
 */

add_action('admin_menu', 'custom_shipping_company_menu');

function custom_shipping_company_menu()
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
        .leftside a.active {
            background: #fff;
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
        <span style="padding: 40px 10px 40px 40px; float: left;font-size: 60px;">🚚</span>
        <div style="padding: 20px 0;">
            <h1>WooCommerce Advance Shipping System</h1>
            <p>ระบบคำนวณค่าขนส่ง ค่าแพ็คสินค้า ตามน้ำหนัก
                <br>
                <strong>Github Repository:</strong> <a href="https://github.com/sunny420x/woocommerce-custom-shipping-company" target="_blank">https://github.com/sunny420x/woocommerce-custom-shipping-company</a>
            </p>
        </div>
    </div>
    <div class="wrap" style="display: flex;">            
        <div class="leftside">
            <h1>WooCommerce Advance Shipping</h1>
            <a href="/wp-admin/admin.php?page=woocommerce-custom-shipping-settings&option=default" <?php if(isset($_GET['option']) && $_GET['option'] == "default") { echo "class='active'"; } ?>>🚩 ค่าเริ่มต้น</a>
            <a href="/wp-admin/admin.php?page=woocommerce-custom-shipping-settings&option=ems" <?php if(isset($_GET['option']) && $_GET['option'] == "ems") { echo "class='active'"; } ?>>🚚 EMS</a>
            <a href="/wp-admin/admin.php?page=woocommerce-custom-shipping-settings&option=category_based_shipping_cost" <?php if(isset($_GET['option']) && $_GET['option'] == "category_based_shipping_cost") { echo "class='active'"; } ?>>🚚 คิดค่าขนส่งคงที่ตามประเภทสินค้า</a>
            <a href="/wp-admin/admin.php?page=woocommerce-custom-shipping-settings&option=packing_settings" <?php if(isset($_GET['option']) && $_GET['option'] == "packing_settings") { echo "class='active'"; } ?>>📦 การแพ็คสินค้า</a>
            <a href="/wp-admin/admin.php?page=woocommerce-custom-shipping-settings&option=free_shipping" <?php if(isset($_GET['option']) && $_GET['option'] == "free_shipping") { echo "class='active'"; } ?>>🆓 สินค้าส่งฟรี</a>
            <a href="/wp-admin/admin.php?page=woocommerce-custom-shipping-settings&option=weight_threshold_shipping" <?php if(isset($_GET['option']) && $_GET['option'] == "weight_threshold_shipping") { echo "class='active'"; } ?>>⚖️ คิดค่าขนส่งตามเกณฑ์น้ำหนัก</a>
            <a href="/wp-admin/admin.php?page=woocommerce-custom-shipping-settings&option=settings" <?php if(isset($_GET['option']) && $_GET['option'] == "settings") { echo "class='active'"; } ?>>🔧 ตั้งค่าทั่วไป</a>
        </div>
        <div class="container">
            <?php
            if(isset($_GET['option']) && $_GET['option'] == "default") {
                if (isset($_GET['newProfile'])) {
                    if (isset($_POST['addProfile'])) {
                        $id = rand();
                        $default_shipping_pricing = get_option('default_shipping_pricing', array());
                        $default_shipping_pricing[] = array(
                            'id' => $id,
                            'start' => sanitize_text_field($_POST['start']),
                            'end' => sanitize_text_field($_POST['end']),
                            'cost' => sanitize_text_field($_POST['cost'])
                        );

                        update_option('default_shipping_pricing', $default_shipping_pricing);
                        wp_redirect(admin_url("admin.php?page=woocommerce-custom-shipping-settings&option=default"));
                        exit;
                    }
            ?>
                <h1>เพิ่มช่วงค่าขนส่งใหม่</h1>
                <div style="padding: 25px 25px 25px 25px;">
                    <form action="" method="post">
                        <label for="start">น้ำหนักตั้งแต่: </label><br>
                        <input type="number" name="start" id="start" style="width: 500px;"> กรัม<br>
                        <label for="end">ถึงน้ำหนัก: </label><br>
                        <input type="number" name="end" id="end" style="width: 500px;"> กรัม<br>    
                        <label for="end">ค่าขนส่ง: </label><br>
                        <input type="number" name="cost" id="cost" style="width: 500px;"> บาท<br>
                        <br>
                        <input type="submit" value="เพิ่มช่วงค่าขนส่งใหม่" class="button botton-outline-primary" name="addProfile">
                    </form>
                </div>
            <?php
                    return;
                }
            ?>
            <?php  
                if(isset($_GET['edit'])) {
                    $profiles = get_option('default_shipping_pricing', array());
                    $id = $_GET['edit'];

                    $selected_profile = array_find($profiles, function ($profile) {
                        return $profile['id'] == $_GET['edit'];
                    });

                    if (isset($_POST['editProfile'])) {
                        foreach ($profiles as &$profile) {
                            if ($profile['id'] == $id) {

                                $profile['start'] = sanitize_text_field($_POST['start']);
                                $profile['end'] = sanitize_text_field($_POST['end']);
                                $profile['cost'] = sanitize_text_field($_POST['cost']);
                                break;
                            }
                        }

                        update_option('default_shipping_pricing', $profiles);
                        wp_redirect(admin_url("admin.php?page=woocommerce-custom-shipping-settings&option=default&edit=$id"));
                        exit;
                    }
                ?>
                <h1>แก้ไขค่าขนส่ง</h1>
                <div style="padding: 25px 25px 25px 25px;">
                    <form action="" method="post">
                        <label for="start">น้ำหนักตั้งแต่: </label><br>
                        <input type="number" name="start" id="start" value="<?=$selected_profile['start'];?>" style="width: 500px;"> กรัม<br>
                        <label for="end">ถึงน้ำหนัก: </label><br>
                        <input type="number" name="end" id="end" value="<?=$selected_profile['end'];?>" style="width: 500px;"> กรัม<br>
                        <label for="end">ค่าขนส่ง: </label><br>
                        <input type="number" name="cost" id="cost" value="<?=$selected_profile['cost'];?>" style="width: 500px;"> บาท<br>
                        <br>
                        <input type="submit" value="แก้ไขค่าขนส่ง" class="button botton-outline-primary" name="editProfile">
                    </form>
                </div>
                <?php
                    return;
                }

                if (isset($_GET['delete'])) {
                    $profiles = get_option('default_shipping_pricing', array());
                    $id = $_GET['delete'];
                    $found = false;

                    foreach ($profiles as $index => $profile) {
                        if ($profile['id'] == $id) {
                            unset($profiles[$index]);
                            $found = true;
                            break;
                        }
                    }

                    if ($found) {
                        $profiles = array_values($profiles);

                        update_option('default_shipping_pricing', $profiles);

                        wp_redirect(admin_url('admin.php?page=woocommerce-custom-shipping-settings&option=default'));
                        exit;
                    }
                }

                if(isset($_GET['adjustPrice'])) {
                    if(isset($_POST['adjustPrice'])) {
                        $adjustAmount = (float) sanitize_text_field( $_POST['adjustAmount'] );
                        $profiles = get_option('default_shipping_pricing', array());
                        foreach ($profiles as &$profile) {
                            $profile['cost'] = (float) $profile['cost'] + $adjustAmount;
                        }
                        unset($profile); // break the reference

                        update_option('default_shipping_pricing', $profiles);
                        wp_redirect(admin_url("admin.php?page=woocommerce-custom-shipping-settings&option=default"));
                        exit;
                    }
                ?>
                <h1>ปรับราคาค่าขนส่ง</h1>
                <div style="padding: 25px 25px 25px 25px;">
                    <form action="" method="post">
                        <label for="end">ปรับเพิ่มจากเดิมจำนวน: </label><br>
                        <input type="number" name="adjustAmount" id="adjustAmount" style="width: 500px;"> บาท<br>
                        <br>
                        <input type="submit" value="ปรับราคาค่าขนส่ง" class="button botton-outline-primary" name="adjustPrice">
                    </form>
                </div>
                <?php
                    return;
                }
                ?>
            <h1>ช่วงค่าขนส่งตามน้ำหนักต่าง ๆ</h1>
            <div style="padding: 25px 25px 25px 25px;">
                <div style="display: flex; gap: 10px;">
                    <button class="button botton-outline-primary" style="width: 50%;" onclick="window.location.href='admin.php?page=woocommerce-custom-shipping-settings&option=default&newProfile'">➕ เพิ่มช่วงค่าขนส่งใหม่</button>
                    <button class="button botton-outline-primary" style="width: 50%;" onclick="window.location.href='admin.php?page=woocommerce-custom-shipping-settings&option=default&adjustPrice'">➕ ปรับราคา</button>
                </div>
                <br>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ตั้งแต่น้ำหนัก (กรัม)</th>
                            <th>ถึงน้ำหนัก (กรัม)</th>
                            <th>ค่าขนส่ง (บาท)</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $profiles = get_option('default_shipping_pricing', array());
                        foreach($profiles as $profile) {
                            $id = $profile['id'];
                        ?>
                        <tr>
                            <td><?=$id;?></td>
                            <td><?=$profile['start'];?></td>
                            <td><?=$profile['end'];?></td>
                            <td><?=$profile['cost'];?></td>
                            <td>
                                <button class="button button-outline-primary" onclick="window.location.href='admin.php?page=woocommerce-custom-shipping-settings&option=default&edit=<?=$id?>'">แก้ไข</button>
                                <button class="button button-outline-danger" onclick="if(confirm('คุณต้องการลบช่วงราคานี้หรือไม่ ?')) { window.location.href='admin.php?page=woocommerce-custom-shipping-settings&option=default&delete=<?=$id?>'; }">ลบ</button>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
            } else if(isset($_GET['option']) && $_GET['option'] == "ems") {
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
                        <br>
                        <br>
                        <button class="button button-primary" style="width: 100%;" type="submit">บันทึกการเปลี่ยนแปลง</button>
                    </div>
                </form>
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
                            <h2>ไปรษณีย์ไทย (EMS)</h2>
                            <p>เปิดใช้งานขนส่งไปรษณีย์ไทย (EMS)</p>
                            <select name="enable_ems" id="">
                                <option value="yes" <?php selected(get_option('enable_ems'), 'yes') ?>>ใช่</option>
                                <option value="no" <?php selected(get_option('enable_ems'), 'no') ?>>ไม่ใช่</option>
                            </select>
                            <p>หากเปิดใช้งาน จะมีตัวเลือก EMS ปรากฏในหน้าชำระเงินตามน้ำหนักสินค้า</p>
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
            } else if(isset($_GET['option']) && $_GET['option'] == "free_shipping") {
                $remote_areas_cost = get_option('free_shipping_remote_areas_cost', 100);
             ?>
            <h1>สินค้าโปรโมชั่นส่งฟรี</h1>
            <div style="padding: 25px 25px 25px 25px;">
                <form action="options.php" method="post">
                    <?php
                        settings_fields('free_shipping_settings_group');
                    ?>
                    <label for="free_shipping_products_list">Slugs สินค้าส่งฟรี: </label>
                    <input type="text" name="free_shipping_products_list" id="free_shipping_products_list" style="width: 100%;" value="<?=get_option('free_shipping_products_list')?>">
                    <div style="height: 400px; overflow: auto;">
                        <?php
                        $args = array(
                            'status'  => 'publish',
                            'limit'   => -1, // -1 pulls all items
                            'orderby' => 'name',
                            'order'   => 'ASC',
                        );

                        $all_products = wc_get_products($args);

                        $free_shipping_products_list = explode(",", get_option('free_shipping_products_list', ''));

                        foreach ($all_products as $product) {
                            ?>
                            
                            <?php
                            if ($product->get_type() == 'variable') {
                                $variations = $product->get_available_variations();
                                
                                foreach ($variations as $variation) {
                                    $variation_id = $variation['variation_id'];
                                    $is_checked = false;

                                    if (!empty($free_shipping_products_list) && in_array($variation_id, $free_shipping_products_list)) {
                                        $is_checked = true;
                                    }
        
                                    $attribute_labels = [];
                                    foreach ($variation['attributes'] as $key => $value) {
                                        $attr_name = str_replace('attribute_', '', $key);
                                        $attr_name = wc_attribute_label($attr_name); 
                                        $attribute_labels[] = $attr_name . ': ' . ucfirst($value);
                                    }
                                    $attributes_text = implode(', ', $attribute_labels);
                                    ?>
                                    <p>
                                        <input 
                                            type="checkbox" 
                                            name="free_shipping_products[<?php echo esc_attr($variation_id); ?>]" 
                                            value="<?php echo esc_attr($variation_id); ?>" 
                                            <?php checked($is_checked, true); ?> 
                                            onchange="initProduct();"
                                        />
                                        <?php echo esc_html($product->get_title()) . ' (' . esc_html(urldecode($attributes_text)) . ')'; ?>
                                    </p>
                                    <?php
                                }
                            } else {
                                $is_checked = false;
                                if (!empty($free_shipping_products_list) && in_array($product->get_id(), $free_shipping_products_list)) {
                                    $is_checked = true;
                                }
                            ?>
                                <p><input type="checkbox" name="free_shipping_products[<?=$product->get_id()?>]" value="<?=$product->get_id()?>" <?php if($is_checked) { echo "checked"; } ?> onchange="initProduct();" /><?php echo esc_html($product->get_title()); ?></p>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                    </div>
                    <br>
                    <label for="free_shipping_remote_areas_cost">ค่าธรรมเนียมเพิ่มเติมสำหรับพื้นที่ห่างไกล: </label>
                    <input type="number" name="free_shipping_remote_areas_cost" id="free_shipping_remote_areas_cost" value="<?=get_option('free_shipping_remote_areas_cost', 100)?>"> บาท
                    <br>
                    <br>
                    <input type="submit" value="บันทึกการเปลี่ยนแปลง" class="button button-primary" style="width: 100%;">
                </form>
                <script>
                    function initProduct() {
                        const checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');
                        let items = []
                        checkedBoxes.forEach(item => {
                            items.push(item.value)
                        })
                        document.getElementsByName('free_shipping_products_list')[0].value = items.join(",");
                    }
                </script>
            </div>
            <?php
            } else if(isset($_GET['option']) && $_GET['option'] == "weight_threshold_shipping") {
            ?>
            <h1>คิดค่าขนส่งตามเกณฑ์น้ำหนัก</h1>
            <div style="padding: 25px 25px 25px 25px;">
                <p>ตั้งค่าให้สินค้าที่มีน้ำหนักเกิน เกณฑ์ที่กำหนดให้คิดค่าส่งแต่ละชิ้น ส่วนสินค้าที่เหลือจะรวมน้ำหนักแล้วคิดค่าส่งรวม</p>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('weight_threshold_shipping_group');
                    ?>
                    <label for="weight_threshold_enabled">เปิดใช้งาน:</label>
                    <select name="weight_threshold_enabled" id="weight_threshold_enabled">
                        <option value="yes" <?php selected(get_option('weight_threshold_enabled'), 'yes') ?>>ใช่</option>
                        <option value="no" <?php selected(get_option('weight_threshold_enabled'), 'no') ?>>ไม่ใช่</option>
                    </select>
                    <br><br>

                    <label for="weight_threshold_value">กำหนดเกณฑ์น้ำหนัก (กรัม):</label><br>
                    <input type="number" name="weight_threshold_value" id="weight_threshold_value" value="<?php echo esc_attr(get_option('weight_threshold_value', 5000)); ?>" style="width: 500px;" /> กรัม
                    <p style="color: #666; font-size: 12px;">สินค้าที่มีน้ำหนักมากกว่าค่านี้ จะคิดค่าส่งแต่ละชิ้น ส่วนสินค้าอื่นจะรวมน้ำหนักแล้วคิดค่าส่งรวม</p>
                    <br>

                    <button class="button button-primary" style="width: 100%;" type="submit">บันทึกการเปลี่ยนแปลง</button>
                </form>
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
    
    register_setting('weight_threshold_shipping_group', 'weight_threshold_enabled');
    register_setting('weight_threshold_shipping_group', 'weight_threshold_value');
    
    register_setting('shipping_settings_group', 'kerry_express_fee');
    register_setting('shipping_settings_group', 'enable_ems');
    register_setting('shipping_settings_group', 'remote_surcharge');
    register_setting('shipping_settings_group', 'remote_areas_list');
    register_setting('shipping_settings_group', 'no_discount_self_pickup');
    register_setting('shipping_settings_group', 'enable_kerry_express');

    register_setting('shipping_settings_group', 'enable_category_based_shipping_cost');
    register_setting('category_shipping_settings_group', 'category_based_shipping_list');

    register_setting('free_shipping_settings_group', 'free_shipping_products_list');
    register_setting('free_shipping_settings_group', 'free_shipping_remote_areas_cost');

    register_setting( 'default_shipping_settings_group', 'default_shipping_pricing' );
}

add_filter('woocommerce_package_rates', 'combined_shipping_methods', 10, 2);
function combined_shipping_methods($rates, $package)
{
    if (!is_array($rates)) {
        return $rates;
    }

    if (!function_exists('WC') || !WC()->cart || empty($package['contents'])) {
        return $rates;
    }

    $total_weight = (float) WC()->cart->get_cart_contents_weight();
    $weight_unit = get_option('woocommerce_weight_unit', 'kg');

    if ($weight_unit === 'kg') {
        $total_weight_grams = $total_weight * 1000;
    } elseif ($weight_unit === 'g' || $weight_unit === 'gram') {
        $total_weight_grams = $total_weight;
    } elseif ($weight_unit === 'lbs' || $weight_unit === 'lb') {
        $total_weight_grams = $total_weight * 453.59237;
    } else {
        $total_weight_grams = $total_weight * 1000;
    }

    if ($total_weight_grams <= 1000) {
        $packing_fee = (float) get_option('packing_fee_0_1');
    } elseif ($total_weight_grams <= 5000) {
        $packing_fee = (float) get_option('packing_fee_1_5');
    } elseif ($total_weight_grams <= 20000) {
        $packing_fee = (float) get_option('packing_fee_5_20');
    } elseif ($total_weight_grams <= 30000) {
        $packing_fee = (float) get_option('packing_fee_20_30');
    } else {
        $packing_fee = (float) get_option('packing_fee_30_plus');
    }

    $destination_zip = isset($package['destination']['postcode']) ? $package['destination']['postcode'] : '';
    $remote_areas_raw = get_option('remote_areas_list', '');
    $remote_areas = array_filter(array_map('trim', preg_split('/\r\n|\n|,/', $remote_areas_raw) ?: array()));
    $is_remote = !empty($destination_zip) && in_array($destination_zip, $remote_areas, true);

    $free_shipping_products_raw = get_option('free_shipping_products_list', '');
    $free_shipping_products = array_filter(array_map('intval', array_map('trim', explode(',', $free_shipping_products_raw))));

    $default_pricing = get_option('default_shipping_pricing', array());
    $default_shipping_total = 0;
    $has_non_free_product = false;
    $has_free_shipping_product = false;

    // Weight threshold shipping feature
    $weight_threshold_enabled = get_option('weight_threshold_enabled', 'no') === 'yes';
    $weight_threshold_value = (float) get_option('weight_threshold_value', 5000);
    $threshold_shipping_total = 0;
    $normal_shipping_total = 0;
    $normal_weight_total = 0;
    $threshold_item_count = 0;

    if (WC()->cart) {
        foreach (WC()->cart->get_cart() as $cart_item) {
            $product_id = isset($cart_item['product_id']) ? $cart_item['product_id'] : 0;
            $variation_id = isset($cart_item['variation_id']) ? $cart_item['variation_id'] : 0;
            $item_id = $variation_id > 0 ? $variation_id : $product_id;

            if (in_array($item_id, $free_shipping_products, true)) {
                $has_free_shipping_product = true;
                continue;
            }

            $has_non_free_product = true;

            if (!empty($default_pricing)) {
                $quantity = isset($cart_item['quantity']) ? (int) $cart_item['quantity'] : 1;
                $product = $cart_item['data']; 
                $product_weight = $product ? (float) $product->get_weight() : 0;

                if ($weight_unit === 'kg') {
                    $product_weight_grams = $product_weight * 1000;
                } elseif ($weight_unit === 'g' || $weight_unit === 'gram') {
                    $product_weight_grams = $product_weight;
                } elseif ($weight_unit === 'lbs' || $weight_unit === 'lb') {
                    $product_weight_grams = $product_weight * 453.59237;
                } else {
                    $product_weight_grams = $product_weight * 1000;
                }

                // Check if weight threshold shipping is enabled
                if ($weight_threshold_enabled && $product_weight_grams > $weight_threshold_value) {
                    // Calculate shipping for each item individually (over threshold)
                    $threshold_item_count += $quantity;
                    foreach ($default_pricing as $profile) {
                        $start = (float) $profile['start'];
                        $end = (float) $profile['end'];

                        if ($product_weight_grams >= $start && $product_weight_grams <= $end) {
                            $threshold_shipping_total += ((float) $profile['cost']) * $quantity;
                            break;
                        }
                    }
                } else {
                    // Add to normal weight pool (normal items)
                    $normal_weight_total += $product_weight_grams * $quantity;
                }
            }
        }

        // Calculate shipping for normal items (by total weight)
        if ($normal_weight_total > 0) {
            foreach ($default_pricing as $profile) {
                $start = (float) $profile['start'];
                $end = (float) $profile['end'];

                if ($normal_weight_total >= $start && $normal_weight_total <= $end) {
                    $normal_shipping_total = (float) $profile['cost'];
                    break;
                }
            }
        }

        // Combine shipping totals
        $default_shipping_total = $threshold_shipping_total + $normal_shipping_total;

        // Store threshold shipping info in session for cart display
        if ($weight_threshold_enabled && $threshold_item_count > 0) {
            WC()->session->set('threshold_shipping_breakdown', array(
                'threshold_shipping' => $threshold_shipping_total,
                'normal_shipping' => $normal_shipping_total,
                'total_shipping' => $default_shipping_total,
                'threshold_item_count' => $threshold_item_count
            ));
        }
    }

    if ($has_non_free_product) {
        $remote_surcharge = $is_remote ? (float) get_option('remote_surcharge', 60) : 0;
    } elseif ($has_free_shipping_product) {
        $remote_surcharge = $is_remote ? (float) get_option('free_shipping_remote_areas_cost', 100) : 0;
    } else {
        $remote_surcharge = 0;
    }

    if (!$has_non_free_product) {
        $packing_fee = 0;
        $remote_surcharge = 0;
    }

    $auto_id = 'custom_shipping_auto';
    $auto_cost = $default_shipping_total + $packing_fee + $remote_surcharge;
    $auto_rate = new WC_Shipping_Rate($auto_id, 'ค่าจัดส่ง (เลือกอัตโนมัติ)', $auto_cost);
    $new_rates[$auto_id] = $auto_rate;

    if (get_option('enable_kerry_express') == 'yes') {
        $kerry_id = 'custom_shipping_kerry';
        $kerry_cost = $default_shipping_total + $packing_fee + $remote_surcharge + (float) get_option('kerry_express_fee', 30);
        $kerry_rate = new WC_Shipping_Rate($kerry_id, 'Kerry Express', $kerry_cost);
        $new_rates[$kerry_id] = $kerry_rate;
    }

    if (get_option('enable_ems') == 'yes' && $total_weight_grams <= 20000) {
        $w = $total_weight_grams;
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
            $extra_kg = ceil(($w - 6000) / 1000);
            $ems_cost = (float) get_option('ems_fee_p15', 140) + ($extra_kg * (float) get_option('ems_fee_after_6kg', 35));
        }

        $ems_total = $ems_cost + $packing_fee + $remote_surcharge;
        $ems_rate = new WC_Shipping_Rate('custom_shipping_ems', 'ไปรษณีย์ไทย (EMS)', $ems_total);
        $new_rates[$ems_rate->get_id()] = $ems_rate;
    }

    if (get_option('enable_self_pickup', 'no') == 'yes') {
        $pickup_id = 'custom_shipping_selfpickup';
        $self_pickup_rate = new WC_Shipping_Rate($pickup_id, 'รับเองหน้าร้าน', 0);
        $new_rates[$pickup_id] = $self_pickup_rate;
    }

    if (get_option('no_discount_self_pickup', 'yes') == 'yes') {
        if (!function_exists('disable_discounts_for_self_pickup')) {
            add_action('woocommerce_before_calculate_totals', 'disable_discounts_for_self_pickup', 20);

            function disable_discounts_for_self_pickup($cart) {
                if (is_admin() && !defined('DOING_AJAX')) return;

                $chosen_methods = WC()->session->get('chosen_shipping_methods');
                $chosen_shipping = isset($chosen_methods[0]) ? $chosen_methods[0] : '';

                if (strpos($chosen_shipping, 'selfpickup') !== false) {
                    if (!empty($cart->get_applied_coupons())) {
                        $cart->remove_coupons();
                        wc_clear_notices();
                        wc_add_notice('การรับสินค้าเองหน้าร้านไม่สามารถใช้ร่วมกับคูปองส่วนลดได้', 'notice');
                    }
                }
            }
        }
    }

    return $new_rates;
}

function custom_free_shipping_product_ids()
{
    $raw = get_option('free_shipping_products_list', '');
    return array_filter(array_map('intval', array_map('trim', explode(',', $raw))));
}

function custom_free_shipping_variation_option_name($term, $variation)
{
    $free_shipping_ids = custom_free_shipping_product_ids();

    $variation_id = 0;
    if (is_object($variation)) {
        if (method_exists($variation, 'get_id')) {
            $variation_id = $variation->get_id();
        } elseif (isset($variation->variation_id)) {
            $variation_id = $variation->variation_id;
        }
    }

    if ($variation_id && in_array($variation_id, $free_shipping_ids, true)) {
        $term .= ' <span class="custom-free-shipping-label">ส่งฟรี</span>';
    }

    return $term;
}
add_filter('woocommerce_variation_option_name', 'custom_free_shipping_variation_option_name', 10, 2);

function custom_free_shipping_product_page_badge_markup()
{
    if (!is_product()) {
        return;
    }
    global $product;
    if (!is_object($product)) {
        return;
    }
    $free_shipping_ids = custom_free_shipping_product_ids();
    if (empty($free_shipping_ids)) {
        return;
    }
    $product_id = $product->get_id();
    $has_free_simple = in_array($product_id, $free_shipping_ids, true);
    $has_free_variation = false;
    if ($product->is_type('variable')) {
        foreach ($product->get_children() as $variation_id) {
            if (in_array($variation_id, $free_shipping_ids, true)) {
                $has_free_variation = true;
                break;
            }
        }
    }
    if (!$has_free_simple && !$has_free_variation) {
        return;
    }

    ?>
    <div id="custom-free-shipping-badge">
        <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 512 512" width="30" height="30" fill="#2a7d2a"><ellipse cx="187.06" cy="360.52" rx="40.45" ry="35.15" transform="translate(-195.6 224.35) rotate(-42.99)"/><ellipse cx="403.28" cy="360.52" rx="40.45" ry="35.15" transform="translate(-137.54 371.8) rotate(-42.99)"/><rect x="5.41" y="182.71" width="34.15" height="34.15" rx="17.08"/><path d="M346.48,356.94c6.07-30,34.41-53.84,64.87-53.84,29.33,0,50.47,22.11,49.89,50.54,31.72.83,40.55-40.94,40.55-40.94,2.67-11.68,6.66-34.45,10-56.8a37.76,37.76,0,0,0-2.43-20.42A350.18,350.18,0,0,0,479.42,180c-11-16.41-29.45-26.13-50.51-26.48-12.56-.2-24.89-.32-34.2-.32l-.08-.08c-1.2-20.08-15.84-35.46-36.39-37.3-13.37-1.19-66.33-2.06-91.75-2.06-10.1,0-24.59.14-39.25.38v-.06h-.12l0,0,0,0H50.86a17.07,17.07,0,0,0-17.08,17.08v.08a17,17,0,0,0,17,17l35.53,0h0a17,17,0,0,1,15.75,16.94v.09A17.08,17.08,0,0,1,85,182.4H66.78a17.07,17.07,0,0,0-17.07,17.07h0a17.07,17.07,0,0,0,17.07,17.08H85a17.08,17.08,0,0,1,17.08,17.08h0A17.08,17.08,0,0,1,85,250.71H16.84A17.08,17.08,0,0,0-.24,267.78h0a17.08,17.08,0,0,0,17.08,17.08H85a17.08,17.08,0,0,1,17.08,17.08h0A17.08,17.08,0,0,1,85,319H63.92a17.07,17.07,0,0,0-17.07,17.07h0a17.08,17.08,0,0,0,17.07,17.08l67.26-.05,5.59-13.93h0c11.45-21.12,34.23-36.08,58.35-36.08,30.47,0,52.11,23.86,49.73,53.88H346.47M177.07,190.35H154.15a.1.1,0,0,0-.09.08l-2.11,15a.08.08,0,0,0,.08.1h17.06a5.63,5.63,0,0,1,5.7,6.63,7.89,7.89,0,0,1-7.56,6.63H150.15a.09.09,0,0,0-.09.07L147,240.65a7.89,7.89,0,0,1-7.56,6.63,5.62,5.62,0,0,1-5.69-6.63l7.94-56.52a8.36,8.36,0,0,1,8-7h29.23a5.62,5.62,0,0,1,5.69,6.63A7.87,7.87,0,0,1,177.07,190.35Zm61.26-8.72a15.55,15.55,0,0,1,4.8,8,27.57,27.57,0,0,1,.4,10.7A29.23,29.23,0,0,1,237,215.38a21,21,0,0,1-8.07,5.87.08.08,0,0,0,0,.11l5.68,16.39c1.53,4.4-2.4,9.53-7.29,9.53H227a5.59,5.59,0,0,1-5.44-3.74l-6.87-20a.11.11,0,0,0-.09-.05H200a.1.1,0,0,0-.09.07l-2.4,17.08a7.89,7.89,0,0,1-7.56,6.63,5.62,5.62,0,0,1-5.69-6.63l7.94-56.52a8.36,8.36,0,0,1,8-7h22.6S232.72,176.81,238.33,181.63Zm62.2,1.66a7.36,7.36,0,0,1-7.06,6.19H267.18a.1.1,0,0,0-.09.07L265,204.39a.08.08,0,0,0,.08.1h20.42a5.25,5.25,0,0,1,5.32,6.19,7.36,7.36,0,0,1-7.06,6.19H263.33a.1.1,0,0,0-.09.07l-2.51,17.86a.1.1,0,0,0,.09.1h26.27a5.25,5.25,0,0,1,5.32,6.19h0a7.36,7.36,0,0,1-7.06,6.19h-32.6a6,6,0,0,1-6-7l7.89-56.12a8.36,8.36,0,0,1,8-7h32.59A5.25,5.25,0,0,1,300.53,183.29Zm54.61,0a7.36,7.36,0,0,1-7.06,6.19H321.79a.1.1,0,0,0-.09.07l-2.08,14.84a.08.08,0,0,0,.08.1h20.42a5.25,5.25,0,0,1,5.32,6.19,7.36,7.36,0,0,1-7.06,6.19H317.94a.1.1,0,0,0-.09.07l-2.51,17.86a.1.1,0,0,0,.09.1H341.7a5.25,5.25,0,0,1,5.32,6.19h0a7.36,7.36,0,0,1-7.06,6.19h-32.6a6,6,0,0,1-6-7l7.89-56.12a8.36,8.36,0,0,1,8-7h32.59A5.25,5.25,0,0,1,355.14,183.29Zm38-6.41c8.93,0,20.49.12,32.09.31,14.06.23,26.36,6.69,33.74,17.71A322.39,322.39,0,0,1,480.18,232c3.3,6.72-2.77,15.47-10.72,15.47H384.21Z"/><path d="M217.45,211.12h-15.8l3-21.64h15.79s11.59-.75,9.69,10.82C230.18,200.3,228.85,211.12,217.45,211.12Z"/></svg> 
        <span style="
        color: #2a7d2a; 
        display: inline-flex;
        color: #2a7d2a;
        height: 32px;
        padding: 0 0 0 10px;
        vertical-align: bottom;
        font-weight: 500;
        font-size: 18px;
        ">
            สินค้าโปรโมชั่น ส่งฟรีทั่วประเทศ
        </span> 
    </div>
    <?php
}
?>

<?php
add_action('woocommerce_single_product_summary', 'custom_free_shipping_product_page_badge_markup', 6);

function custom_free_shipping_product_page_script()
{
    if (!is_product()) {
        return;
    }

    global $product;
    if (!is_object($product)) {
        return;
    }

    $free_shipping_ids = custom_free_shipping_product_ids();
    $free_ids_json = json_encode(array_values($free_shipping_ids));
    $product_id = $product->get_id();
    $has_free_simple = in_array($product_id, $free_shipping_ids, true);
    $has_free_variation = false;

    if ($product->is_type('variable')) {
        foreach ($product->get_children() as $variation_id) {
            if (in_array($variation_id, $free_shipping_ids, true)) {
                $has_free_variation = true;
                break;
            }
        }
    }

    if (!$has_free_simple && !$has_free_variation) {
        return;
    }

    echo '<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var freeIds = ' . $free_ids_json . ';
            var productId = ' . (int)$product_id . ';
            var badge = document.getElementById("custom-free-shipping-badge");
            if (!badge) {
                return;
            }

            function showBadge() {
                badge.style.display = "inline-block";
            }

            function hideBadge() {
                badge.style.display = "none";
            }

            function updateBadgeForVariation(variation) {
                if (!variation || !variation.variation_id) {
                    hideBadge();
                    return;
                }
                if (freeIds.indexOf(Number(variation.variation_id)) !== -1) {
                    showBadge();
                } else {
                    hideBadge();
                }
            }

            function updateBadgeForCurrentSelection() {
                var form = document.querySelector("form.variations_form");
                if (!form) {
                    hideBadge();
                    return;
                }
                var variationIdInput = form.querySelector("input[name=\"variation_id\"]");
                if (variationIdInput && variationIdInput.value) {
                    updateBadgeForVariation({ variation_id: parseInt(variationIdInput.value, 10) });
                } else {
                    hideBadge();
                }
            }

            if (freeIds.indexOf(productId) !== -1) {
                showBadge();
                return;
            }

            var form = document.querySelector("form.variations_form");
            if (!form) {
                hideBadge();
                return;
            }

            if (typeof jQuery !== "undefined") {
                jQuery(form).on("found_variation", function(event, variation) {
                    updateBadgeForVariation(variation);
                });
                jQuery(form).on("reset_data", function() {
                    hideBadge();
                });
            }

            form.addEventListener("change", function() {
                setTimeout(updateBadgeForCurrentSelection, 10);
            });

            updateBadgeForCurrentSelection();
        });
    </script>';
}
add_action('wp_footer', 'custom_free_shipping_product_page_script');

/**
 * บันทึกชื่อบริษัทขนส่งลงใน Order Note หลังจากลูกค้าสั่งซื้อ
 */
add_action('woocommerce_checkout_update_order_meta', 'save_shipping_label_to_order_note', 10, 2);
function save_shipping_label_to_order_note($order_id, $data)
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

/**
 * Display weight threshold shipping breakdown on cart page
 */
add_action('woocommerce_cart_totals_after_shipping', 'display_threshold_shipping_breakdown', 10);
function display_threshold_shipping_breakdown()
{
    if (!is_cart()) {
        return;
    }

    $weight_threshold_enabled = get_option('weight_threshold_enabled', 'no') === 'yes';
    if (!$weight_threshold_enabled) {
        return;
    }

    $breakdown = WC()->session->get('threshold_shipping_breakdown', array());
    if (empty($breakdown) || $breakdown['threshold_item_count'] <= 0) {
        return;
    }

    ?>
    <tr>
        <td colspan="2">
            <div>
                <div style="padding: 10px 30px; color: #333;">
                    <strong>รายละเอียดค่าขนส่ง (เกณฑ์น้ำหนัก)</strong><br>
                    - สินค้าที่มีน้ำหนักเกิน: <strong><?php echo number_format($breakdown['threshold_item_count']); ?></strong> ชิ้น<br>
                    - ค่าส่งสินค้าชิ้นใหญ่มากกว่า <?=number_format(get_option('weight_threshold_value') / 1000, 0)?> กิโลกรัม: <strong style="color: #c41e3a;"><?php echo wc_price($breakdown['threshold_shipping']); ?></strong><br>
                    <?php if ($breakdown['normal_shipping'] > 0): ?>
                    - ค่าส่งสินค้าอื่น: <strong><?php echo wc_price($breakdown['normal_shipping']); ?></strong><br>
                    <?php endif; ?>
                </div>
            </div>
        </td>
    </tr>
    <?php
}