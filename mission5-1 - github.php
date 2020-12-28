<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <?php
	    // DB接続設定
	    $dsn = 'mysql:dbname=データベース名;host=ホスト名';
	    $user = 'ユーザー名';
	    $password = 'パスワード';
	    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	    //テーブルの作成   
	    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
	    ." ("
	    . "id INT AUTO_INCREMENT PRIMARY KEY,"
	    . "name char(32),"
	    . "comment TEXT,"
	    ."date char(32),"
	    ."password char(32)"
	    .");";
	    $stmt = $pdo->query($sql);
	    
	    //送信ボタンが押された場合
        if (!empty($_POST["submit"])){
        	$comment = $_POST["comment"];               //投稿フォームのコメントから値を受け取る
        	$name = $_POST["name"];                     //投稿フォームの名前から値を受け取る
        	$password = $_POST["password"];             //投稿フォームのパスワードから値を受け取る
        	$date = date("Y/m/d/ H:i:s");               //投稿時間を取得
        	$s = $_POST["a"];                           //編集か新規投稿かを区別する値を受け取る
            if(empty($s)){//新規投稿
                if($comment==""){//コメントが空の場合
                    if($name==""){//名前が空の場合
                        if($password==""){//パスワードが空の場合
                            echo "名前・コメント・パスワードを入力してください"."<br>";//エラーメッセージを表示
                        }else{//パスワードが空でない場合
                            $c=$password;
                            echo "コメント・名前を入力してください"."<br>";//エラーメッセージを表示
                        }
                    }else{//名前が空でない場合
                        if($password==""){//パスワードが空の場合
                            $a=$name;
                            echo "コメント・パスワードを入力してください"."<br>";//エラーメッセージを表示
                        }else{//パスワードが空でない場合
                            $a=$name;
                            $c=$password;
                            echo "コメントを入力してください"."<br>";//エラーメッセージを表示
                        }
                    }
                }else{//コメントが空でない場合
                    if($name==""){//名前が空の場合
                        if($password==""){//パスワードが空の場合
                            $b=$comment;
                            echo "名前・パスワードを入力してください"."<br>";//エラーメッセージを表示
                        }else{//パスワードが空でない場合
                            $b=$comment;
                            $c=$password;
                            echo "名前を入力してください"."<br>";//エラーメッセージを表示
                        }
                    }else{//名前が空でない場合
                        if($password==""){//パスワードが空の場合
                            $a=$name;
                            $b=$comment;
                            echo "パスワードを入力してください"."<br>";//エラーメッセージを表示
                        }else{//パスワードが空でない場合
                        	//データベースに新規に書き込む
                            $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment,date,password)VALUES (:name, :comment,:date,:password)");
	                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	                        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	                        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	                        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
	                        $sql -> execute();
                            echo $name."さん（送信内容）受け付けました". "<br>";//メッセージを表示
                        }
                    }   
                }
            }else{//編集
                if($comment==""){//コメントが空の場合
                    if($name==""){//名前が空の場合
                        if($password==""){//パスワードが空の場合
                            echo "名前・コメント・パスワードを入力してください"."<br>";//エラーメッセージを表示
                        }else{//パスワードが空でない場合
                            $c=$password;
                            echo "名前・コメントを入力してください"."<br>";//エラーメッセージを表示
                        }
                    }else{//名前が空でない場合
                        if($password==""){//パスワードが空の場合
                            $a=$name;
                            echo "コメント・パスワードを入力してください"."<br>";//エラーメッセージを表示
                        }else{//パスワードが空でない場合
                            $a=$name;
                            $c=$password;
                            echo "コメントを入力してください"."<br>";//エラーメッセージを表示
                        }
                    }
                }else{//コメントが空でない場合
                    if($name==""){//名前が空の場合
                        if($password==""){//パスワードが空の場合
                            $b=$comment;
                            echo "名前・パスワードを入力してください"."<br>";//エラーメッセージを表示
                        }else{//パスワードが空でない場合
                            $b=$comment;
                            $c=$password;
                            echo "名前を入力してください"."<br>";//エラーメッセージを表示
                        }
                    }else{//名前が空でない場合
                        if($password==""){//パスワードが空の場合
                            $a=$name;
                            $b=$comment;
                            echo "パスワードを入力してください"."<br>";
                        }else{//パスワードが空でない場合
                            $id = $s; //変更する投稿番号
                            //データベースを編集
	                        $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
	                        $stmt = $pdo->prepare($sql);
	                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	                        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
	                        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
	                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                        $stmt->execute();  
                            echo $name."さん（編集内容）受け付けました". "<br>";//メッセージを表示
                        }
                    }
                }
            }
        }
        
        //削除ボタンが押された場合
        if(!empty($_POST["delate"])){
            $num = $_POST["num"];                       //削除フォームの削除番号から値を受け取る
            $password_delate = $_POST["password_delate"];//削除フォームのパスワードから値を受け取る
            if($num==""){//削除番号が空の場合
                if($password_delate==""){//パスワードが空の場合
                    echo "削除番号・パスワードを入力してください"."<br>";//エラーメッセージを表示
                }else{//パスワードが空でない場合
                    $e=$password_delate;
                    echo "削除番号を入力してください"."<br>";//エラーメッセージを表示
                }
            }else{
                if($password_delate==""){//パスワードが空の場合
                    $d=$num;
                    echo "パスワードを入力してください"."<br>";//エラーメッセージを表示
                }else{//パスワードが空でない場
                    //削除したい投稿のパスワードを取得
                    $password="";  //変数passwordを初期化
                    $id = $num ; // idがこの値のデータだけを抽出する
                    $sql = 'SELECT * FROM tbtest WHERE id=:id ';
                    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                    $stmt->execute();                             // ←SQLを実行する。
                    $results = $stmt->fetchAll(); 
	                foreach ($results as $row){
		                $password=$row['password'];
	                }
	                if(!empty($password)){//投稿が存在する場合
                        if($password_delate==$password){//パスワードが正しい場合
                            //指定のデータを削除   
	                        $id = $num;
	                        $sql = 'delete from tbtest where id=:id';
	                        $stmt = $pdo->prepare($sql);
	                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                        $stmt->execute();
	                        echo "削除しました". "<br>";//メッセージを表示
                        }else{//パスワードが正しくない場合
                            $d=$num;
                            echo "パスワードが間違っています". "<br>";//エラーメッセージを表示
                        }
                    }else{//投稿が存在しない場合
                        echo "既に削除されています". "<br>"; //エラーメッセージを表示
                    }
                }
            }
        }
        
	    //編集ボタンが押された場合
        if(!empty($_POST["edit"])){
            $edit = $_POST["editnum"];                  //編集フォームの編集番号から値を受け取る
            $password_edit = $_POST["password_edit"];   //投稿フォームのパスワードから値を受け取る
            if($edit==""){//編集番号が空の場合
                if($password_edit==""){//パスワードが空の場合
                    echo "編集番号・パスワードを入力してください"."<br>";//エラーメッセージを表示
                }else{//パスワードが空でない場合
                    $g=$password_edit;
                    echo "編集番号を入力してください"."<br>";//エラーメッセージを表示
                }
            }else{//編集番号が空でない場合
                if($password_edit==""){//パスワードが空の場合
                    $f=$edit;
                    echo "パスワードを入力してください"."<br>";//エラーメッセージを表示
                }else{//パスワードが空でない場合
                	//編集したい投稿の名前・コメント・パスワードを取得
                    $id = $edit ; // idがこの値のデータだけを抽出する
                    
                    $sql = 'SELECT * FROM tbtest WHERE id=:id ';
                    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                    $stmt->execute();                             // ←SQLを実行する。
                    $results = $stmt->fetchAll(); 
	                foreach ($results as $row){
		                $name=$row['name'];
		                $comment=$row['comment'];
		                $password=$row['password'];
	                }
                    if($password==$password_edit){//パスワードが一致した場合
                        $a=$name;
                        $b=$comment;
                        $c=$password;
                    }else{//パスワードが一致しない場合
                        $f=$edit;
                        echo "パスワードが間違っています"; //エラーメッセージを表示
                    }
                }
            }
        }
    ?>
    <form method="POST" action="" >
        【投稿フォーム】<br>
        　　　名前:<input type="text" name="name" placeholder="名前" value="<?php if(!empty($a)){echo $a;} ?>"><br>
        　コメント:<input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($b)){echo $b;} ?>"><br>
        パスワード:<input type="text" name="password" placeholder="パスワード" value="<?php if(!empty($c)){echo $c;} ?>" autocomplete="off"><br>
        <input type="hidden" name="a" value="<?php if(!empty($edit)){echo $edit;} ?>">
        <input type="submit" name="submit" value="送信"><br>
        【削除フォーム】<br>
        　　　削除:<input type="text" name="num" placeholder="削除番号" value="<?php if(!empty($d)){echo $d;} ?>"><br>
        パスワード:<input type="text" name="password_delate" placeholder="パスワード" value="<?php if(!empty($e)){echo $e;} ?>" autocomplete="off"><br>
        <input type="submit" name="delate" value="削除"><br>
        【編集フォーム】<br>
        　　　編集:<input type="text" name="editnum" placeholder="編集番号" value="<?php if(!empty($f)){echo $f;} ?>"><br>
        パスワード:<input type="text" name="password_edit" placeholder="パスワード" value="<?php if(!empty($g)){echo $g;} ?>" autocomplete="off"><br>
        <input type="submit" name="edit" value="編集"><br>
    </form>
    <?php
    //投稿の表示
        echo "投稿一覧".'<br>';
	    $sql = 'SELECT * FROM tbtest';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
		       //$rowの中にはテーブルのカラム名が入る
		    echo $row['id'].',';
		    echo $row['name'].',';
		    echo $row['comment'].',';
		    echo $row['date'].'<br>';
	    echo "<hr>";
	    }
	?>
</body>
</html>