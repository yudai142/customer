<?php
try{
   $pdo = new PDO('mysql:dbname=customer;host=localhost;charset=utf8','root','root',[
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
   ]);

   header('Content-Type: application/json; charset=utf-8');
   switch($_POST['type']){
      case 'list':
         // 登録した情報のリスト取得
         echo json_encode($pdo->query('SELECT * FROM customer')->fetchAll());
         exit;
      case 'add':
         // 追加処理
         $stmt = $pdo->prepare('INSERT INTO customer(name,email,created) VALUES(?,?,now())');
         $stmt->execute([$_POST['name'],$_POST['email']]);
         exit;
      case 'edit_target':
         // IDから更新したいデータを取得
         $stmt = $pdo->prepare('SELECT id,name,email FROM customer WHERE id = ?');
         $stmt->execute([$_POST['id']]);
         echo json_encode($stmt->fetch());
         exit;
      case 'edit':
         // 更新処理
         $stmt = $pdo->prepare('UPDATE customer SET name = ?,email = ?,modified = now() WHERE id = ?');
         $stmt->execute([$_POST['name'],$_POST['email'],$_POST['editid']]);
         exit;
      case 'delete':
         // 削除処理
         $stmt = $pdo->prepare('DELETE FROM customer WHERE id = ?');
         $stmt->execute([$_POST['id']]);
         exit;
   }
}catch(PDOException $e){
   exit($e->getMessage());
}
