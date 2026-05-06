<div class="max-w-2xl mx-auto mt-10">
    <!-- Kembali button moved to the bottom for better layout on mobile/desktop -->
    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white">
            <div class="flex items-center justify-center space-x-3">
                <div class="bg-white/20 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold">Input Kendaraan (Supir)</h1>
            </div>
            <p class="text-center text-blue-100 mt-2">Formulir pendaftaran kendaraan keluar/masuk</p>
        </div>
        <div class="mt-6 flex justify-start pl-4 md:pl-6">
            <a href="/"
                class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 bg-white hover:bg-blue-50 rounded-lg shadow-sm transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="p-6">
            @if (session()->has('message'))
                <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-green-800 font-medium">{{ session('message') }}</p>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="submit" class="space-y-6">

                {{-- JENIS KEGIATAN --}}
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <span class="text-blue-600 font-bold text-sm">1</span>
                        </div>
                        <label class="block text-sm font-bold text-gray-800">
                            Jenis Kegiatan <span class="text-red-500">*</span>
                        </label>
                    </div>

                    <select wire:model="type"
                        class="w-full border-2 border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">Pilih jenis kegiatan...</option>
                        <option value="bongkar">BONGKAR</option>
                        <option value="muat">MUAT</option>
                    </select>

                    <p class="text-xs text-gray-500 mt-3 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pilih dulu jenis kegiatan. Form di bawah akan menyesuaikan (BONGKAR / MUAT).
                    </p>

                    @error('type')
                        <p class="text-xs text-red-600 mt-1 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- DATA KENDARAAN --}}
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <span class="text-blue-600 font-bold text-sm">2</span>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">
                            Data Kendaraan & Supir
                            <span
                                class="inline-block ml-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 font-medium">
                                @if($type === 'bongkar') BONGKAR
                                @elseif($type === 'muat') MUAT
                                @else PILIH JENIS
                                @endif
                            </span>
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Company / Vendor --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Instansi / Vendor <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" list="companyNames" wire:model="company_name"
                                    class="w-full border-2 border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="Ketik atau pilih instansi / vendor...">
                                <datalist id="companyNames">
<option value="ABHISHEK IMPEX"></option>
<option value="ACD"></option>
<option value="ADIL MAKMUR FAJAR, PT (AMCO)"></option>
<option value="AGRINDO SURYA ABADI, PT"></option>
<option value="AGRO MULIA, CV"></option>
<option value="AGRODAN CHEMICAL CO., LTD"></option>
<option value="AGROTECH PESTICIDE INDUSTRY"></option>
<option value="AKINO WAHANAMULIA, PT"></option>
<option value="AKR CORPORINDO TBK, PT"></option>
<option value="ALFA KIMIA, TOKO"></option>
<option value="ALKINDO NARATAMA TBK, PT"></option>
<option value="ANEKA KARYA, CV"></option>
<option value="ANHUI HUAXING CHEMICAL INDUSTRY"></option>
<option value="ANHUI HUAXING CHEMICAL INDUSTRY (CNY)"></option>
<option value="ANUGERAH PRIMATAMA, PT"></option>
<option value="ANUGRAH KIMIA ARIWIDYA, PT"></option>
<option value="ARGATA PUTRA"></option>
<option value="ARTA PITAGIRI LESTARI, PT"></option>
<option value="ARTEMIS PRIMAVERA KEMINDO, PT"></option>
<option value="ASIA PRIMA PACKAGING, PT"></option>
<option value="ASTANA WIRA KARYA, PT"></option>
<option value="AZELIS INDONESIA DISTRIBUSI, PT"></option>
<option value="BAHAGIA FA"></option>
<option value="BAHTERA ADI JAYA, PT"></option>
<option value="BAMBANG"></option>
<option value="BANJAR"></option>
<option value="BARIA BULK TERMINAL, PT"></option>
<option value="BASF DISTRIBUTION INDONESIA, PT"></option>
<option value="BERKAH BERSAMA, TOKO"></option>
<option value="BETA CHEMICALS, LTD."></option>
<option value="BETA CHEMICALS, LTD. (CNY)"></option>
<option value="BRENNTAG, PT"></option>
<option value="BUKIT MEGA MAS ABADI, PT"></option>
<option value="BUKIT TINGGI"></option>
<option value="BUKIT WARNA ABADI, PT"></option>
<option value="CAHAYA FAJAR GEMILANG, CV"></option>
<option value="CAKRAWALA MEGA INDAH, PT"></option>
<option value="CANDI GASINDO UTAMA, PT"></option>
<option value="CATALITE INDONESIA, PT (IDR)"></option>
<option value="CATUR AGRODAYA MANDIRI, PT"></option>
<option value="CHANDRA ASRI PACIFIC TBK, PT"></option>
<option value="CHEMPLAST INDONESIA, PT"></option>
<option value="CHINA JIANGSU INTERNATIONAL INDONESIA, PT"></option>
<option value="CIANJUR"></option>
<option value="CIPTA JAYA MANDIRI, CV"></option>
<option value="CIPTA MITRA WARNA SEMPURNA, PT"></option>
<option value="CITRA SPS"></option>
<option value="CJI OVERSEAS IMPORT AND EXPORT LTD"></option>
<option value="CJI OVERSEAS IMPORT AND EXPORT LTD (CNY)"></option>
<option value="DELTACIPTA SARANAPROMOSI, PT"></option>
<option value="DHARMA COLOUR"></option>
<option value="DINAMIKA CIPTA MULIA PRIMATAMA, PT"></option>
<option value="DJULINAR METTA"></option>
<option value="EASTCHEM CO., LTD."></option>
<option value="ENERGI HIJAU SAMOEDERA BERSAUDARA, PT"></option>
<option value="ETONG"></option>
<option value="ETONG CHEMICAL INDONESIA, PT"></option>
<option value="EXCEL"></option>
<option value="EXCEL MEG INDO, PT"></option>
<option value="EXTRANS"></option>
<option value="FADILLAH MANDIRI, CV"></option>
<option value="FARACO GLOBAL TECHNOLOGY, PT"></option>
<option value="FH AGROCHEMICAL INTERNATIONAL TRADE PTE., LTD."></option>
<option value="FH AGROCHEMICAL INTERNATIONAL TRADE PTE.LTD (CNY)"></option>
<option value="GALIC BINA MADA, PT"></option>
<option value="GANA MAS PRIMA, PT"></option>
<option value="GANDIWA SUKSES BERSAMA, PT"></option>
<option value="GARAM, PT"></option>
<option value="GARUDA MAS LESTARI, PT"></option>
<option value="GENUS"></option>
<option value="GLOBALINDO INTI PERSADA, PT"></option>
<option value="GnG ASIA BINTANG JAYA, PT"></option>
<option value="GPI"></option>
<option value="GRAHA BATAVIA MANDIRI, PT"></option>
<option value="GRAHA JAYA PRATAMA KINERJA, PT"></option>
<option value="GREENFIELD CHEMICAL INDUSTRY CO., LIMITED (CNY)"></option>
<option value="GUANGAN LIER CHEMICAL CO., LTD"></option>
<option value="HALIM SAKTI PRATAMA, PT"></option>
<option value="HALIM SAMUDRA INTERUTAMA, PT"></option>
<option value="HEBEI SHANLI CHEMICAL CO., LTD."></option>
<option value="HEBEI SHANLI CHEMICAL CO., LTD. (CNY)"></option>
<option value="HEBEI SHUANGJI CHEMICAL CO., LTD (CNY)"></option>
<option value="HEMANI INDUSTRIES, LTD."></option>
<option value="HERIANTO"></option>
<option value="HERSUM CO., LIMITED."></option>
<option value="HEXA PRIMA PERSADA, PT"></option>
<option value="HITASE INDONESIA, PT"></option>
<option value="HUBEI BENXING ARGOCHEMICAL CO., LTD (CNY)"></option>
<option value="HUBEI BENXING SUPPLY CHAIN MANAGEMENT CO., LTD"></option>
<option value="HYPHEN CHEMICALS LIMITED (CNY)"></option>
<option value="ILUVA GRAVURE INDUSTRY, PT"></option>
<option value="INDOCHEMICAL CITRA KIMIA, PT"></option>
<option value="INDOKEMIKA JAYATAMA, PT"></option>
<option value="INDOKO BUMI PERMATA, PT"></option>
<option value="INDO-SINO AGROCHEMICAL, PT"></option>
<option value="INIKO KARYA PERSADA, PT"></option>
<option value="INNER MONGOLIA MIRACULOUS CROP SCIENCE CO., LTD."></option>
<option value="INNER MONGOLIA MIRACULOUS CROP SCIENCE CO., LTD. (CNY)"></option>
<option value="INTI EVERSPRING INDONESIA, PT"></option>
<option value="IPROCHEM COMPANY LIMITED"></option>
<option value="JAKARTA GLOBALINDO, PT"></option>
<option value="JAMBI"></option>
<option value="JAVA TAIKO DRUM INDUSTRIES, PT"></option>
<option value="JEBSEN & JESSEN CHEMICALS GMBH"></option>
<option value="JEBSEN & JESSEN INGREDIENTS INDONESIA"></option>
<option value="JETSET POLYCHROME, PT"></option>
<option value="JIANGSU INSTITUTE OF ECOMONES CO., LTD"></option>
<option value="JIANGSU INSTITUTE OF ECOMONES CO., LTD (YUAN)"></option>
<option value="JIANGSU NEW ENERGY CROP PROTECTION CO., LTD"></option>
<option value="JIANGSU NEW ENERGY CROP PROTECTION CO.,LTD (CNY)"></option>
<option value="JIVA AGRICULTURE INDONESIA"></option>
<option value="JOKOIS"></option>
<option value="JSK"></option>
<option value="JUSTUS KIMIARAYA, PT"></option>
<option value="KALIMANTAN"></option>
<option value="KARYA INDAH MULTIGUNA, PT"></option>
<option value="LAKIN KEMAS GLOBAL, PT"></option>
<option value="LAMBERTI INDONESIA, PT"></option>
<option value="LIER CHEMICAL CO., LTD."></option>
<option value="LIER CHEMICAL CO.,LTD. (CNY)"></option>
<option value="LOTTE CHEMICAL TITAN NUSANTARA, PT"></option>
<option value="MAHA KIMIA INDONESIA, PT"></option>
<option value="MAKMUR FALLAS, CV"></option>
<option value="MATA PELANGI TRADINDO, PT"></option>
<option value="MCL"></option>
<option value="MEDIA KARYA MAJASARI, PT"></option>
<option value="MEGA CIPTA UTAMA, CV"></option>
<option value="MEGAH CONVERTING, PT"></option>
<option value="MENARA CIPTA PROFILINDO, PT"></option>
<option value="MOBIL PABRIK"></option>
<option value="MITRA ABADI KEMASINDO, PT"></option>
<option value="MITRA MAKMUR DWIJAYA, PT"></option>
<option value="MITRA SANTOSA ABADI, CV"></option>
<option value="MULTI MAKMUR INDAH INDUSTRI, PT"></option>
<option value="MULTI SARANA INDOTANI, PT"></option>
<option value="MULTIBOX INDAH, PT"></option>
<option value="MULYA ADHI PARAMITA, PT"></option>
<option value="NABE CRYSTAL MANDIRI, PT"></option>
<option value="NAGARI AGRIMUKTI, CV"></option>
<option value="NANJING LIMIN CHEMICAL CO., LTD (CNY)"></option>
<option value="NANTONG JIANGSHAN AGROCHEMICAL & CHEMICALS CO., LTD (CNY)"></option>
<option value="NANTONG JIANGSHAN AGROCHEMICAL &CHEMICALS LIMITED LIABILITY Co."></option>
<option value="NAVA HITA KARANA, PT"></option>
<option value="NINGXIA WYNCA TECHNOLOGY CO., LTD"></option>
<option value="NINGXIA WYNCA TECHNOLOGY CO., LTD (CNY)"></option>
<option value="NOPOSION AGROCHEMICALS INDONESIA, PT"></option>
<option value="OMNI KEMAS INDUSTRY, PT"></option>
<option value="OMNI PLUS SYSTEM, PT"></option>
<option value="PALKO SARI EKA, PT"></option>
<option value="PELANGI DWIPUTRA KIMIARAYA, PT"></option>
<option value="PELITA ABADI SENTOSA, PT"></option>
<option value="PLASTRADE WORLD, PT"></option>
<option value="POLYMARK REAINDO PLUS, PT"></option>
<option value="PRIMA JAYA INDAH LESTARI, PT"></option>
<option value="PRIMA KARYA BERJAYA, PT"></option>
<option value="PRIMA KARYA BERJAYA, PT (CNY)"></option>
<option value="QINGDAO HISIGMA CHEMICALS CO., LTD."></option>
<option value="QINGDAO HISIGMA CHEMICALS CO., LTD. (CNY)"></option>
<option value="ROHA LAUTAN PEWARNA, PT"></option>
<option value="SAGARA PRIMA PERKASA, PT"></option>
<option value="SAIKU PUTRA EXPRESS"></option>
<option value="SAMCHEM PRASANDHA, PT"></option>
<option value="SAMUDRA UTAMA NARAPATI, PT (USD)"></option>
<option value="SARI KRESNA KIMIA, PT"></option>
<option value="SARI KRESNA KIMIA, PT (CNY)"></option>
<option value="SARI KRESNA KIMIA, PT (USD)"></option>
<option value="SEJATI TANI, CV"></option>
<option value="SELARAS JAYA ABADI, CV"></option>
<option value="SENTRA KEMIKA PERSADA, PT"></option>
<option value="SENTRAL TANI NUSANTARA, PT"></option>
<option value="SHANDONG BINNONG TECHNOLOGY CO., LTD."></option>
<option value="SHANDONG WEIFANG RAINBOW CHEMICAL CO., LTD."></option>
<option value="SHANDONG WEIFANG RAINBOW CHEMICAL CO., LTD. (YUAN)"></option>
<option value="SHANGHAI E-TONG CHEMICAL CO., LTD."></option>
<option value="SHANGHAI E-TONG CHEMICAL CO.,LTD (YUAN)"></option>
<option value="SHANGHAI HANFU BIOTECHNOLOGY CO., LTD"></option>
<option value="SHANGHAI KJ IMPORT & EXPORT CO., LTD."></option>
<option value="SHANGHAI NEW VALLEY BIOTECHNOLOGY CO., LTD (CNY)"></option>
<option value="SICHUAN LESHAN FUHUA TONGDA AGRO-CHEMICAL TECHNOLOGY CO., LTD (CNY)"></option>
<option value="SINAMYANG"></option>
<option value="SINAMYANG GROUP PTE., LTD."></option>
<option value="SINAMYANG GROUP PTE., LTD. (YUAN)"></option>
<option value="SINAMYANG INDONESIA, PT"></option>
<option value="SINAR KIMIA UTAMA, PT"></option>
<option value="SINAR SILICA MAKMUR, PT"></option>
<option value="SINERGI MITRA SUKSES, PT"></option>
<option value="SINLOG"></option>
<option value="SINO AGRI BIOSCIENCES SINGAPORE PTE., LTD."></option>
<option value="SINO AGRI BIOSCIENCES SINGAPORE PTE., LTD. (CNY)"></option>
<option value="SINO-AGRI LEADING BIOSCIENCES CO., LTD (CNY)"></option>
<option value="SINO-AGRI RED SUN BIO-TECHNOLOGY CO., LTD"></option>
<option value="SINO-AGRI RED SUN BIO-TECHNOLOGY CO., LTD (CNY)"></option>
<option value="SOLO LOGO INDONESIA, PT"></option>
<option value="STS"></option>
<option value="SUENHO PERKASA, PT"></option>
<option value="SUMATERA ABADI JAYA, CV"></option>
<option value="SUMBER ABADI KIMIA"></option>
<option value="SUMBER INDOKEM JAYA"></option>
<option value="SUPER PERDANA"></option>
<option value="SURYA BARU, PT"></option>
<option value="SURYA MANDIRI"></option>
<option value="SURYA RENGO CONTAINERS, PT"></option>
<option value="SUWANDY"></option>
<option value="TAIZHOU CHUNFENG MACHINERY CO., LTD."></option>
<option value="TAIZHOU CHUNFENG MACHINERY CO., LTD. (CNY)"></option>
<option value="TAIZHOU LUQIAO LAMSIN IMPORT & EXPORT CO., LTD."></option>
<option value="TAIZHOU LUQIAO LAMSIN IMPORT & EXPORT CO., LTD. (CNY)"></option>
<option value="TAIZHOU ORDER IMPORT AND EXPORT CO.,LTD"></option>
<option value="TAIZHOU ORDER IMPORT AND EXPORT CO.,LTD. (CNY)"></option>
<option value="TANI MAKMUR, CV"></option>
<option value="THAI HARVEST, LTD."></option>
<option value="THAI HARVEST LTD. (CNY)"></option>
<option value="TIANJIN DINGSHEGXIN CHEMICAL INDUSTRY CO., LTD."></option>
<option value="TIGA KRISMA SEJAHTERA, CV"></option>
<option value="TIRTA SURYA RAYA, PT"></option>
<option value="TITIAN ABADI LESTARI, PT"></option>
<option value="TOKO MANG OGI"></option>
<option value="TOKOPEDIA"></option>
<option value="TRANSPORINDO"></option>
<option value="TRICIPTA AGUNG SEJAHTERA, PT"></option>
<option value="TRITAMA VIRYA POLYMER, PT"></option>
<option value="TRUSTCHEM CO., LTD."></option>
<option value="TRUSTCHEM CO., LTD. (CNY)"></option>
<option value="UDAYA ANUGERAH, PT"></option>
<option value="ULTRA PRIMA CORRUGATOR, PT"></option>
<option value="UNGGUL TEKNO FILTER, PT"></option>
<option value="UNID GLOBAL CORPORATION"></option>
<option value="UNIPACK PLASINDO"></option>
<option value="UNITED CHEMICAL INTER ANEKA"></option>
<option value="UNIVERSAL PACK INDUSTRY, PT"></option>
<option value="UPL INDONESIA, PT"></option>
<option value="WAHANA MAS MULIA, PT"></option>
<option value="WARNAPRIMA KIMIATAMA, PT"></option>
<option value="WEDO INDUSTRY LIMITED"></option>
<option value="WEIFANG LUBA CHEMICAL CO., LTD (CNY)"></option>
<option value="WIKA INTINUSA NIAGATAMA, PT"></option>
<option value="XUZHOU JINHE CHEMICALS CO., LTD"></option>
<option value="YANNO AGRO SCIENCE INDONESIA, PT"></option>
<option value="YUASA INDUSTRIAL BATTERY INDONESIA, PT"></option>
<option value="YUDIAN KAWAN MINERAL, PT"></option>
<option value="ZHEJIANG CHEMICALS IMPORT & EXPORT CORPORATION"></option>
<option value="ZHEJIANG CHEMICALS IMPORT & EXPORT CORPORATION (CNY)"></option>
<option value="ZHEJIANG RAYFULL CHEMICALS CO.,LTD."></option>
<option value="ZHEJIANG XINAN CHEMICAL INDUSTRIAL GROUP CO., LTD (CNY)"></option>
<option value="ZHEJIANG XINNONG CHEMICAL CO., LTD."></option>
<option value="ZHEJIANG XINNONG CHEMICAL CO., LTD. (CNY)"></option>
<option value="ZHONGCHANG INTERNASIONAL (SINGAPORE) TRADING PTE. LTD."></option>
<option value="ZHONGCHANG INTERNASIONAL (SINGAPORE) TRADING PTE. LTD. (CNY)"></option>
<option value="ZHUOCHEN INDUSTRIES (SHANGHAI) CO., LTD"></option>
<option value="ZHUOCHEN INDUSTRIES (SHANGHAI) CO., LTD (YUAN)"></option>
                                </datalist>
                            </div>
                            @error('company_name')
                                <p class="text-xs text-red-600 mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Plat Nomor --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Polisi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" wire:model="plate_number"
                                    class="w-full border-2 border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="B 9070 NYK">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            @error('plate_number')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Kendaraan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jenis Kendaraan
                            </label>
                            <div>
                                <input type="text" list="vehicleKinds" wire:model="vehicle_kind"
                                    class="w-full border-2 border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="Ketik atau pilih jenis kendaraan...">

                                @php
                                    $vehicles = [
                                        'L300',
                                        'Colt Diesel',
                                        'FUSO Kecil',
                                        'FUSO Besar',
                                        'Tronton',
                                        'Kontainer 20 Ft',
                                        'Kontainer 40 Ft',
                                        'Tangki',
                                        'Storage',
                                        'Fuso Gandengan',
                                        'Truck Box',
                                        'Fuso Box',
                                        'Fuso Wing Box',
                                        'Pick Up Box'
                                    ];

                                    sort($vehicles);
                                @endphp
                                <datalist id="vehicleKinds">
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle }}">{{ $vehicle }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>

                        {{-- Tujuan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan</label>
                            <div>
                                <input type="text" list="destinations" wire:model="destination"
                                    class="w-full border-2 border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="Ketik atau pilih tujuan...">

                                <!-- @php
                                
                                    @endphp -->
                                <datalist id="destinations">
                                    <option value="ACEH"></option>
                                    <option value="BANGKA"></option>
                                    <option value="BANJAR"></option>
                                    <option value="BENGKULU"></option>
                                    <option value="BREBES"></option>
                                    <option value="BUKIT TINGGI TMA"></option>
                                    <option value="BUKIT TINGGI UD. CAM"></option>
                                    <option value="CAACI - PEKANBARU"></option>
                                    <option value="CBA PABRIK"></option>
                                    <option value="CIANJUR"></option>
                                    <option value="DEPO BARABAI"></option>
                                    <option value="GORONTALO"></option>
                                    <option value="JAMBI"></option>
                                    <option value="JEMBER"></option>
                                    <option value="JOJGA"></option>
                                    <option value="KENDARI"></option>
                                    <option value="LAMPUNG"></option>
                                    <option value="MAKASAR"></option>
                                    <option value="MALANG"></option>
                                    <option value="MAMUJU"></option>
                                    <option value="MANADO"></option>
                                    <option value="MEDAN"></option>
                                    <option value="PALOPO"></option>
                                    <option value="PALU"></option>
                                    <option value="PALEMBANG"></option>
                                    <option value="PAMANUKAN"></option>
                                    <option value="PATI"></option>
                                    <option value="PEKANBARU"></option>
                                    <option value="PONTIANAK"></option>
                                    <option value="PURWOKERTO"></option>
                                    <option value="SAMARINDA"></option>
                                    <option value="SAMPIT"></option>
                                    <option value="SUB DEPO BATANG"></option>
                                    <option value="SUB DEPO BELITANG"></option>
                                    <option value="SUB DEPO BERAU"></option>
                                    <option value="SUB DEPO BIMA"></option>
                                    <option value="SUB DEPO MERANGEN"></option>
                                </datalist>
                            </div>
                        </div>

                        {{-- Nama Sopir --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Sopir <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="driver_name"
                                class="w-full border-2 border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Nama lengkap sopir">
                            @error('driver_name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- No HP Sopir --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor HP Sopir</label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-xl border-2 border-r-0 border-gray-200 bg-gray-50 text-sm text-gray-700">+62</span>
                                <input type="text" wire:model="driver_phone_local"
                                    class="w-full border-2 border-gray-200 rounded-r-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="812xxxx">
                            </div>
                        </div>

                        {{-- Identitas --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Identitas</label>
                            <div>
                                <input type="text" list="identityTypes" wire:model="driver_identity"
                                    class="w-full border-2 border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="Ketik atau pilih identitas...">
                                <datalist id="identityTypes">
                                    <option value="KTP"></option>
                                    <option value="SIM"></option>
                                    <option value="Passport"></option>
                                </datalist>
                            </div>
                        </div>

                        {{-- FIELD KHUSUS BONGKAR --}}
                        <div x-data x-show="$wire.type === 'bongkar'" x-cloak
                            class="md:col-span-2 mt-4 p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-blue-800 uppercase tracking-wide">Field Tambahan
                                    Proses Bongkar</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">No. Surat
                                        Jalan</label>
                                    <input type="text" wire:model="sj_number"
                                        class="w-full border-2 border-blue-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="97892736-9900">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Nama Barang</label>
                                    <input type="text" wire:model="item_name"
                                        class="w-full border-2 border-blue-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="KARTON BOX">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Jumlah Barang</label>
                                    <input type="text" wire:model="item_quantity"
                                        class="w-full border-2 border-blue-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                        placeholder="10.000 PCS">
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                            <textarea wire:model="description" rows="3"
                                class="w-full border-2 border-gray-200 rounded-xl p-3.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                placeholder="{{ $type === 'bongkar'
    ? 'BONGKAR'
    : ($type === 'muat' ? 'MUAT' : '') }}"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-4 px-6 rounded-xl text-sm tracking-wide shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span>Kirim Data Sekarang</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>