<?php

use Illuminate\Database\Seeder;

class BluePrintsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('blue_prints')->delete();
        
        \DB::table('blue_prints')->insert(array (
            0 => 
            array (
                'id' => 1,
                'column' => 'รหัส',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'text',
                'modifier' => 'unique',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            1 => 
            array (
                'id' => 2,
                'column' => 'จดทะเบียน',
                'group' => 'assets',
                'type' => 'date',
                'form' => 'date',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            2 => 
            array (
                'id' => 3,
                'column' => 'จังหวัด',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'select',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            3 => 
            array (
                'id' => 4,
                'column' => 'เชื้อเพลิง',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'select',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            4 => 
            array (
                'id' => 5,
                'column' => 'ประเภทรถ',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'select',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            5 => 
            array (
                'id' => 6,
                'column' => 'ยี่ห้อ',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'select',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            6 => 
            array (
                'id' => 7,
                'column' => 'รุ่น',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'text',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            7 => 
            array (
                'id' => 8,
                'column' => 'รุ่นปี',
                'group' => 'assets',
                'type' => 'integer',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            8 => 
            array (
                'id' => 9,
                'column' => 'สี',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'text',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            9 => 
            array (
                'id' => 10,
                'column' => 'ทะเบียนรถรอง',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'text',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            10 => 
            array (
                'id' => 11,
                'column' => 'เลขตัวรถ',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'text',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            11 => 
            array (
                'id' => 12,
                'column' => 'ตำแหน่งเลขตัวรถ',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'select',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            12 => 
            array (
                'id' => 13,
                'column' => 'เลขเครื่องยนต์',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'text',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            13 => 
            array (
                'id' => 14,
                'column' => 'ตำแหน่งเลขเครื่องยนต์',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'select',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            14 => 
            array (
                'id' => 15,
                'column' => 'จำนวนสูบ',
                'group' => 'assets',
                'type' => 'integer',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            15 => 
            array (
                'id' => 16,
                'column' => 'จำนวนซีซี',
                'group' => 'assets',
                'type' => 'integer',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            16 => 
            array (
                'id' => 17,
                'column' => 'แรงม้า',
                'group' => 'assets',
                'type' => 'integer',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            17 => 
            array (
                'id' => 18,
                'column' => 'จำนวนเพลา',
                'group' => 'assets',
                'type' => 'integer',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            18 => 
            array (
                'id' => 19,
                'column' => 'จำนวนล้อ',
                'group' => 'assets',
                'type' => 'integer',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            19 => 
            array (
                'id' => 20,
            'column' => 'จำนวนยาง (เส้น)',
                'group' => 'assets',
                'type' => 'integer',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            20 => 
            array (
                'id' => 21,
            'column' => 'น้ำหนักรถ (กก)',
                'group' => 'assets',
                'type' => 'integer',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            21 => 
            array (
                'id' => 22,
            'column' => 'น้ำหนักบรรทุก (กก)',
                'group' => 'assets',
                'type' => 'integer',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            22 => 
            array (
                'id' => 23,
            'column' => 'น้ำหนักรวม (กก)',
                'group' => 'assets',
                'type' => 'integer',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            23 => 
            array (
                'id' => 24,
                'column' => 'หมายเหตุ',
                'group' => 'assets',
                'type' => 'string',
                'form' => 'text',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            24 => 
            array (
                'id' => 25,
                'column' => 'Business Unit',
                'group' => 'allocations',
                'type' => 'string',
                'form' => 'select',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            25 => 
            array (
                'id' => 26,
                'column' => 'Fleet',
                'group' => 'allocations',
                'type' => 'string',
                'form' => 'select',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            26 => 
            array (
                'id' => 27,
                'column' => 'ทะเบียนรถ',
                'group' => 'taxes',
                'type' => 'string',
                'form' => 'text',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            27 => 
            array (
                'id' => 28,
                'column' => 'จดภาษี',
                'group' => 'taxes',
                'type' => 'date',
                'form' => 'date',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            28 => 
            array (
                'id' => 29,
                'column' => 'สิ้นภาษี',
                'group' => 'taxes',
                'type' => 'date',
                'form' => 'date',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            29 => 
            array (
                'id' => 30,
                'column' => 'ราคา',
                'group' => 'finances',
                'type' => 'decimal',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            30 => 
            array (
                'id' => 31,
                'column' => 'เงินดาวน์',
                'group' => 'finances',
                'type' => 'decimal',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            31 => 
            array (
                'id' => 32,
                'column' => 'เงินคงเหลือค่าลิซซิ่ง',
                'group' => 'finances',
                'type' => 'decimal',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            32 => 
            array (
                'id' => 33,
                'column' => 'ดอกเบี้ยรวม',
                'group' => 'finances',
                'type' => 'decimal',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            33 => 
            array (
                'id' => 34,
                'column' => 'ยอดคงเหลือ',
                'group' => '',
                'type' => '',
                'form' => '',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            34 => 
            array (
                'id' => 35,
                'column' => 'ราคาให้เลือกหลังสิ้นสุดสัญญา',
                'group' => '',
                'type' => '',
                'form' => '',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            35 => 
            array (
                'id' => 36,
            'column' => 'Effective Interest (%)',
                'group' => '',
                'type' => '',
                'form' => '',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            36 => 
            array (
                'id' => 37,
                'column' => 'กรรมสิทธิ์',
                'group' => 'contracts',
                'type' => 'string',
                'form' => 'select',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            37 => 
            array (
                'id' => 38,
                'column' => 'สถานะ',
                'group' => 'contracts',
                'type' => 'string',
                'form' => 'select',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            38 => 
            array (
                'id' => 39,
                'column' => 'เลขสัญญา',
                'group' => 'contracts',
                'type' => 'string',
                'form' => 'text',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            39 => 
            array (
                'id' => 40,
                'column' => 'บริษัทลิซซิ่ง',
                'group' => 'contracts',
                'type' => 'string',
                'form' => 'select',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            40 => 
            array (
                'id' => 41,
                'column' => 'เริ่มสัญญา',
                'group' => 'contracts',
                'type' => 'date',
                'form' => 'date',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            41 => 
            array (
                'id' => 42,
                'column' => 'สิ้นสุดสัญญา',
                'group' => 'contracts',
                'type' => 'date',
                'form' => 'date',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            42 => 
            array (
                'id' => 43,
                'column' => 'ดอกเบี้ย',
                'group' => 'contracts',
                'type' => 'decimal',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            43 => 
            array (
                'id' => 44,
                'column' => 'ระยะเวลาเช่าซื้อ',
                'group' => 'contracts',
                'type' => 'integer',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            44 => 
            array (
                'id' => 45,
                'column' => 'ค่างวด',
                'group' => 'contracts',
                'type' => 'decimal',
                'form' => 'number',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            45 => 
            array (
                'id' => 46,
                'column' => 'สัญญาเดิม',
                'group' => '',
                'type' => '',
                'form' => '',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
            46 => 
            array (
                'id' => 47,
                'column' => 'สัญญาเดิมเริ่ม',
                'group' => '',
                'type' => '',
                'form' => '',
                'modifier' => 'nullable',
                'created_at' => '2018-08-13 16:35:57',
                'updated_at' => '2018-08-13 16:35:57',
            ),
        ));
        
        
    }
}