<?php

use Illuminate\Database\Seeder;

class SelectsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('selects')->delete();
        
        \DB::table('selects')->insert(array (
            0 => 
            array (
                'id' => 1,
                'column' => 'Business Unit',
                'option' => 'EAS',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            1 => 
            array (
                'id' => 2,
                'column' => 'Business Unit',
                'option' => 'EEC',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            2 => 
            array (
                'id' => 3,
                'column' => 'Business Unit',
                'option' => 'ENCOM',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            3 => 
            array (
                'id' => 4,
                'column' => 'Business Unit',
                'option' => 'NASIAM',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            4 => 
            array (
                'id' => 5,
                'column' => 'Business Unit',
                'option' => 'SFM',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            5 => 
            array (
                'id' => 6,
                'column' => 'Business Unit',
                'option' => 'SUMET',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            6 => 
            array (
                'id' => 7,
                'column' => 'Business Unit',
                'option' => 'WORAKORN',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            7 => 
            array (
                'id' => 8,
                'column' => 'Business Unit',
                'option' => 'มาบข่า',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            8 => 
            array (
                'id' => 9,
                'column' => 'Fleet',
                'option' => 'EAS',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            9 => 
            array (
                'id' => 10,
                'column' => 'Fleet',
            'option' => 'EAS (รอซ่อมที่มาบข่า)',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            10 => 
            array (
                'id' => 11,
                'column' => 'Fleet',
                'option' => 'จ๊ะโอ๋',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            11 => 
            array (
                'id' => 12,
                'column' => 'Fleet',
                'option' => 'จ๋ะโอ๋',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            12 => 
            array (
                'id' => 13,
                'column' => 'Fleet',
                'option' => 'ทะเลไทย 2/33',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            13 => 
            array (
                'id' => 14,
                'column' => 'Fleet',
                'option' => 'บุญมี',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            14 => 
            array (
                'id' => 15,
                'column' => 'Fleet',
                'option' => 'มาบข่า',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            15 => 
            array (
                'id' => 16,
                'column' => 'Fleet',
                'option' => 'ไม่มีตัวตน',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            16 => 
            array (
                'id' => 17,
                'column' => 'Fleet',
                'option' => 'รถส่วนตัว',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            17 => 
            array (
                'id' => 18,
                'column' => 'Fleet',
                'option' => 'สนง.อุตสาหกรรมเช่า',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            18 => 
            array (
                'id' => 19,
                'column' => 'Fleet',
                'option' => 'แหลมฉบัง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            19 => 
            array (
                'id' => 20,
                'column' => 'Fleet',
                'option' => 'อจ.ต้อม',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            20 => 
            array (
                'id' => 21,
                'column' => 'จังหวัด',
                'option' => 'กรุงเทพมหานคร',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            21 => 
            array (
                'id' => 22,
                'column' => 'จังหวัด',
                'option' => 'ฉะเชิงเทรา',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            22 => 
            array (
                'id' => 23,
                'column' => 'จังหวัด',
                'option' => 'ระยอง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            23 => 
            array (
                'id' => 24,
                'column' => 'เชื้อเพลิง',
                'option' => 'NGV',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            24 => 
            array (
                'id' => 25,
                'column' => 'เชื้อเพลิง',
                'option' => 'ดีเซล',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            25 => 
            array (
                'id' => 26,
                'column' => 'เชื้อเพลิง',
                'option' => 'ไม่ใช้เชื้อเพลิง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            26 => 
            array (
                'id' => 27,
                'column' => 'ประเภทรถ',
                'option' => 'tank',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            27 => 
            array (
                'id' => 28,
                'column' => 'ประเภทรถ',
                'option' => 'กึ่งพ่วง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            28 => 
            array (
                'id' => 29,
                'column' => 'ประเภทรถ',
                'option' => 'กึ่งพ่วงตู้คอนเทนเนอร์',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            29 => 
            array (
                'id' => 30,
                'column' => 'ประเภทรถ',
                'option' => 'กึ่งพ่วงน้ำมัน',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            30 => 
            array (
                'id' => 31,
                'column' => 'ประเภทรถ',
                'option' => 'กึ่งพ่วงบรรทุกเฮกเซน',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            31 => 
            array (
                'id' => 32,
                'column' => 'ประเภทรถ',
                'option' => 'กึ่งพ่วงเมทานอล',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            32 => 
            array (
                'id' => 33,
                'column' => 'ประเภทรถ',
                'option' => 'กึ่งพวงรับเบอร์โซลเว้นท์',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            33 => 
            array (
                'id' => 34,
                'column' => 'ประเภทรถ',
                'option' => 'เคมี',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            34 => 
            array (
                'id' => 35,
                'column' => 'ประเภทรถ',
                'option' => 'เต็ม',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            35 => 
            array (
                'id' => 36,
                'column' => 'ประเภทรถ',
                'option' => 'มอเตอร์ไซด์',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            36 => 
            array (
                'id' => 37,
                'column' => 'ประเภทรถ',
                'option' => 'โม',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            37 => 
            array (
                'id' => 38,
                'column' => 'ประเภทรถ',
                'option' => 'รถยนต์',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            38 => 
            array (
                'id' => 39,
                'column' => 'ประเภทรถ',
            'option' => 'ลากจูง (โม)',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            39 => 
            array (
                'id' => 40,
                'column' => 'ประเภทรถ',
                'option' => 'หัวลาก',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            40 => 
            array (
                'id' => 41,
                'column' => 'ประเภทรถ',
                'option' => 'หัวลากวัตถุอันตราย',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            41 => 
            array (
                'id' => 42,
                'column' => 'ประเภทรถ',
                'option' => 'หางพ่วงตู้บรรทุกแห้ง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            42 => 
            array (
                'id' => 43,
                'column' => 'ยี่ห้อ',
                'option' => 'HINO',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            43 => 
            array (
                'id' => 44,
                'column' => 'ยี่ห้อ',
                'option' => 'ISUZU',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            44 => 
            array (
                'id' => 45,
                'column' => 'ยี่ห้อ',
                'option' => 'SCANIA',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            45 => 
            array (
                'id' => 46,
                'column' => 'ยี่ห้อ',
                'option' => 'TOYOTA',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            46 => 
            array (
                'id' => 47,
                'column' => 'ยี่ห้อ',
                'option' => 'VOLVO',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            47 => 
            array (
                'id' => 48,
                'column' => 'ตำแหน่งเลขตัวรถ',
                'option' => 'กลางซ้าย',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            48 => 
            array (
                'id' => 49,
                'column' => 'ตำแหน่งเลขตัวรถ',
                'option' => 'หน้าขวา',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            49 => 
            array (
                'id' => 50,
                'column' => 'ตำแหน่งเลขตัวรถ',
                'option' => 'หน้าซ้าย',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            50 => 
            array (
                'id' => 51,
                'column' => 'ตำแหน่งเลขตัวรถ',
                'option' => 'หลังขวา',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            51 => 
            array (
                'id' => 52,
                'column' => 'ตำแหน่งเลขเครื่องยนต์',
                'option' => 'ขวาเครื่อง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            52 => 
            array (
                'id' => 53,
                'column' => 'ตำแหน่งเลขเครื่องยนต์',
                'option' => 'ซ้ายเครื่อง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            53 => 
            array (
                'id' => 54,
                'column' => 'กรรมสิทธิ์',
                'option' => 'EEC',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            54 => 
            array (
                'id' => 55,
                'column' => 'กรรมสิทธิ์',
                'option' => 'ENCOM',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            55 => 
            array (
                'id' => 56,
                'column' => 'กรรมสิทธิ์',
                'option' => 'NASIAM',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            56 => 
            array (
                'id' => 57,
                'column' => 'กรรมสิทธิ์',
                'option' => 'PATRAPORN',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            57 => 
            array (
                'id' => 58,
                'column' => 'กรรมสิทธิ์',
                'option' => 'SFM',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            58 => 
            array (
                'id' => 59,
                'column' => 'กรรมสิทธิ์',
                'option' => 'Sold',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            59 => 
            array (
                'id' => 60,
                'column' => 'กรรมสิทธิ์',
                'option' => 'WORAKORN',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            60 => 
            array (
                'id' => 61,
                'column' => 'กรรมสิทธิ์',
                'option' => 'ลิสซิ่ง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            61 => 
            array (
                'id' => 62,
                'column' => 'กรรมสิทธิ์',
                'option' => 'ลุงวุฒิ',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            62 => 
            array (
                'id' => 63,
                'column' => 'สถานะ',
                'option' => 'ขาย',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            63 => 
            array (
                'id' => 64,
                'column' => 'สถานะ',
                'option' => 'ปลดภาระ',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            64 => 
            array (
                'id' => 65,
                'column' => 'สถานะ',
                'option' => 'มีภาระ',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            65 => 
            array (
                'id' => 66,
                'column' => 'บริษัทลิซซิ่ง',
                'option' => 'เกียรตินาคิน',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            66 => 
            array (
                'id' => 67,
                'column' => 'บริษัทลิซซิ่ง',
                'option' => 'โตโยต้าลิสซิ่ง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            67 => 
            array (
                'id' => 68,
                'column' => 'บริษัทลิซซิ่ง',
                'option' => 'ทิสโก้ ลิสซิ่ง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            68 => 
            array (
                'id' => 69,
                'column' => 'บริษัทลิซซิ่ง',
                'option' => 'ไทยโอริกซ์ ลิสซิ่ง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            69 => 
            array (
                'id' => 70,
                'column' => 'บริษัทลิซซิ่ง',
                'option' => 'ธนาคารธนชาติ',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            70 => 
            array (
                'id' => 71,
                'column' => 'บริษัทลิซซิ่ง',
            'option' => 'ธนาคารแลนด์ แอนด์ เฮ้าส์ จำกัด (มหาชน)',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            71 => 
            array (
                'id' => 72,
                'column' => 'บริษัทลิซซิ่ง',
            'option' => 'บริษัท เอสเอ็มเอฟแอล ลิสซิ่ง (ประเทศไทย) จำกัด',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            72 => 
            array (
                'id' => 73,
                'column' => 'บริษัทลิซซิ่ง',
                'option' => 'ภัทรลิสซิ่ง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            73 => 
            array (
                'id' => 74,
                'column' => 'บริษัทลิซซิ่ง',
                'option' => 'มิตรสยามมอเตอร์ส',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            74 => 
            array (
                'id' => 75,
                'column' => 'บริษัทลิซซิ่ง',
                'option' => 'ราชธานีลิสซิ่ง',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            75 => 
            array (
                'id' => 76,
                'column' => 'บริษัทลิซซิ่ง',
                'option' => 'ลิสซิ่งกสิกรไทย',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            76 => 
            array (
                'id' => 77,
                'column' => 'บริษัทลิซซิ่ง',
            'option' => 'เอเชียเสริมกิจลิสซิ่ง (ซื้อต่อจากสหนคร)',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            77 => 
            array (
                'id' => 78,
                'column' => 'บริษัทลิซซิ่ง',
                'option' => 'ฮิตาชิ แคปปิตอล',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
            78 => 
            array (
                'id' => 79,
                'column' => 'บริษัทลิซซิ่ง',
                'option' => 'ฮีโนสยามนคร',
                'created_at' => '2018-08-13 20:01:43',
                'updated_at' => '2018-08-13 20:01:43',
            ),
        ));
        
        
    }
}