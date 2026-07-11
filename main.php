<?php
/**
 * Plugin Name: Advance Shipping For Woocommerce
 * Description: ระบบกำหนดค่าบริการขนส่ง และเลือกบริษัทขนส่งเองสำหรับลูกค้า สำหรับสินค้าบน WooCommerce
 * Author: Jirakit Pawnsakunrungrot
 * Author URI: https://www.linkedin.com/in/sunny-jirakit
 * Plugin URI: https://github.com/sunny420x/woocommerce-advance-shipping
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
        <span style="padding: 60px 10px 60px 40px;float: left;font-size: 60px;">🚚</span>
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

    register_setting( 'default_shipping_settings_group', 'default_shipping_pricing' );
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

    // Keep any non-Weight-Based rates intact
    foreach ($rates as $rate_id => $rate) {
        if (strpos($rate_id, 'wbs') === false) {
            $new_rates[$rate_id] = $rate;
        }
    }

    // Build our own shipping rates so this plugin does NOT depend on Weight Based Shipping
    $default_pricing = get_option('default_shipping_pricing', array());
    $default_shipping_total = 0;

    if (!empty($default_pricing) && WC()->cart) {
        $weight_unit = get_option('woocommerce_weight_unit', 'kg');

        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];

            $product = wc_get_product($product_id);
            $product_weight = $product ? (float) $product->get_weight() : 0;

            // Convert product weight to grams based on store settings
            if ($weight_unit === 'kg') {
                $product_weight_grams = $product_weight * 1000;
            } elseif ($weight_unit === 'g' || $weight_unit === 'gram') {
                $product_weight_grams = $product_weight;
            } elseif ($weight_unit === 'lbs' || $weight_unit === 'lb') {
                $product_weight_grams = $product_weight * 453.59237;
            } else {
                $product_weight_grams = $product_weight * 1000;
            }

            // Find matching pricing profile for this product weight
            foreach ($default_pricing as $profile) {
                $start = (float) $profile['start'];
                $end = (float) $profile['end'];

                if ($product_weight_grams >= $start && $product_weight_grams <= $end) {
                    $default_shipping_total += ((float) $profile['cost']) * $quantity;
                    break; // assume non-overlapping ranges
                }
            }
        }
    }

    // Main auto-selected shipping rate (our replacement for WBS)
    $auto_id = 'custom_shipping_auto';
    $auto_cost = $default_shipping_total + $packing_fee + $remote_surcharge;
    $auto_rate = new WC_Shipping_Rate( $auto_id, 'ค่าจัดส่ง (เลือกอัตโนมัติ)', $auto_cost );
    $new_rates[$auto_id] = $auto_rate;

    // Kerry Express option
    if(get_option("enable_kerry_express") == "yes") {
        $kerry_id = 'custom_shipping_kerry';
        $kerry_cost = $default_shipping_total + $packing_fee + $remote_surcharge + (float) get_option('kerry_express_fee', 30);
        $kerry_rate = new WC_Shipping_Rate( $kerry_id, 'Kerry Express', $kerry_cost );
        $new_rates[$kerry_id] = $kerry_rate;
    }

    // Thailand Post EMS (limit 20kg)
    if ($total_weight <= 20000) {
        $w = $total_weight; // grams
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
            $ems_cost = (float) get_option('ems_fee_p15', 140) + ($extra_kg * get_option('ems_fee_after_6kg', 35));
        }

        $ems_total = $ems_cost + $packing_fee + $remote_surcharge;
        $ems_rate = new WC_Shipping_Rate( $rate_id . '_ems_custom', 'ไปรษณีย์ไทย (EMS)', $ems_total );
        $new_rates[$ems_rate->get_id()] = $ems_rate;
    }

    // Self pickup
    if(get_option('enable_self_pickup', 'no') == 'yes') {
        $pickup_id = 'custom_shipping_selfpickup';
        $self_pickup_rate = new WC_Shipping_Rate( $pickup_id, 'รับเองหน้าร้าน', 0 );
        $new_rates[$pickup_id] = $self_pickup_rate;
    }

    // If self-pickup should disallow coupons, ensure we register the hook once
    if ( get_option('no_discount_self_pickup', 'yes') == 'yes' ) {
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