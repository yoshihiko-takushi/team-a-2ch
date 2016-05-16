# よくわかる（かもしれない）PHP
 
## 型や値について

### 配列

```php
<?php
$array = ['a', 'b', 'c', 'd'];

// 取得はこんな
echo $array[0]; // a
```

### 連想配列


```php
<?php
$aArray = [
    'a' => 0,
    'b' => 1,
    'c' => 2,
];

// 取得
echo $array['a']; // 0

```

#### 全部取りたい！

配列や連想配列のループは```foreach```がベター

```php
<?php

// keyとvalueを同時とり
foreach($array as $k => $v) {
    echo 'key:' . $k;
    echo 'value:' . $v;
}

// 値だけ
foreach($array as $v) {
    echo 'value:' . $v;
}
```


*** keyだけ取り出したい場合はー？ ***

`array_key()`に配列を渡すとkeyだけ取得してくれる
`foreach()`はkeyは省略できるが、valueは省略できないのでこうする
```php
<?php

foreach(array_key($array) as $k) {
    echo 'key:' . $k;
}
```

### クラス

```php
<?php
class Guest
{
    // コンストラクター
    // 今は気にしなくてよし！
    public function __construct()
    {
    }

    // クラスの中に書く変数をメンバー変数（プロパティ）といいます
    // "public"はアクセス権です。クラス外でもアクセスするこができます
    public $user;

    // privateはクラス内のみで使用することができる
    private $description;

    // アクセス権はメソッドにも使える
    // メソッドは、アクセス権のあとに"function"を書く
    public function getUser() {
        // メンバー変数とローカル変数の違い
        // ローカル変数はメソッドの中で宣言した変数
        $description = '説明'; // <= こいつのこと

        /**
         * メンバー変数は先ほど説明した通りの変数
         * 簡単に解釈するとメソッド内で宣言した変数 => ローカル変数
         * メソッド外、クラス内で宣言した変数 => メンバー変数
         *
         * ということで下の変数代入はメンバー変数にローカル変数の値を代入していることになる
         * 同じ名前だけど値としては全然違う
         */
        $this->description = $description;

        // $thisってなぁに？
        // A. 自分のクラスのことを指す
        // 今回はGuest Classのことを指す
        return $this->user;
    }

    public function getDescription()
    {
        return $this->description;
    }

    // プライベートメソッド
    // 返り値（return）がないメソッドのことを voidメソッドといいます。
    private function g() {
        echo 'テスト';
    }

    // アクセス権は省略してもおｋ
    // 省略した場合はpublicがデフォルトで設定される
    // つけたほうがわかりやすいし読みやすいので、必ず付けてください
    function nanashi() {
        echo '名無し';
    }
}
```

### クラスの使い方
```php
<?php
// new 演算子を前に置いて、クラス名と括弧＋括弧閉じでインスタンス化
// いわゆるオブジェクト化を行う
// この式では$guestの変数はオブジェクトGuestが代入されたことになる
$guest = new Guest();

// クラスのメンバー変数、メソッドなどを使いたい場合は"->"を使ってアクセスする
$guestUser = $guest->user; // メンバー変数
$guestDescription = $guest->getDescription();  // メソッド

// 注意点としては private のメンバー変数、メソッドはアクセスできない
$guestPrivateDescription = $guest->description; // Guest->description っていうメンバー変数はprivateなのでこれはダメ
```

### コラム・PHPの怖い話

```php
<?php
$guestUser2 = $guest->user; // これはおｋ。なぜなら Guestクラスにはアクセス権がpublicなメンバー変数 user があるから

$guestUser3 = $guest->user2; // これはダメでしょう。なぜなら Guestクラスにはそういう名前のメンバー変数が存在しないから（どや！）

// じゃあこうするは！
$guest->user2 = 'test2'; // えっ、存在しないのに？
$guestUser3 = $guest->user2; // wwwwむりぽｗｗｗ
```

いやいや〜wwww

***おｋです*** （えっ

コードに存在しないのにあとからClassへメンバー変数を定義することができます。
これは他の言語ではほとんど見ないPHP特有の機能です

「あとから追加できるなんて、汎用的で素敵！」なんて思うかもしれませんが
コードを品質上「書いてあること以外のことが起きるかもしれない」など、管理する側からしてみればたまったもんじゃありません。
なので、こういうコードは絶対に書かないようにしましょう。


### PHPの条件分岐
```php
<?php
$i = 1; // これはint型の1です
$iStr = '1'; // これはstring型の"1"です

// ここで条件分岐
if ($i == $iStr) {
    echo 'この条件は通ります？'; // 通ります
}
```

PHPの条件分岐"=="は型が違くても値が一緒だったらその条件は通ります
しかし...

```php
<?php
$iBool = true; // bool型のtrueです（falseってのもある）
if ($i == $iBool) {
    echo 'この条件は通らないでしょー？'; // 通ります（？！
}
```

と、このようにPHPは”自動変換”というものが存在します

* boolean の FALSE
* integer の 0 (ゼロ)
* float の 0.0 (ゼロ)
* 空の文字列、 および文字列の "0"
* 要素の数がゼロである 配列
* メンバ変数の数がゼロである オブジェクト (PHP 4のみ)
* 特別な値 NULL (値がセットされていない変数を含む)
* 空のタグから作成された SimpleXML オブジェクト

↑のリストの中はすべて0として解釈されてしまうため、条件分岐ではしばしば望んでいない動きになったりするので注意しなければなりません

*** じゃあ・・・どうすれば・・・？ ***



#### 型チェック付きの条件分岐

値だけのチェックだと自動変換という落とし穴が存在するので型もチェックすれば安全となります
コードではこう書きます
```php
<?php
if ($i === $iBool) { // == を === へ変更
    echo 'この条件は通らない・・・？（震え声）'; // 通りません！（´；∀；｀)ﾌﾞﾜｯ！
}
```

↑の例は $i (int型の1）と $iBool (boolean型のtrue）の型と値をチェックしました。
trueは自動変換で1になりますが、型はintとbooleanと違うので、条件をパスしないということです。
条件分岐を使う場合は必ず"==="のイコール３つを使うように意識してください
ちなみに似たような言語ではjavascriptもこれに当たります


## リクエスト取得

*** あの人の思い（request）を受け取りたい！ ***

### GET編

ひと昔は`$_GET`とは、`$_POST`などのスーパーグローバル変数へアクセスして取得していました。

しかし、このグローバル変数は値を書き換えることができるため危険という声が昔からありました。

そのことから、グローバル変数をラップ（今は"安全に取り出せるように"する技法のことと理解してください）する関数`input_filter()`を使って取り出すのがベターとなっています。

```php
<?php
$id = filter_input(INPUT_GET, 'id'); // HTTP GETメソッドからidの値を取得する

?>
<!-- 飛ばす方法 -->
<form action="get.php" method="get">
    <input type="text" name="id">
    <input type="submit" value="送信">
</form>
```


### POST編
```php
<!-- こうすると配列で飛ばすことができる。ただし、メソッドはPOSTで飛ばすこと-->
    <form action="post.php" method="post">
        <input type="text" name="data[id]">
        <input type="text" name="data[message]">
        <input type="submit" value="送信">
    </form>

<?php
$comment = filter_input(INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
echo $comment['id'];
echo $comment['message'];
?>
```


## DB接続・操作


## 外部ファイルの読み込み


*** あの人が書いた関数やクラスを使いたいけど。どうすれば。。。 ***

外部ファイルの読み込みは単純です。

例えば、`util.php`というファイルがあれば
```php
<?php

require('./util.php');
```

で、おｋ。

同じ機能で`include()`という関数がありますが、存在しないファイルをロードする場合`require()`の場合はエラーになりますが、`include()`はエラーにならず、スルーしてしまうので、`require()`を使ったほうがデバッグしやすいです。


また、`require()`で注意する点は、同じクラス名、同じメソッド名が宣言されていたらエラーになるので注意です。


ex)
A.php が、B.phpをロード

- A.php
```php
<?php

require('./B.php');

function test() { // B.phpにも関数test()が存在するためエラーが発生する
    // ...
}

```

- B.pphp
```php
<?php

function test() {
    // ...
}

```

もし上記の状態を回避したいのであれば、Classにしてしまえばいい。

- A.php
```php
<?php
require('./B.php');

function test() { // B.phpにも関数test()が存在するが、B -> testなので、エラーは発生しない
    // ...
}

// Bの関数test()を使いたい場合はオブジェクト化してしまえばいい
$b = new B();
$b->test(); // Bクラスの関数test()
test(); // A.phpの関数test()
```

- B.php （Bはクラスにする）
```php
<?php

class B {
    public function test() {
        // ...
    }
}
```