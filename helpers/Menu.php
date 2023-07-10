<?php
/**
 * Menu Items
 * All Project Menu
 * @category  Menu List
 */

class Menu{
	
	
			public static $navbartopleft = array(
		array(
			'path' => 'home', 
			'label' => 'Home', 
			'icon' => '<i class="fa fa-desktop "></i>'
		),
		
		array(
			'path' => 'data_sepeda', 
			'label' => 'Master data', 
			'icon' => '<i class="fa fa-recycle "></i>','submenu' => array(
		array(
			'path' => 'data_prodi', 
			'label' => 'Data Prodi', 
			'icon' => '<i class="fa fa-graduation-cap "></i>'
		),
		
		array(
			'path' => 'denda_berlaku', 
			'label' => 'Denda Berlaku', 
			'icon' => '<i class="fa fa-warning "></i>'
		),
		
		array(
			'path' => 'data_sepeda', 
			'label' => 'Data Sepeda', 
			'icon' => '<i class="fa fa-bicycle "></i>'
		)
	)
		),
		
		array(
			'path' => 'users', 
			'label' => 'Pengguna', 
			'icon' => '<i class="fa fa-users "></i>'
		),
		
		array(
			'path' => 'penyewaan', 
			'label' => 'Peminjaman', 
			'icon' => '<i class="fa fa-slideshare "></i>'
		),
		
		array(
			'path' => 'pemgembalian', 
			'label' => 'Pengembalian', 
			'icon' => '<i class="fa fa-retweet "></i>'
		),
		
		array(
			'path' => 'penyewaan/Laporan', 
			'label' => 'Laporan', 
			'icon' => '<i class="fa fa-file-text-o "></i>'
		),
		
		array(
			'path' => 'penyewaan/page_user', 
			'label' => 'Page User', 
			'icon' => ''
		),
		
		array(
			'path' => 'penyewaan/user_pinjam', 
			'label' => 'User Pinjam', 
			'icon' => ''
		),
		
		array(
			'path' => 'penyewaan/halaman_user', 
			'label' => 'History Peminjaman', 
			'icon' => '<i class="fa fa-recycle "></i>'
		),
		
		array(
			'path' => 'pemgembalian/user_penegembalian', 
			'label' => 'History Penegembalian', 
			'icon' => '<i class="fa fa-joomla "></i>'
		),
		
		array(
			'path' => 'penyewaan/halaman_user_add', 
			'label' => 'Peminjaman', 
			'icon' => '<i class="fa fa-bicycle "></i>'
		)
	);
		
	
	
			public static $status = array(
		array(
			"value" => "-1", 
			"label" => "Diterima", 
		),
		array(
			"value" => "1", 
			"label" => "Ditolak", 
		),
		array(
			"value" => "Pending", 
			"label" => "Pending", 
		),);
		
			public static $level = array(
		array(
			"value" => "Administrator", 
			"label" => "Administrator", 
		),
		array(
			"value" => "User", 
			"label" => "User", 
		),);
		
			public static $account_status = array(
		array(
			"value" => "Active", 
			"label" => "Active", 
		),
		array(
			"value" => "Pending", 
			"label" => "Pending", 
		),
		array(
			"value" => "Blocked", 
			"label" => "Blocked", 
		),);
		
			public static $status_pengembalian = array(
		array(
			"value" => "1", 
			"label" => "Sudah Dikembalikan", 
		),);
		
}