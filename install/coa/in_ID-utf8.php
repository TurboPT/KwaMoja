<?php
InsertRecord('accountsection',array('sectionid'),array(10),array('sectionid','sectionname'),array(10,'Aktiva'), $db);
InsertRecord('accountsection',array('sectionid'),array(20),array('sectionid','sectionname'),array(20,'Kewajiban'), $db);
InsertRecord('accountsection',array('sectionid'),array(30),array('sectionid','sectionname'),array(30,'Pendapatan'), $db);
InsertRecord('accountsection',array('sectionid'),array(40),array('sectionid','sectionname'),array(40,'Biaya'), $db);
InsertRecord('accountgroups',array('groupname'),array('Aktiva Lain-lain'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Aktiva Lain-lain','10','0','32767',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Aktiva Tetap'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Aktiva Tetap','10','0','32767',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Beban'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Beban','40','1','5000',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Beban Lain-Lain'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Beban Lain-Lain','40','1','32767',''), $db);
InsertRecord('accountgroups',array('groupname'),array('COGS'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('COGS','40','1','32767',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Harga Pokok Penjualan'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Harga Pokok Penjualan','40','1','2000',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Inventory'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Inventory','10','0','32767',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Kas Bank'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Kas Bank','10','0','10000',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Keuangan'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Keuangan','40','1','32767',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Kewajiban'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Kewajiban','20','0','32767',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Kewajiban Jangka Panjang'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Kewajiban Jangka Panjang','20','0','32767',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Modal'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Modal','20','0','32767',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Pendapatan'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Pendapatan','30','1','32767',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Pendapatan Lain'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Pendapatan Lain','10','0','32767',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Penjualan'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Penjualan','30','1','1000',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Penyusutan'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Penyusutan','40','1','32767',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Piutang Dagang'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Piutang Dagang','10','0','20000',''), $db);
InsertRecord('accountgroups',array('groupname'),array('Utang Dagang'),array('groupname','sectioninaccounts','pandl','sequenceintb','parentgroupname'),array('Utang Dagang','20','0','30000',''), $db);
InsertRecord('chartmaster',array('accountcaode'),array('1100'),array('accountcode','accountname','group_'),array('1100','Kas dan Bank IDR','Kas Bank'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('1200'),array('accountcode','accountname','group_'),array('1200','Mata Uang Asing','Kas Bank'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('1300'),array('accountcode','accountname','group_'),array('1300','Piutang Dagang/AR','Piutang Dagang'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('1410'),array('accountcode','accountname','group_'),array('1410','Inventory - Komponen','Inventory'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('1415'),array('accountcode','accountname','group_'),array('1415','Inventory - Perakitan Barang','Inventory'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('1501'),array('accountcode','accountname','group_'),array('1501','Uang Muka','Keuangan'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('1502'),array('accountcode','accountname','group_'),array('1502','Pajak Pembelian - VAT in','Aktiva Lain-lain'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('1599'),array('accountcode','accountname','group_'),array('1599','Transaksi Aktiva Tetap','Kas Bank'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('1700'),array('accountcode','accountname','group_'),array('1700','Akumulasi Penyusutan','Penyusutan'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('2100'),array('accountcode','accountname','group_'),array('2100','Utang Dagang/AP','Utang Dagang'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('2201'),array('accountcode','accountname','group_'),array('2201','Pembayaran Pajak','Kewajiban'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('2202'),array('accountcode','accountname','group_'),array('2202','Pembayaran Wajib','Kewajiban'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('3100'),array('accountcode','accountname','group_'),array('3100','Stok Awal','Modal'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('3200'),array('accountcode','accountname','group_'),array('3200','Stock Biasa','Modal'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('3400'),array('accountcode','accountname','group_'),array('3400','Laba ditahan','Modal'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('4100'),array('accountcode','accountname','group_'),array('4100','Penjualan','Pendapatan'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('4200'),array('accountcode','accountname','group_'),array('4200','Penjualan Kembali dan Diskon','Pendapatan'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('5101'),array('accountcode','accountname','group_'),array('5101','Harga Pokok Penjualan','COGS'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('5102'),array('accountcode','accountname','group_'),array('5102','Diskon Temin Pembelian','COGS'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('5103'),array('accountcode','accountname','group_'),array('5103','Ongkos Masuk','COGS'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('6100'),array('accountcode','accountname','group_'),array('6100','Beban Penjualan','Beban'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('6200'),array('accountcode','accountname','group_'),array('6200','Administrasi dan Beban Umum','Beban'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('6205'),array('accountcode','accountname','group_'),array('6205','Beban Penyusutan','Beban'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('6300'),array('accountcode','accountname','group_'),array('6300','Selisih Mata Uang Asing','Penjualan'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('7101'),array('accountcode','accountname','group_'),array('7101','Pendapatan Bunga Bank','Pendapatan Lain'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('7102'),array('accountcode','accountname','group_'),array('7102','Pendapatan Kas','Pendapatan Lain'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('8101'),array('accountcode','accountname','group_'),array('8101','Beban Administrasi Bank','Beban Lain-Lain'), $db);
InsertRecord('chartmaster',array('accountcaode'),array('8102'),array('accountcode','accountname','group_'),array('8102','Beban Bunga Bank','Beban Lain-Lain'), $db);
?>