<?php
session_start();
// membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","stockbarang");


//menambah barang baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn,"insert into stock (namabarang, deskripsi, stock) values('$namabarang', '$deskripsi', '$stock')");
    if($addtotable){
        header('location:index.php');
    } else{
        echo 'Gagal';
        header('location:index.php');
    }
};

//Menambah Barang Masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang +$qty;

    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang, keterangan, qty) values('$barangnya', '$penerima', '$qty')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    } else{
        echo 'Gagal';
        header('location:masuk.php');
    }
};


//Menambah Barang Keluar
if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;

    $addtokeluar = mysqli_query($conn,"insert into keluar (idbarang, penerima, qty) values('$barangnya', '$penerima', '$qty')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtokeluar && $updatestockmasuk){
        header('location:keluar.php');
    } else{
        echo 'Gagal';
        header('location:keluar.php');
    }
}


//update info barang
if(isset($_POST['updatebarang'])){
    $id = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn,"update stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang = '$id'");
    if($update){
        header('location:index.php');
    } else{
        echo 'Gagal update barang';
        header('location:index.php');
    }
}  

//Menghapus barang dari stock
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb']; 

    $hapus = mysqli_query($conn,"delete from stock where idbarang = '$id'");
    if($hapus){
        header('location:index.php');
    } else{
        echo 'Gagal hapus baranag';
        header('location:index.php');
    }
};

//mengubah data barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb']; 
    $idb = $_POST['idm']; 
    $deskripsi = $_POST['keterangan']; 
    $qty = $_POST['qty']; 

    $lihatstock = mysqli_query($conn,"select * from stock where idbarang='$idb'"); 
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

   $lihatqty =mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
   $qtynya = mysqli_fetch_array($lihatqty);
   $qtyskrg = $qtynya['qty'];

   if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $stokbaru = $stockskrg + $selisih;
    } else {
      $selisih = $qtyskrg-$qty;
      $stokbaru = $stockskrg - $selisih;
    }

    $updateStock = mysqli_query($conn, "UPDATE stock SET stock='$stokbaru' WHERE idbarang='$idb'");
    $updateMasuk = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");

    if($updatestock && $updatemasuk){
        header('location:masuk.php');
    } else {
        echo 'gagal';
        header('location:masuk.php');
        }
    }

    //menghapus barang masuk
    if(isset($_POST['hapusbarangmasuk'])){
        $idb = $_POST['idb'];
        $qty = $_POST['kty'];
        $idm = $_POST['idm'];
    
        // Ambil stock sekarang
        $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
        $data = mysqli_fetch_array($getdatastock);
        $stocksekarang = $data['stock'];
    
        $selisih = $stock - $qty;

        // Hitung pengembalian stock
        $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");
        $hapusdata = mysqli_query($conn, "delete from masuk where idmasuk='$idm'");

        if($update&&$hapusdata){
            header('location:masuk.php');
        } else {
            header('location:masuk.php'); 
        }
        }

//membuat tanggal masuk
if (isset($_POST['tanggal'])) {
    $tanggal = $_POST['tanggal'];
} else {
    $tanggal = '';  // Nilai default jika tidak ada input
}

//membuat nama barang
if (isset($_POST['namabarang'])) {
    $namabarang = $_POST['namabarang'];
} else {
    $namabarang = 'Nama Barang Tidak Diketahui';  // Nilai default
}

//membuat jumlah barang
if (isset($_POST['qty'])) {
    $qty = $_POST['qty'];  // Mengambil nilai qty dari input form
} else {
    $qty = 0;  // Nilai default jika tidak ada input
}

//membuat keterangan
if (isset($_POST['keterangan'])) {
    $keterangan = $_POST['keterangan'];  // Mengambil nilai keterangan dari form input
} else {
    $keterangan = 'Tidak ada keterangan';  // Nilai default jika keterangan tidak ada
}

//mengubah data barang keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb']; 
    $idb = $_POST['idk']; 
    $penerima = $_POST['penerima']; 
    $qty = $_POST['qty']; 

    $lihatstock = mysqli_query($conn,"select * from stock where idbarang='$idb'"); 
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

   $lihatqty =mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
   $qtynya = mysqli_fetch_array($lihatqty);
   $qtyskrg = $qtynya['qty'];

   if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $stokbaru = $stockskrg - $selisih;
    } else {
      $selisih = $qtyskrg-$qty;
      $stokbaru = $stockskrg + $selisih;
    }

    $updateStock = mysqli_query($conn, "UPDATE stock SET stock='$stokbaru' WHERE idbarang='$idb'");
    $updateMasuk = mysqli_query($conn, "UPDATE keluar SET qty='$qty', keterangan='$deskripsi' WHERE idkeluar='$idk'");

    if($updatestock && $updatemasuk){
        header('location:keluar.php');
    } else {
        echo 'gagal';
        header('location:keluar.php');
        }
    }

    //menghapus barang keluar
    // menghapus barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];     // id barang
    $qty = $_POST['qty'];     // jumlah barang yang keluar
    $idk = $_POST['idk'];     // id transaksi keluar

    // Ambil stock sekarang
    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stocksekarang = $data['stock'];

    // Tambahkan kembali qty ke stock
    $selisih = $stocksekarang + $qty;

    // Update stok
    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");

    // Hapus data dari tabel keluar
    $hapusdata = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idk'");

    if($update && $hapusdata){
        header('location:keluar.php');
    } else {
        header('location:keluar.php');
    }
}
