<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $villages = [
            [
                'district_id' => 1,
                'village_name' => 'SELONG BELANAK',
                'bps_code' => '5202010002',
                'kemendagri_code' => '52.02.05.2007',
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'MEKAR SARI',
                'bps_code' => '5202010003',
                'kemendagri_code' => '52.02.05.2008',
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'BANYU URIP',
                'bps_code' => '5202010004',
                'kemendagri_code' => '52.02.05.2002',
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'KATENG',
                'bps_code' => '5202010005',
                'kemendagri_code' => '52.02.05.2004',
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'MANGKUNG',
                'bps_code' => '5202010006',
                'kemendagri_code' => '52.02.05.2003',
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'BONDIR',
                'bps_code' => '5202010007',
                'kemendagri_code' => '52.02.05.2001',
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'SETANGGOR',
                'bps_code' => '5202010013',
                'kemendagri_code' => '52.02.05.2005',
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'PENUJAK',
                'bps_code' => '5202010014',
                'kemendagri_code' => '52.02.05.2006',
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'BATUJAI',
                'bps_code' => '5202010015',
                'kemendagri_code' => '52.02.05.2009',
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'TANAK RARANG',
                'bps_code' => '5202010016',
                'kemendagri_code' => '52.02.05.2010',
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'MENTOKOK SELANGLET',
                'bps_code' => '5202010017',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'BATU ASAK',
                'bps_code' => '5202010018',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'MASJURING',
                'bps_code' => '5202010019',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 1,
                'village_name' => 'JANGKIH JAWE',
                'bps_code' => '5202010020',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 2,
                'village_name' => 'MONTONG SAPAH',
                'bps_code' => '5202011001',
                'kemendagri_code' => '52.02.11.2001',
                'resident_count' => null
            ],
            [
                'district_id' => 2,
                'village_name' => 'KABUL',
                'bps_code' => '5202011002',
                'kemendagri_code' => '52.02.11.2003',
                'resident_count' => null
            ],
            [
                'district_id' => 2,
                'village_name' => 'PELAMBIK',
                'bps_code' => '5202011003',
                'kemendagri_code' => '52.02.11.2004',
                'resident_count' => null
            ],
            [
                'district_id' => 2,
                'village_name' => 'RANGGAGATA',
                'bps_code' => '5202011004',
                'kemendagri_code' => '52.02.11.2006',
                'resident_count' => null
            ],
            [
                'district_id' => 2,
                'village_name' => 'UNGGA',
                'bps_code' => '5202011005',
                'kemendagri_code' => '52.02.11.2002',
                'resident_count' => null
            ],
            [
                'district_id' => 2,
                'village_name' => 'DAREK',
                'bps_code' => '5202011006',
                'kemendagri_code' => '52.02.11.2005',
                'resident_count' => null
            ],
            [
                'district_id' => 2,
                'village_name' => 'BATU JANGKIH',
                'bps_code' => '5202011007',
                'kemendagri_code' => '52.02.11.2010',
                'resident_count' => null
            ],
            [
                'district_id' => 2,
                'village_name' => 'MONTONG AJAN',
                'bps_code' => '5202011008',
                'kemendagri_code' => '52.02.11.2009',
                'resident_count' => null
            ],
            [
                'district_id' => 2,
                'village_name' => 'PANDAN INDAH',
                'bps_code' => '5202011009',
                'kemendagri_code' => '52.02.11.2007',
                'resident_count' => null
            ],
            [
                'district_id' => 2,
                'village_name' => 'SERAGE',
                'bps_code' => '5202011010',
                'kemendagri_code' => '52.02.11.2008',
                'resident_count' => null
            ],
            [
                'district_id' => 2,
                'village_name' => 'TEDUH',
                'bps_code' => '5202011011',
                'kemendagri_code' => '52.02.11.2011',
                'resident_count' => null
            ],
            [
                'district_id' => 2,
                'village_name' => 'PANDAN TINGGANG',
                'bps_code' => '5202011012',
                'kemendagri_code' => '52.02.11.2012',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'TUMPAK',
                'bps_code' => '5202020001',
                'kemendagri_code' => '52.02.04.2011',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'PRABU',
                'bps_code' => '5202020002',
                'kemendagri_code' => '52.02.04.2013',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'KUTA',
                'bps_code' => '5202020003',
                'kemendagri_code' => '52.02.04.2009',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'REMBITAN',
                'bps_code' => '5202020004',
                'kemendagri_code' => '52.02.04.2008',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'SUKADANA',
                'bps_code' => '5202020005',
                'kemendagri_code' => '52.02.04.2003',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'MERTAK',
                'bps_code' => '5202020006',
                'kemendagri_code' => '52.02.04.2012',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'PENGENGAT',
                'bps_code' => '5202020007',
                'kemendagri_code' => '52.02.04.2005',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'TERUWAI',
                'bps_code' => '5202020008',
                'kemendagri_code' => '52.02.04.2004',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'GAPURA',
                'bps_code' => '5202020009',
                'kemendagri_code' => '52.02.04.2007',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'KAWO',
                'bps_code' => '5202020010',
                'kemendagri_code' => '52.02.04.2006',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'SEGALE ANYAR',
                'bps_code' => '5202020011',
                'kemendagri_code' => '52.02.04.2002',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'SENGKOL',
                'bps_code' => '5202020012',
                'kemendagri_code' => '52.02.04.2001',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'PENGEMBUR',
                'bps_code' => '5202020013',
                'kemendagri_code' => '52.02.04.2010',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'KETARA',
                'bps_code' => '5202020014',
                'kemendagri_code' => '52.02.04.2015',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'TANAK AWU',
                'bps_code' => '5202020015',
                'kemendagri_code' => '52.02.04.2014',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'BANGKET PARAK',
                'bps_code' => '5202020016',
                'kemendagri_code' => '52.02.04.2016',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'DADAP',
                'bps_code' => '5202020017',
                'kemendagri_code' => '52.02.04.2017',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'KEREME JATI',
                'bps_code' => '5202020018',
                'kemendagri_code' => '52.02.04.2018',
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'NANDUS',
                'bps_code' => '5202020019',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 3,
                'village_name' => 'AWANG',
                'bps_code' => '5202020020',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'KIDANG',
                'bps_code' => '5202030001',
                'kemendagri_code' => '52.02.06.2010',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'BILELANDO',
                'bps_code' => '5202030002',
                'kemendagri_code' => '52.02.06.2007',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'SEMOYANG',
                'bps_code' => '5202030003',
                'kemendagri_code' => '52.02.06.2003',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'GANTI',
                'bps_code' => '5202030004',
                'kemendagri_code' => '52.02.06.2008',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'BELEKA',
                'bps_code' => '5202030005',
                'kemendagri_code' => '52.02.06.2002',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'SENGKERANG',
                'bps_code' => '5202030006',
                'kemendagri_code' => '52.02.06.2006',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'LANDAH',
                'bps_code' => '5202030007',
                'kemendagri_code' => '52.02.06.2005',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'MARONG',
                'bps_code' => '5202030008',
                'kemendagri_code' => '52.02.06.2009',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'MUJUR',
                'bps_code' => '5202030009',
                'kemendagri_code' => '52.02.06.2004',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'SUKARAJA',
                'bps_code' => '5202030010',
                'kemendagri_code' => '52.02.06.2001',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'BELEKE DAYE',
                'bps_code' => '5202030011',
                'kemendagri_code' => '52.02.06.2011',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'BELEKE LEBE SANE',
                'bps_code' => '5202030012',
                'kemendagri_code' => '52.02.06.2012',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'JERO PURI',
                'bps_code' => '5202030013',
                'kemendagri_code' => '52.02.06.2014',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'PENGONAK',
                'bps_code' => '5202030014',
                'kemendagri_code' => '52.02.06.2013',
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'SEMUDANE',
                'bps_code' => '5202030015',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'EMBUNG PUNTIK',
                'bps_code' => '5202030016',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'DAHE',
                'bps_code' => '5202030017',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 4,
                'village_name' => 'KIDANG BARU',
                'bps_code' => '5202030018',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'LOANG MAKA',
                'bps_code' => '5202040001',
                'kemendagri_code' => '52.02.07.2004',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'LANGKO',
                'bps_code' => '5202040002',
                'kemendagri_code' => '52.02.07.2002',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'SELEBUNG REMBIGA',
                'bps_code' => '5202040003',
                'kemendagri_code' => '52.02.07.2009',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'BAKAN',
                'bps_code' => '5202040004',
                'kemendagri_code' => '52.02.07.2006',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'DURIAN',
                'bps_code' => '5202040005',
                'kemendagri_code' => '52.02.07.2007',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'PENDEM',
                'bps_code' => '5202040006',
                'kemendagri_code' => '52.02.07.2008',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'JANAPRIA',
                'bps_code' => '5202040007',
                'kemendagri_code' => '52.02.07.2003',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'SABA',
                'bps_code' => '5202040008',
                'kemendagri_code' => '52.02.07.2005',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'LEKOR',
                'bps_code' => '5202040009',
                'kemendagri_code' => '52.02.07.2001',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'KEREMBONG',
                'bps_code' => '5202040010',
                'kemendagri_code' => '52.02.07.2010',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'JANGO',
                'bps_code' => '5202040011',
                'kemendagri_code' => '52.02.07.2011',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'SETUTA',
                'bps_code' => '5202040012',
                'kemendagri_code' => '52.02.07.2012',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'JANGGAWANA',
                'bps_code' => '5202040013',
                'kemendagri_code' => '52.02.07.2015',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'LINGKOK BRENGE',
                'bps_code' => '5202040014',
                'kemendagri_code' => '52.02.07.2016',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'PRAKO',
                'bps_code' => '5202040015',
                'kemendagri_code' => '52.02.07.2013',
                'resident_count' => null
            ],
            [
                'district_id' => 5,
                'village_name' => 'TIBU SISOK',
                'bps_code' => '5202040016',
                'kemendagri_code' => '52.02.07.2014',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'MUNCAN',
                'bps_code' => '5202050001',
                'kemendagri_code' => '52.02.09.2003',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'MONGGAS',
                'bps_code' => '5202050002',
                'kemendagri_code' => '52.02.09.2002',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'DARMAJI',
                'bps_code' => '5202050003',
                'kemendagri_code' => '52.02.09.2008',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'DASAN BARU',
                'bps_code' => '5202050004',
                'kemendagri_code' => '52.02.09.2006',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'KOPANG REMBIGA',
                'bps_code' => '5202050005',
                'kemendagri_code' => '52.02.09.2005',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'MONTONG GAMANG',
                'bps_code' => '5202050006',
                'kemendagri_code' => '52.02.09.2007',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'LENDANG ARA',
                'bps_code' => '5202050007',
                'kemendagri_code' => '52.02.09.2001',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'BEBUAQ',
                'bps_code' => '5202050008',
                'kemendagri_code' => '52.02.09.2004',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'WAJA GESENG',
                'bps_code' => '5202050009',
                'kemendagri_code' => '52.02.09.2009',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'SEMPARU',
                'bps_code' => '5202050010',
                'kemendagri_code' => '52.02.09.2011',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'AIK BUAL',
                'bps_code' => '5202050011',
                'kemendagri_code' => '52.02.09.2010',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'BERINDING',
                'bps_code' => '5202050012',
                'kemendagri_code' => '52.02.09.2012',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'PAJANGAN',
                'bps_code' => '5202050013',
                'kemendagri_code' => '52.02.09.2013',
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'PESENG',
                'bps_code' => '5202050014',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 6,
                'village_name' => 'MONGGAS BERSATU',
                'bps_code' => '5202050015',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'PANJISARI',
                'bps_code' => '5202060001',
                'kemendagri_code' => '52.02.01.1008',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'LENENG',
                'bps_code' => '5202060002',
                'kemendagri_code' => '52.02.01.1002',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'RENTENG',
                'bps_code' => '5202060003',
                'kemendagri_code' => '52.02.01.1009',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'PRAYA',
                'bps_code' => '5202060004',
                'kemendagri_code' => '52.02.01.1001',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'PRAPEN',
                'bps_code' => '5202060005',
                'kemendagri_code' => '52.02.01.1005',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'TIWUGALIH',
                'bps_code' => '5202060006',
                'kemendagri_code' => '52.02.01.1006',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'SEMAYAN',
                'bps_code' => '5202060009',
                'kemendagri_code' => '52.02.01.1004',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'BUNUT BAOK',
                'bps_code' => '5202060018',
                'kemendagri_code' => '52.02.01.2014',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'GERUNUNG',
                'bps_code' => '5202060019',
                'kemendagri_code' => '52.02.01.1003',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'GONJAK',
                'bps_code' => '5202060020',
                'kemendagri_code' => '52.02.01.1007',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'JAGO',
                'bps_code' => '5202060021',
                'kemendagri_code' => '52.02.01.2013',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'AIK MUAL',
                'bps_code' => '5202060022',
                'kemendagri_code' => '52.02.01.2011',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'MERTAK TOMBOK',
                'bps_code' => '5202060023',
                'kemendagri_code' => '52.02.01.2010',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'MONTONG TEREP',
                'bps_code' => '5202060024',
                'kemendagri_code' => '52.02.01.2012',
                'resident_count' => null
            ],
            [
                'district_id' => 7,
                'village_name' => 'MEKAR DAMAI',
                'bps_code' => '5202060025',
                'kemendagri_code' => '52.02.01.2015',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'JONTLAK',
                'bps_code' => '5202061001',
                'kemendagri_code' => '52.02.10.1002',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'GERANTUNG',
                'bps_code' => '5202061002',
                'kemendagri_code' => '52.02.10.1001',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'SASAKE',
                'bps_code' => '5202061003',
                'kemendagri_code' => '52.02.10.1003',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'LAJUT',
                'bps_code' => '5202061004',
                'kemendagri_code' => '52.02.10.2007',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'BATUNYALA',
                'bps_code' => '5202061005',
                'kemendagri_code' => '52.02.10.2006',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'PEJANGGIK',
                'bps_code' => '5202061006',
                'kemendagri_code' => '52.02.10.2010',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'KELEBUH',
                'bps_code' => '5202061007',
                'kemendagri_code' => '52.02.10.2009',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'BERAIM',
                'bps_code' => '5202061008',
                'kemendagri_code' => '52.02.10.2005',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'PENGADANG',
                'bps_code' => '5202061009',
                'kemendagri_code' => '52.02.10.2008',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'JURANG JALER',
                'bps_code' => '5202061010',
                'kemendagri_code' => '52.02.10.2004',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'DAKUNG',
                'bps_code' => '5202061011',
                'kemendagri_code' => '52.02.10.2011',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'PRAI MEKE',
                'bps_code' => '5202061012',
                'kemendagri_code' => '52.02.10.2012',
                'resident_count' => null
            ],
            [
                'district_id' => 8,
                'village_name' => 'LELONG',
                'bps_code' => '5202061013',
                'kemendagri_code' => '52.02.10.2013',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'LABULIA',
                'bps_code' => '5202070001',
                'kemendagri_code' => '52.02.02.2004',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'SUKARARA',
                'bps_code' => '5202070002',
                'kemendagri_code' => '52.02.02.2010',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'PUYUNG',
                'bps_code' => '5202070003',
                'kemendagri_code' => '52.02.02.2008',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'GEMEL',
                'bps_code' => '5202070004',
                'kemendagri_code' => '52.02.02.2011',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'BAREJULAT',
                'bps_code' => '5202070005',
                'kemendagri_code' => '52.02.02.2001',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'BUNKATE',
                'bps_code' => '5202070006',
                'kemendagri_code' => '52.02.02.2013',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'NYEROT',
                'bps_code' => '5202070007',
                'kemendagri_code' => '52.02.02.2009',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'BATUTULIS',
                'bps_code' => '5202070008',
                'kemendagri_code' => '52.02.02.2005',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'JELANTIK',
                'bps_code' => '5202070009',
                'kemendagri_code' => '52.02.02.2003',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'UBUNG',
                'bps_code' => '5202070010',
                'kemendagri_code' => '52.02.02.2002',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'BONJERUK',
                'bps_code' => '5202070011',
                'kemendagri_code' => '52.02.02.2012',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'PERINA',
                'bps_code' => '5202070012',
                'kemendagri_code' => '52.02.02.2006',
                'resident_count' => null
            ],
            [
                'district_id' => 9,
                'village_name' => 'PENGENJEK',
                'bps_code' => '5202070013',
                'kemendagri_code' => '52.02.02.2007',
                'resident_count' => null
            ],
            [
                'district_id' => 10,
                'village_name' => 'BILEBANTE',
                'bps_code' => '5202080001',
                'kemendagri_code' => '52.02.08.2006',
                'resident_count' => null
            ],
            [
                'district_id' => 10,
                'village_name' => 'BAGU',
                'bps_code' => '5202080002',
                'kemendagri_code' => '52.02.08.2004',
                'resident_count' => null
            ],
            [
                'district_id' => 10,
                'village_name' => 'SINTUNG',
                'bps_code' => '5202080003',
                'kemendagri_code' => '52.02.08.2005',
                'resident_count' => null
            ],
            [
                'district_id' => 10,
                'village_name' => 'PRINGGARATA',
                'bps_code' => '5202080004',
                'kemendagri_code' => '52.02.08.2001',
                'resident_count' => null
            ],
            [
                'district_id' => 10,
                'village_name' => 'MURBAYA',
                'bps_code' => '5202080005',
                'kemendagri_code' => '52.02.08.2003',
                'resident_count' => null
            ],
            [
                'district_id' => 10,
                'village_name' => 'SEPAKEK',
                'bps_code' => '5202080006',
                'kemendagri_code' => '52.02.08.2002',
                'resident_count' => null
            ],
            [
                'district_id' => 10,
                'village_name' => 'PEMEPEK',
                'bps_code' => '5202080007',
                'kemendagri_code' => '52.02.08.2007',
                'resident_count' => null
            ],
            [
                'district_id' => 10,
                'village_name' => 'MENEMENG',
                'bps_code' => '5202080008',
                'kemendagri_code' => '52.02.08.2008',
                'resident_count' => null
            ],
            [
                'district_id' => 10,
                'village_name' => 'ARJANGKA',
                'bps_code' => '5202080009',
                'kemendagri_code' => '52.02.08.2009',
                'resident_count' => null
            ],
            [
                'district_id' => 10,
                'village_name' => 'TAMAN INDAH',
                'bps_code' => '5202080010',
                'kemendagri_code' => '52.02.08.2010',
                'resident_count' => null
            ],
            [
                'district_id' => 10,
                'village_name' => 'SISIK',
                'bps_code' => '5202080011',
                'kemendagri_code' => '52.02.08.2011',
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'BEBER',
                'bps_code' => '5202090001',
                'kemendagri_code' => '52.02.03.2008',
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'PAGUTAN',
                'bps_code' => '5202090002',
                'kemendagri_code' => '52.02.03.2009',
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'BARABALI',
                'bps_code' => '5202090003',
                'kemendagri_code' => '52.02.03.2007',
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'BUJAK',
                'bps_code' => '5202090004',
                'kemendagri_code' => '52.02.03.2001',
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'PERESAK',
                'bps_code' => '5202090005',
                'kemendagri_code' => '52.02.03.2003',
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'MANTANG',
                'bps_code' => '5202090006',
                'kemendagri_code' => '52.02.03.2004',
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'AIK DAREQ',
                'bps_code' => '5202090007',
                'kemendagri_code' => '52.02.03.2005',
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'SELEBUNG',
                'bps_code' => '5202090008',
                'kemendagri_code' => '52.02.03.2002',
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'TAMPAK SIRING',
                'bps_code' => '5202090009',
                'kemendagri_code' => '52.02.03.2006',
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'MEKAR BERSATU',
                'bps_code' => '5202090010',
                'kemendagri_code' => '52.02.03.2010',
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'LENDANG TAMPEL',
                'bps_code' => '5202090011',
                'kemendagri_code' => '52.02.03.2011',
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'TOJONG OJONG',
                'bps_code' => '5202090012',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 11,
                'village_name' => 'BENUE',
                'bps_code' => '5202090013',
                'kemendagri_code' => null,
                'resident_count' => null
            ],
            [
                'district_id' => 12,
                'village_name' => 'MAS-MAS',
                'bps_code' => '5202091001',
                'kemendagri_code' => '52.02.12.2007',
                'resident_count' => null
            ],
            [
                'district_id' => 12,
                'village_name' => 'AIK BUKAQ',
                'bps_code' => '5202091002',
                'kemendagri_code' => '52.02.12.2004',
                'resident_count' => null
            ],
            [
                'district_id' => 12,
                'village_name' => 'SETILING',
                'bps_code' => '5202091003',
                'kemendagri_code' => '52.02.12.2002',
                'resident_count' => null
            ],
            [
                'district_id' => 12,
                'village_name' => 'AIK BERIK',
                'bps_code' => '5202091004',
                'kemendagri_code' => '52.02.12.2006',
                'resident_count' => null
            ],
            [
                'district_id' => 12,
                'village_name' => 'TERATAK',
                'bps_code' => '5202091005',
                'kemendagri_code' => '52.02.12.2005',
                'resident_count' => null
            ],
            [
                'district_id' => 12,
                'village_name' => 'LANTAN',
                'bps_code' => '5202091006',
                'kemendagri_code' => '52.02.12.2001',
                'resident_count' => null
            ],
            [
                'district_id' => 12,
                'village_name' => 'TANAK BEAQ',
                'bps_code' => '5202091007',
                'kemendagri_code' => '52.02.12.2003',
                'resident_count' => null
            ],
            [
                'district_id' => 12,
                'village_name' => 'KARANG SIDEMEN',
                'bps_code' => '5202091008',
                'kemendagri_code' => '52.02.12.2008',
                'resident_count' => null
            ],
        ];

        foreach ($villages as $village) {
            Village::create([
                'district_id'  => $village['district_id'],
                'village_name'  => $village['village_name'],
                'bps_code'  => $village['bps_code'],
                'kemendagri_code'  => $village['kemendagri_code'],
                'resident_count' => $village['resident_count'],
            ]);
        }
    }
}
